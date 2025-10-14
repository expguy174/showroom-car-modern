<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\ServiceAppointment;
use App\Models\Service;
use App\Services\NotificationService;
use App\Models\Showroom;
use App\Models\CarVariant;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;

class ServiceAppointmentController extends Controller
{
    /**
     * Normalize various time inputs to HH:MM (24h).
     */
    protected function normalizeTimeToHHMM(?string $value): ?string
    {
        if ($value === null) return null;
        $trimmed = trim($value);
        // HH:MM:SS -> HH:MM
        if (preg_match('/^([01]?\d|2[0-3]):([0-5]\d):([0-5]\d)$/', $trimmed, $m)) {
            return sprintf('%02d:%02d', (int)$m[1], (int)$m[2]);
        }
        // e.g., 05:10 or 5:10
        if (preg_match('/^([01]?\d|2[0-3]):[0-5]\d$/', $trimmed)) {
            // Ensure leading zero for hour
            [$h, $m] = explode(':', $trimmed, 2);
            return sprintf('%02d:%02d', (int)$h, (int)$m);
        }
        // e.g., 05:10 AM, 5:10 pm
        if (preg_match('/^\s*(\d{1,2}:\d{2})\s*([AaPp][Mm])\s*$/', $trimmed, $m)) {
            try {
                $dt = Carbon::createFromFormat('g:i A', strtoupper($m[1] . ' ' . $m[2]));
                return $dt ? $dt->format('H:i') : null;
            } catch (\Throwable $e) {
                return null;
            }
        }
        return null;
    }

    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $serviceId = $request->string('service_id')->toString();
        $q = $request->string('q')->toString();

        $appointments = ServiceAppointment::where('user_id', Auth::id())
            ->with(['showroom', 'service', 'carVariant.carModel.carBrand'])
            ->when($status !== '', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($serviceId !== '', function ($query) use ($serviceId) {
                $query->where('service_id', $serviceId);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('appointment_number', 'like', "%{$q}%")
                        ->orWhereHas('carVariant.carModel', function ($cm) use ($q) {
                            $cm->where('name', 'like', "%{$q}%");
                        })
                        ->orWhereHas('showroom', function ($sr) use ($q) {
                            $sr->where('name', 'like', "%{$q}%");
                        })
                        ->orWhereHas('service', function ($svc) use ($q) {
                            $svc->where('name', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('user.service-appointments.partials.list', ['appointments' => $appointments])->render(),
                'pagination' => view('components.pagination-modern', ['paginator' => $appointments->withQueryString()])->render(),
                'summary' => view('user.service-appointments.partials.summary', ['paginator' => $appointments->withQueryString()])->render(),
            ]);
        }

        // Get services for filter dropdown
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('user.service-appointments.index', compact('appointments', 'services'));
    }

    public function create(Request $request)
    {
        $showrooms = Showroom::where('is_active', true)->get();
        $services = Service::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $carVariants = CarVariant::with(['carModel.carBrand'])->get();
        
        // Pre-select service if service_id is provided
        $selectedServiceId = $request->get('service_id');
        $selectedService = null;
        if ($selectedServiceId) {
            $selectedService = Service::find($selectedServiceId);
        }
        
        return view('user.service-appointments.create', compact('showrooms', 'services', 'carVariants', 'selectedService'));
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

            // Normalize time before validation (accept HH:MM or with AM/PM)
            $normalized = $this->normalizeTimeToHHMM($request->input('appointment_time'));
            if ($normalized) {
                $request->merge(['appointment_time' => $normalized]);
            }

            // Validate theo thứ tự ưu tiên - dừng ngay khi gặp error đầu tiên
            $validationRules = [
                // 1. Showroom
                ['showroom_id', 'required|exists:showrooms,id', 'Vui lòng chọn showroom.'],
                
                // 2. Appointment Date
                ['appointment_date', 'required|date|after:today', 'Vui lòng chọn ngày hẹn hợp lệ.'],
                
                // 3. Appointment Time  
                ['appointment_time', 'required|string', 'Vui lòng chọn giờ hẹn.'],
                
                // 4. Car Variant
                ['car_variant_id', 'required|exists:car_variants,id', 'Vui lòng chọn xe.'],
                
                // 5. Service ID
                ['service_id', 'required|exists:services,id', 'Vui lòng chọn dịch vụ.'],
                
                // 6. Requested Services
                ['requested_services', 'nullable|string|max:65535', 'Yêu cầu thêm quá dài.'],
            ];
            
            $validated = [];
            
            // Validate từng field theo thứ tự với custom messages
            foreach ($validationRules as [$field, $rules, $message]) {
                try {
                    $fieldValidation = $request->validate([
                        $field => $rules
                    ], [
                        $field . '.required' => $message,
                        $field . '.date' => $field === 'appointment_date' ? 'Ngày hẹn không hợp lệ.' : $message,
                        $field . '.after' => $field === 'appointment_date' ? 'Ngày hẹn phải sau hôm nay.' : $message,
                        $field . '.exists' => $field === 'showroom_id' ? 'Showroom không khả dụng.' : 
                                            ($field === 'service_id' ? 'Dịch vụ không khả dụng.' : 
                                            ($field === 'car_variant_id' ? 'Xe không khả dụng.' : $message)),
                        $field . '.string' => $message,
                        $field . '.max' => $field === 'requested_services' ? 'Yêu cầu thêm quá dài.' : $message,
                    ]);
                    $validated = array_merge($validated, $fieldValidation);
                } catch (\Illuminate\Validation\ValidationException $e) {
                    // Dừng ngay khi gặp error đầu tiên
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                            'errors' => [$field => [$message]],
                        ], 422);
                    }
                    return back()->withErrors([$field => $message])->withInput();
                }
            }
            
            // Custom validation cho appointment_date (weekend check)
            if (isset($validated['appointment_date'])) {
                $date = \Carbon\Carbon::parse($validated['appointment_date']);
                if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
                    $message = 'Không thể đặt lịch vào cuối tuần. Vui lòng chọn thứ 2 - thứ 6.';
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                            'errors' => ['appointment_date' => [$message]],
                        ], 422);
                    }
                    return back()->withErrors(['appointment_date' => $message])->withInput();
                }
            }
            
            // Custom validation cho appointment_time (business hours)
            if (isset($validated['appointment_time'])) {
                $time = $validated['appointment_time'];
                if (!preg_match('/^([01]?\d|2[0-3]):[0-5]\d$/', $time)) {
                    $message = 'Giờ hẹn không hợp lệ (định dạng HH:MM).';
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                            'errors' => ['appointment_time' => [$message]],
                        ], 422);
                    }
                    return back()->withErrors(['appointment_time' => $message])->withInput();
                }
                
                $hour = (int) substr($time, 0, 2);
                if ($hour < 9 || $hour >= 17) {
                    $message = 'Giờ hẹn phải trong khung giờ làm việc (09:00 - 16:59).';
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                            'errors' => ['appointment_time' => [$message]],
                        ], 422);
                    }
                    return back()->withErrors(['appointment_time' => $message])->withInput();
                }
            }
            
            // Validate additional fields (không dừng khi có lỗi)
            $additionalValidation = $request->validate([
                'vehicle_registration' => 'nullable|string|max:32',
                'current_mileage' => 'nullable|integer|min:0|max:4294967295', 
                'estimated_cost' => 'nullable|numeric|min:0|max:9999999999999.99',
                'is_warranty_work' => 'nullable|boolean',
                'service_description' => 'nullable|string|max:65535',
                'priority' => 'nullable|in:low,medium,high,urgent',
            ]);
            
            $validated = array_merge($validated, $additionalValidation);

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

            // Check service availability
            $service = \App\Models\Service::find($validated['service_id']);
            if (!$service || !$service->is_active) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Dịch vụ không khả dụng',
                        'errors' => ['service_id' => ['Dịch vụ không khả dụng']],
                    ], 422);
                }
                return back()->withErrors(['service_id' => 'Dịch vụ không khả dụng'])->withInput();
            }

            // Chuẩn hoá giờ (HH:MM -> HH:MM:00) để khớp kiểu time trong DB
            $normalizedTime = $validated['appointment_time'] . ':00';

            // Check if exact time slot is already taken
            $existingAppointment = ServiceAppointment::where('showroom_id', $validated['showroom_id'])
                ->where('appointment_date', $validated['appointment_date'])
                ->where('appointment_time', $normalizedTime)
                ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
                ->first();

            if ($existingAppointment) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Thời gian này đã được đặt. Vui lòng chọn thời gian khác.',
                        'errors' => ['appointment_time' => ['Thời gian này đã được đặt. Vui lòng chọn thời gian khác.']],
                    ], 422);
                }
                return back()->withErrors(['appointment_time' => 'Thời gian này đã được đặt. Vui lòng chọn thời gian khác.'])->withInput();
            }

            // Generate unique appointment number
            do {
                $appointmentNumber = 'SA-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
            } while (ServiceAppointment::where('appointment_number', $appointmentNumber)->exists());

            $data = $validated;
            $data['user_id'] = Auth::id();
            $data['appointment_number'] = $appointmentNumber;
            $data['status'] = 'scheduled';
            $data['priority'] = $validated['priority'] ?? 'medium'; // Default priority
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
                    'redirect' => route('user.service-appointments.index'),
                ]);
            }
            return redirect()->route('user.service-appointments.index')
                ->with('toast.kind', 'success')
                ->with('toast.msg', 'Đặt lịch bảo dưỡng thành công! Chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất.');

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

        $appointment->load(['showroom', 'service', 'carVariant.carModel.carBrand']);

        return view('user.service-appointments.show', compact('appointment'));
    }

    public function edit(ServiceAppointment $appointment)
    {
        // Chỉ cho phép sửa khi là lịch của user và đang ở trạng thái 'scheduled'
        if ($appointment->user_id !== Auth::id() || $appointment->status !== 'scheduled') {
            abort(403);
        }

        $showrooms = Showroom::where('is_active', true)->get();
        $services = Service::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $carVariants = CarVariant::with(['carModel.carBrand'])->get();

        return view('user.service-appointments.edit', compact('appointment', 'showrooms', 'services', 'carVariants'));
    }

    public function update(Request $request, ServiceAppointment $appointment)
    {
        // chỉ cho phép sửa khi khách vẫn còn "Đã lên lịch"
        if ($appointment->user_id !== Auth::id() || $appointment->status !== 'scheduled') {
            abort(403);
        }

        // Normalize time before validation
        $normalized = $this->normalizeTimeToHHMM($request->input('appointment_time'));
        if ($normalized) { $request->merge(['appointment_time' => $normalized]); }

        // Use same validation as store method - allow editing existing appointments
        $validationRules = [
            // 1. Showroom
            ['showroom_id', 'required|exists:showrooms,id', 'Vui lòng chọn showroom.'],
            
            // 2. Appointment Date (allow current date for existing appointments)
            ['appointment_date', 'required|date|after_or_equal:today', 'Vui lòng chọn ngày hẹn hợp lệ.'],
            
            // 3. Appointment Time  
            ['appointment_time', 'required|string', 'Vui lòng chọn giờ hẹn.'],
            
            // 4. Car Variant
            ['car_variant_id', 'required|exists:car_variants,id', 'Vui lòng chọn xe.'],
            
            // 5. Service ID
            ['service_id', 'required|exists:services,id', 'Vui lòng chọn dịch vụ.'],
            
            // 6. Requested Services
            ['requested_services', 'nullable|string|max:65535', 'Yêu cầu thêm quá dài.'],
        ];
        
        $validated = [];
        
        // Validate từng field theo thứ tự
        foreach ($validationRules as [$field, $rules, $message]) {
            try {
                $fieldValidation = $request->validate([
                    $field => $rules
                ]);
                $validated = array_merge($validated, $fieldValidation);
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'errors' => [$field => [$message]],
                    ], 422);
                }
                return back()->withErrors([$field => $message])->withInput();
            }
        }
        
        // Custom validation cho appointment_date (weekend check)
        if (isset($validated['appointment_date'])) {
            $date = \Carbon\Carbon::parse($validated['appointment_date']);
            if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
                $message = 'Không thể đặt lịch vào cuối tuần. Vui lòng chọn thứ 2 - thứ 6.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'errors' => ['appointment_date' => [$message]],
                    ], 422);
                }
                return back()->withErrors(['appointment_date' => $message])->withInput();
            }
        }
        
        // Custom validation cho appointment_time (business hours)
        if (isset($validated['appointment_time'])) {
            $time = $validated['appointment_time'];
            if (!preg_match('/^([01]?\d|2[0-3]):[0-5]\d$/', $time)) {
                $message = 'Giờ hẹn không hợp lệ (định dạng HH:MM).';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'errors' => ['appointment_time' => [$message]],
                    ], 422);
                }
                return back()->withErrors(['appointment_time' => $message])->withInput();
            }
            
            $hour = (int) substr($time, 0, 2);
            if ($hour < 9 || $hour >= 17) {
                $message = 'Giờ hẹn phải trong khung giờ làm việc (09:00 - 16:59).';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'errors' => ['appointment_time' => [$message]],
                    ], 422);
                }
                return back()->withErrors(['appointment_time' => $message])->withInput();
            }
        }
        
        // Validate additional fields (không dừng khi có lỗi)
        $additionalValidation = $request->validate([
            'vehicle_registration' => 'nullable|string|max:32',
            'current_mileage' => 'nullable|integer|min:0|max:4294967295', 
            'estimated_cost' => 'nullable|numeric|min:0|max:9999999999999.99',
            'is_warranty_work' => 'nullable|boolean',
            'service_description' => 'nullable|string|max:65535',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);
        
        $validated = array_merge($validated, $additionalValidation);

        // Check showroom availability
        $showroom = \App\Models\Showroom::find($validated['showroom_id']);
        if (!$showroom || !$showroom->is_active) {
            $message = 'Showroom không khả dụng';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => ['showroom_id' => [$message]],
                ], 422);
            }
            return back()->withErrors(['showroom_id' => $message])->withInput();
        }

        // Check service availability
        $service = \App\Models\Service::find($validated['service_id']);
        if (!$service || !$service->is_active) {
            $message = 'Dịch vụ không khả dụng';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => ['service_id' => [$message]],
                ], 422);
            }
            return back()->withErrors(['service_id' => $message])->withInput();
        }

        if (isset($validated['appointment_time'])) {
            $validated['appointment_time'] = $validated['appointment_time'] . ':00';
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
        // Check ownership and allowed statuses
        if ($appointment->user_id !== Auth::id() || !in_array($appointment->status, ['scheduled', 'confirmed'])) {
            return request()->expectsJson() || request()->ajax()
                ? response()->json(['success' => false, 'message' => 'Không thể hủy lịch ở trạng thái hiện tại.'], 422)
                : abort(403);
        }

        $appointment->update(['status' => 'cancelled']);

        try {
            app(NotificationService::class)->send(
                $appointment->user_id,
                'service_appointment',
                'Đã hủy lịch bảo dưỡng',
                'Bạn đã hủy lịch bảo dưỡng #' . $appointment->id . '.'
            );
        } catch (\Throwable $e) {}

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã hủy lịch bảo dưỡng thành công!'
            ]);
        }
        return redirect()->route('user.service-appointments.index')->with('success', 'Đã hủy lịch bảo dưỡng thành công!');
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

        $normalizedTime = preg_match('/^\d{2}:\d{2}:\d{2}$/', (string) $request->appointment_time)
            ? $request->appointment_time
            : ($request->appointment_time . ':00');

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $normalizedTime,
            'status' => 'scheduled',
        ]);

        // Notify user about reschedule
        try {
            app(NotificationService::class)->send(
                $appointment->user_id,
                'service_appointment',
                'Đã đổi lịch bảo dưỡng',
                'Lịch bảo dưỡng #' . $appointment->id . ' đã được cập nhật.'
            );
        } catch (\Throwable $e) {}

        return redirect()->route('user.service-appointments.show', $appointment->id)
            ->with('success', 'Đã yêu cầu đổi lịch thành công! Chúng tôi sẽ xác nhận trong thời gian sớm nhất.');
    }

    public function rate(Request $request, ServiceAppointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }
        if ($appointment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể đánh giá khi lịch đã hoàn thành.',
            ], 422);
        }

        $data = $request->validate([
            'satisfaction_rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:65535', // Match DB text field
        ], [
            'satisfaction_rating.required' => 'Vui lòng chọn số sao.',
            'satisfaction_rating.integer' => 'Số sao phải là số nguyên.',
            'satisfaction_rating.min' => 'Số sao tối thiểu là 1.',
            'satisfaction_rating.max' => 'Số sao tối đa là 5.',
            'feedback.max' => 'Phản hồi không được quá 65535 ký tự.',
        ]);

        $appointment->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu đánh giá dịch vụ. Cảm ơn bạn!'
        ]);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'showroom_id' => 'required|exists:showrooms,id',
            'date' => [
                'required','date','after:today',
                function($attr,$value,$fail){
                    $date = \Carbon\Carbon::parse($value);
                    // Check if it's weekend
                    if ($date->dayOfWeek === 0 || $date->dayOfWeek === 6) {
                        $fail('Không thể đặt lịch vào cuối tuần. Vui lòng chọn thứ 2 - thứ 6.');
                    }
                }
            ],
        ]);

        $date = Carbon::parse($request->date);
        $showroom = Showroom::find($request->showroom_id);

        // Get existing appointments for this date and showroom
        $existingAppointments = ServiceAppointment::where('showroom_id', $request->showroom_id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->get();

        // Show existing appointments for this date (since users can pick any time)
        $appointmentTimes = $existingAppointments->pluck('appointment_time')->map(function($time) {
            return substr($time, 0, 5); // Remove seconds (HH:MM:SS -> HH:MM)
        })->sort()->values();

        $availableSlots = [];
        
        // Show summary info instead of predefined slots
        $totalAppointments = $existingAppointments->count();
                $availableSlots[] = [
            'info' => 'Giờ làm việc: 09:00 - 16:59',
            'total_appointments' => $totalAppointments,
            'existing_times' => $appointmentTimes->toArray()
        ];

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
