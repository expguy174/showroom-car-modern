<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceAppointment;
use App\Models\Showroom;
use App\Models\CarVariant;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ServiceAppointmentController extends Controller
{
    public function index()
    {
        $appointments = ServiceAppointment::where('user_id', Auth::id())
            ->with(['showroom', 'carVariant.carModel.carBrand'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        return view('user.service-appointments.index', compact('appointments'));
    }

    public function create()
    {
        $showrooms = Showroom::where('is_active', true)->get();
        $carVariants = CarVariant::with(['carModel.carBrand'])->get();
        return view('user.service-appointments.create', compact('showrooms', 'carVariants'));
    }

    public function store(Request $request)
    {
        try {
            // Preflight: ensure required table exists to avoid 500 from DB
            if (!Schema::hasTable('service_appointments')) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Chưa khởi tạo bảng 'service_appointments'. Vui lòng chạy migrate.",
                    ], 500);
                }
                return back()->with('error', "Chưa khởi tạo bảng 'service_appointments'. Vui lòng chạy migrate.");
            }
            $validated = $request->validate([
                'showroom_id' => 'required|exists:showrooms,id',
                'car_variant_id' => 'required|exists:car_variants,id',
                'appointment_type' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::APPOINTMENT_TYPES),
                'appointment_date' => 'required|date|after:today',
                // Chuẩn HH:MM 00-23:00-59
                'appointment_time' => [
                    'required','string',
                    function($attr,$value,$fail){
                        if (!preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', (string)$value)) {
                            $fail('Giờ hẹn không hợp lệ (định dạng HH:MM).');
                        }
                    }
                ],
                'customer_name' => 'required|string|max:255',
                'customer_phone' => [
                    'required','string','min:10','max:15',
                    function($attr,$value,$fail){
                        if (!preg_match('/^[0-9+\-\s()]+$/', (string)$value)) {
                            $fail('Số điện thoại không hợp lệ.');
                        }
                    }
                ],
                'customer_email' => 'required|email|max:255',
                'vehicle_vin' => 'nullable|string|max:17',
                'vehicle_registration' => 'nullable|string|max:20',
                'vehicle_model' => 'nullable|string|max:255',
                'vehicle_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
                'current_mileage' => 'nullable|numeric|min:0|max:999999',
                'requested_services' => 'required|string|max:1000',
                'service_description' => 'nullable|string|max:1000',
                'customer_complaints' => 'nullable|string|max:500',
                'special_instructions' => 'nullable|string|max:500',
                'is_warranty_work' => 'boolean',
                'payment_status' => 'nullable|in:' . implode(',', \App\Models\ServiceAppointment::PAYMENT_STATUSES),
                'notes' => 'nullable|string|max:1000',
            ], [
                'scheduled_time.regex' => 'Thời gian không hợp lệ (HH:MM)',
                'customer_phone.regex' => 'Số điện thoại không hợp lệ',
                'vehicle_year.min' => 'Năm sản xuất không hợp lệ',
                'vehicle_year.max' => 'Năm sản xuất không hợp lệ',
                'current_mileage.max' => 'Số km không hợp lệ',
            ]);

            // Check showroom availability
            $showroom = \App\Models\Showroom::find($validated['showroom_id']);
            if (!$showroom || !$showroom->is_active) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Showroom không khả dụng',
                        'errors' => ['showroom_id' => ['Showroom không khả dụng']],
                    ], 422);
                }
                return back()->withErrors(['showroom_id' => 'Showroom không khả dụng'])->withInput();
            }

            // Chuẩn hoá giờ (HH:MM -> HH:MM:00) để khớp kiểu time trong DB
            $normalizedTime = preg_match('/^\\d{2}:\\d{2}:\\d{2}$/', (string) $validated['appointment_time'])
                ? $validated['appointment_time']
                : ($validated['appointment_time'] . ':00');

            // Check if time slot is available
            $scheduledDateTime = $validated['appointment_date'] . ' ' . $normalizedTime;
            $existingAppointment = ServiceAppointment::where('showroom_id', $validated['showroom_id'])
                ->where('appointment_date', $validated['appointment_date'])
                ->where('appointment_time', $normalizedTime)
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($existingAppointment) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Thời gian này đã được đặt. Vui lòng chọn thời gian khác.',
                        'errors' => ['scheduled_time' => ['Thời gian này đã được đặt. Vui lòng chọn thời gian khác.']],
                    ], 422);
                }
                return back()->withErrors(['scheduled_time' => 'Thời gian này đã được đặt. Vui lòng chọn thời gian khác.'])->withInput();
            }

            // Generate appointment number
            $appointmentNumber = 'SA' . date('Ymd') . strtoupper(uniqid());

            $data = $validated;
            $data['user_id'] = Auth::id();
            $data['appointment_number'] = $appointmentNumber;
            $data['status'] = 'scheduled';
            $data['appointment_time'] = $normalizedTime;

            $appointment = ServiceAppointment::create($data);

            // Send confirmation email
            try {
                $emailService = app(\App\Services\EmailService::class);
                $emailService->sendServiceAppointmentConfirmation($appointment);
                Log::info('Service appointment created successfully', ['appointment_id' => $appointment->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send service appointment confirmation email:', ['error' => $e->getMessage()]);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt lịch bảo dưỡng thành công! Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.',
                    'id' => $appointment->id,
                    'redirect' => route('user.service-appointments.show', $appointment->id),
                ]);
            }
            return redirect()->route('user.service-appointments.show', $appointment->id)
                ->with('success', 'Đặt lịch bảo dưỡng thành công! Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.');

        } catch (ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu chưa hợp lệ. Vui lòng kiểm tra lại.',
                    'errors' => $e->errors(),
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Service appointment creation error:', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            if ($request->expectsJson() || $request->ajax()) {
                $payload = [
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi đặt lịch. Vui lòng thử lại.',
                ];
                if (config('app.debug')) { $payload['error'] = $e->getMessage(); }
                return response()->json($payload, 500);
            }
            return back()->with('error', 'Có lỗi xảy ra khi đặt lịch. Vui lòng thử lại.')->withInput();
        }
    }

    public function show(ServiceAppointment $appointment)
    {
        // Check if user owns this appointment
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->load(['showroom', 'carVariant.carModel.carBrand']);

        return view('user.service-appointments.show', compact('appointment'));
    }

    public function edit(ServiceAppointment $appointment)
    {
        // Chỉ cho phép sửa khi là lịch của user và đang ở trạng thái 'scheduled'
        if ($appointment->user_id !== Auth::id() || $appointment->status !== 'scheduled') {
            abort(403);
        }

        $showrooms = Showroom::where('is_active', true)->get();
        $carVariants = CarVariant::with(['carModel.carBrand'])->get();

        return view('user.service-appointments.edit', compact('appointment', 'showrooms', 'carVariants'));
    }

    public function update(Request $request, ServiceAppointment $appointment)
    {
        // chỉ cho phép sửa khi khách vẫn còn "Đã lên lịch"
        if ($appointment->user_id !== Auth::id() || $appointment->status !== 'scheduled') {
            abort(403);
        }

        $validated = $request->validate([
            'showroom_id' => 'required|exists:showrooms,id',
            'car_variant_id' => 'required|exists:car_variants,id',
            'appointment_type' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::APPOINTMENT_TYPES),
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'vehicle_registration' => 'nullable|string|max:20',
            'current_mileage' => 'nullable|numeric|min:0',
            'service_description' => 'required|string|max:1000',
            'priority' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::PRIORITIES),
            'preferred_technician' => 'nullable|string|max:255',
            'special_instructions' => 'nullable|string|max:500',
            'estimated_cost' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:' . implode(',', \App\Models\ServiceAppointment::PAYMENT_METHODS),
            'status' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::STATUSES),
            // Bổ sung validation cho rating ở tầng ứng dụng
            'customer_satisfaction' => 'nullable|numeric|min:0|max:5',
        ]);

        // Chuẩn hoá giờ lưu DB
        if (isset($validated['appointment_time'])) {
            $validated['appointment_time'] = preg_match('/^\\d{2}:\\d{2}:\\d{2}$/', (string) $validated['appointment_time'])
                ? $validated['appointment_time']
                : ($validated['appointment_time'] . ':00');
        }

        $appointment->update($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật lịch bảo dưỡng thành công!',
                'redirect' => route('user.service-appointments.show', $appointment->id),
            ]);
        }

        return redirect()->route('user.service-appointments.show', $appointment->id)
            ->with('success', 'Cập nhật lịch bảo dưỡng thành công!');
    }

    public function cancel(ServiceAppointment $appointment)
    {
        // Check if user owns this appointment and it can be cancelled
        if ($appointment->user_id !== Auth::id() || !in_array($appointment->status, ['pending', 'confirmed'])) {
            abort(403);
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Cancelled by customer',
            'cancelled_at' => now()
        ]);

        return redirect()->route('user.service-appointments.index')
            ->with('success', 'Đã hủy lịch bảo dưỡng thành công!');
    }

    public function reschedule(Request $request, ServiceAppointment $appointment)
    {
        // Check if user owns this appointment and it can be rescheduled
        if ($appointment->user_id !== Auth::id() || !in_array($appointment->status, ['pending', 'confirmed'])) {
            abort(403);
        }

        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|string',
        ]);

        $normalizedTime = preg_match('/^\\d{2}:\\d{2}:\\d{2}$/', (string) $request->appointment_time)
            ? $request->appointment_time
            : ($request->appointment_time . ':00');

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $normalizedTime,
            'status' => 'scheduled',
        ]);

        return redirect()->route('user.service-appointments.show', $appointment->id)
            ->with('success', 'Đã yêu cầu đổi lịch thành công! Chúng tôi sẽ xác nhận trong thời gian sớm nhất.');
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'showroom_id' => 'required|exists:showrooms,id',
            'date' => 'required|date|after:today',
        ]);

        $date = Carbon::parse($request->date);
        $showroom = Showroom::find($request->showroom_id);

        // Get existing appointments for this date and showroom
        $existingAppointments = ServiceAppointment::where('showroom_id', $request->showroom_id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->get();

        // Define available time slots (9:00 AM to 5:00 PM, 1-hour slots)
        $availableSlots = [];
        $startTime = 9;
        $endTime = 17;

        for ($hour = $startTime; $hour < $endTime; $hour++) {
            $timeSlot = sprintf('%02d:00', $hour);       // hiển thị cho UI
            $dbTimeSlot = $timeSlot . ':00';             // so sánh trong DB (HH:MM:SS)
            $slotCount = $existingAppointments->where('appointment_time', $dbTimeSlot)->count();
            
            // Assume max 3 appointments per time slot
            if ($slotCount < 3) {
                $availableSlots[] = [
                    'time' => $timeSlot,
                    'available' => 3 - $slotCount,
                    'status' => $slotCount == 0 ? 'available' : 'limited'
                ];
            }
        }

        return response()->json([
            'date' => $date->format('Y-m-d'),
            'showroom' => $showroom->name,
            'available_slots' => $availableSlots
        ]);
    }

    public function getServiceHistory()
    {
        $appointments = ServiceAppointment::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with(['showroom', 'carVariant.carModel.carBrand'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(15);

        return view('user.service-appointments.history', compact('appointments'));
    }

    public function getUpcomingAppointments()
    {
        $appointments = ServiceAppointment::where('user_id', Auth::id())
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->where('appointment_date', '>=', now())
            ->with(['showroom', 'carVariant.carModel.carBrand'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        return response()->json($appointments);
    }
}
