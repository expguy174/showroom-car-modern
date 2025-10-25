<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceAppointment;
use App\Models\Showroom;
use App\Models\CarBrand;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

class ServiceAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceAppointment::with(['user.userProfile', 'showroom', 'service']);

        // Filter by showroom
        if ($request->has('showroom_id') && $request->showroom_id) {
            $query->where('showroom_id', $request->showroom_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by service
        if ($request->has('service_id') && $request->service_id) {
            $query->where('service_id', $request->service_id);
        }

        // Search - in appointment fields, user email, and userProfile name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('appointment_number', 'like', '%' . $search . '%')
                  ->orWhere('vehicle_registration', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%')
                               ->orWhereHas('userProfile', function($profileQuery) use ($search) {
                                   $profileQuery->where('name', 'like', '%' . $search . '%');
                               });
                  });
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(15);
        
        // Append query parameters to pagination links (exclude ajax and with_stats)
        $appointments->appends($request->except(['page', 'ajax', 'with_stats']));
        
        // Calculate stats - use actual statuses from database (scheduled, not pending)
        $totalAppointments = ServiceAppointment::count();
        $pendingAppointments = ServiceAppointment::where('status', 'scheduled')->count();
        $confirmedAppointments = ServiceAppointment::where('status', 'confirmed')->count();
        $inProgressAppointments = ServiceAppointment::where('status', 'in_progress')->count();
        $completedAppointments = ServiceAppointment::where('status', 'completed')->count();
        
        $services = Service::where('is_active', true)->orderBy('name')->get();
        
        // Handle AJAX requests
        if ($request->ajax()) {
            // Stats only request
            if ($request->has('stats_only')) {
                return response()->json([
                    'total' => $totalAppointments,
                    'pending' => $pendingAppointments,
                    'confirmed' => $confirmedAppointments,
                    'in_progress' => $inProgressAppointments,
                    'completed' => $completedAppointments
                ]);
            }
            
            return view('admin.service-appointments.partials.table', compact('appointments'))->render();
        }

        return view('admin.service-appointments.index', compact(
            'appointments', 
            'services',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'inProgressAppointments',
            'completedAppointments'
        ));
    }

    public function show(ServiceAppointment $appointment)
    {
        $appointment->load(['user', 'showroom', 'carVariant.carModel.carBrand']);
        
        return view('admin.service-appointments.show', compact('appointment'));
    }

    public function edit(ServiceAppointment $appointment)
    {
        $appointment->load(['user.userProfile', 'showroom', 'service', 'carVariant.carModel.carBrand']);
        $showrooms = Showroom::where('is_active', true)->get();
        $services = Service::where('is_active', true)->get();
        $users = User::with('userProfile')->get();
        $statuses = ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        
        return view('admin.service-appointments.edit', compact('appointment', 'showrooms', 'services', 'users', 'statuses', 'priorities'));
    }

    public function update(Request $request, ServiceAppointment $appointment)
    {
        $request->validate([
            'showroom_id' => 'required|exists:showrooms,id',
            'service_id' => 'required|exists:services,id',
            'car_variant_id' => 'required|exists:car_variants,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'current_mileage' => 'nullable|integer|min:0',
            'service_description' => 'nullable|string|max:65535',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'special_instructions' => 'nullable|string|max:65535',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled',
            'technician_notes' => 'nullable|string|max:65535',
        ]);

        $data = $request->only([
            'showroom_id',
            'service_id',
            'car_variant_id',
            'appointment_date',
            'appointment_time',
            'customer_name',
            'customer_phone',
            'customer_email',
            'current_mileage',
            'service_description',
            'priority',
            'special_instructions',
            'estimated_cost',
            'actual_cost',
            'status',
            'technician_notes',
        ]);

        // Chuẩn hoá giờ TIME HH:MM:SS
        if (!empty($data['appointment_time'])) {
            $data['appointment_time'] = preg_match('/^\d{2}:\d{2}:\d{2}$/', (string) $data['appointment_time'])
                ? $data['appointment_time']
                : ($data['appointment_time'] . ':00');
        }
        
        // Update completion date if status is completed
        if ($request->status === 'completed' && $appointment->status !== 'completed') {
            $data['completed_at'] = now();
        }

        // Update cancellation date if status is cancelled
        if ($request->status === 'cancelled' && $appointment->status !== 'cancelled') {
            $data['cancelled_at'] = now();
        }


        return redirect()->route('admin.service-appointments.index')
            ->with('success', 'Cập nhật lịch bảo dưỡng thành công!');
    }


    public function updateStatus(Request $request, ServiceAppointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::STATUSES),
            'notes' => 'nullable|string|max:500',
        ]);

        $newStatus = $request->status;
        
        // Validate status transitions (Simple workflow)
        $validTransitions = [
            'scheduled' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed'],
        ];

        if (isset($validTransitions[$appointment->status])) {
            if (!in_array($newStatus, $validTransitions[$appointment->status])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể chuyển từ trạng thái hiện tại sang trạng thái này'
                ], 400);
            }
        }

        $data = ['status' => $newStatus];

        // Add status timestamps
        if ($newStatus === 'confirmed') {
            $data['confirmed_at'] = now();
        }
        
        if ($newStatus === 'in_progress') {
            $data['in_progress_at'] = now();
        }
        
        if ($newStatus === 'completed') {
            $data['completed_at'] = now();
        }

        if ($newStatus === 'cancelled') {
            $data['cancelled_at'] = now();
            $data['cancellation_reason'] = $request->notes ?? 'Cancelled by admin';
        }

        // Add technician notes if provided
        if ($request->notes && $newStatus !== 'cancelled') {
            $data['technician_notes'] = $request->notes;
        }

        $appointment->update($data);

        $messages = [
            'in_progress' => 'Đã bắt đầu thực hiện dịch vụ!',
            'completed' => 'Đã hoàn thành dịch vụ!'
        ];

        return response()->json([
            'success' => true,
            'message' => $messages[$newStatus] ?? 'Cập nhật trạng thái thành công!',
            'status' => $request->status
        ]);
    }

    public function calendar()
    {
        $appointments = ServiceAppointment::with(['user', 'showroom', 'carVariant.carModel.carBrand'])
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->get();

        $calendarData = [];
        foreach ($appointments as $appointment) {
            $calendarData[] = [
                'id' => $appointment->id,
                'title' => $appointment->customer_name . ' - ' . $appointment->carVariant->carModel->carBrand->name,
                'start' => $appointment->appointment_date . 'T' . $appointment->appointment_time,
                'end' => $appointment->appointment_date . 'T' . $appointment->appointment_time,
                'backgroundColor' => $this->getStatusColor($appointment->status),
                'borderColor' => $this->getStatusColor($appointment->status),
                'extendedProps' => [
                    'appointment_number' => $appointment->appointment_number,
                    'service_type' => $appointment->appointment_type,
                    'showroom' => $appointment->showroom->name,
                    'status' => $appointment->status,
                ]
            ];
        }

        return view('admin.service-appointments.calendar', compact('calendarData'));
    }

    public function dashboard()
    {
        // Today's appointments
        $todayAppointments = ServiceAppointment::whereDate('appointment_date', today())
            ->with(['user', 'showroom'])
            ->get();

        // This week's appointments
        $weekAppointments = ServiceAppointment::whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->with(['user', 'showroom'])
            ->get();

        // Statistics
        $stats = [
            'total_pending' => ServiceAppointment::where('status', 'scheduled')->count(),
            'total_confirmed' => ServiceAppointment::where('status', 'confirmed')->count(),
            'total_in_progress' => ServiceAppointment::where('status', 'in_progress')->count(),
            'total_completed' => ServiceAppointment::where('status', 'completed')->count(),
            'today_count' => $todayAppointments->count(),
            'week_count' => $weekAppointments->count(),
        ];

        // Recent appointments
        $recentAppointments = ServiceAppointment::with(['user', 'showroom', 'carVariant.carModel.carBrand'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.service-appointments.dashboard', compact('todayAppointments', 'weekAppointments', 'stats', 'recentAppointments'));
    }

    public function export(Request $request)
    {
        $query = ServiceAppointment::with(['user', 'showroom', 'carVariant.carModel.carBrand']);

        // Apply filters
        if ($request->has('showroom_id') && $request->showroom_id) {
            $query->where('showroom_id', $request->showroom_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->get();

        $filename = 'service_appointments_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Mã lịch', 'Khách hàng', 'Số điện thoại', 'Email', 'Loại dịch vụ',
                'Ngày hẹn', 'Giờ hẹn', 'Showroom', 'Xe', 'Biển số', 'Số km',
                'Mức độ ưu tiên', 'Trạng thái', 'Chi phí ước tính', 'Chi phí thực tế',
                'Ngày tạo'
            ]);

            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->appointment_number,
                    $appointment->customer_name,
                    $appointment->customer_phone,
                    $appointment->customer_email,
                    ucfirst($appointment->appointment_type),
                    $appointment->appointment_date->format('d/m/Y'),
                    $appointment->appointment_time,
                    $appointment->showroom->name,
                    $appointment->carVariant->carModel->carBrand->name . ' ' . $appointment->carVariant->carModel->name,
                    $appointment->vehicle_license_plate ?? 'N/A',
                    $appointment->vehicle_mileage ?? 'N/A',
                    ucfirst($appointment->urgency_level),
                    ucfirst($appointment->status),
                    $appointment->estimated_cost ? number_format($appointment->estimated_cost) : 'N/A',
                    $appointment->actual_cost ? number_format($appointment->actual_cost) : 'N/A',
                    $appointment->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function confirm(ServiceAppointment $appointment)
    {
        if ($appointment->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể xác nhận lịch hẹn ở trạng thái đã đặt lịch'
            ], 400);
        }

        $appointment->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Get updated stats
        $totalAppointments = ServiceAppointment::count();
        $pendingAppointments = ServiceAppointment::where('status', 'scheduled')->count();
        $confirmedAppointments = ServiceAppointment::where('status', 'confirmed')->count();
        $inProgressAppointments = ServiceAppointment::where('status', 'in_progress')->count();
        $completedAppointments = ServiceAppointment::where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'message' => 'Đã xác nhận lịch hẹn thành công!',
            'stats' => [
                'total' => $totalAppointments,
                'pending' => $pendingAppointments,
                'confirmed' => $confirmedAppointments,
                'inProgress' => $inProgressAppointments,
                'completed' => $completedAppointments
            ]
        ]);
    }

    public function cancel(ServiceAppointment $appointment)
    {
        if (!in_array($appointment->status, ['scheduled', 'confirmed', 'rescheduled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy lịch hẹn ở trạng thái này'
            ], 400);
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_at' => now()
        ]);

        // Get updated stats
        $totalAppointments = ServiceAppointment::count();
        $pendingAppointments = ServiceAppointment::where('status', 'scheduled')->count();
        $confirmedAppointments = ServiceAppointment::where('status', 'confirmed')->count();
        $inProgressAppointments = ServiceAppointment::where('status', 'in_progress')->count();
        $completedAppointments = ServiceAppointment::where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy lịch hẹn thành công!',
            'stats' => [
                'total' => $totalAppointments,
                'pending' => $pendingAppointments,
                'confirmed' => $confirmedAppointments,
                'inProgress' => $inProgressAppointments,
                'completed' => $completedAppointments
            ]
        ]);
    }

    public function destroy(ServiceAppointment $appointment)
    {
        try {
            $appointment->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa lịch hẹn thành công!'
                ]);
            }

            return redirect()->route('admin.service-appointments.index')
                ->with('success', 'Đã xóa lịch hẹn thành công!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xóa lịch hẹn'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa lịch hẹn');
        }
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'scheduled':
                return '#3B82F6'; // Blue
            case 'confirmed':
                return '#10B981'; // Green
            case 'in_progress':
                return '#F59E0B'; // Yellow
            case 'completed':
                return '#8B5CF6'; // Purple
            case 'cancelled':
                return '#EF4444'; // Red
            default:
                return '#6B7280'; // Gray
        }
    }
}
