<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceAppointment;
use App\Models\Showroom;
use App\Models\CarBrand;
use App\Models\User;
use Carbon\Carbon;

class ServiceAppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceAppointment::with(['user', 'showroom', 'carVariant.carModel.carBrand']);

        // Filter by showroom
        if ($request->has('showroom_id') && $request->showroom_id) {
            $query->where('showroom_id', $request->showroom_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by appointment type
        if ($request->has('appointment_type') && $request->appointment_type) {
            $query->where('appointment_type', $request->appointment_type);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('appointment_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%')
                  ->orWhere('vehicle_license_plate', 'like', '%' . $search . '%');
            });
        }

        $appointments = $query->orderBy('appointment_date', 'asc')->paginate(15);
        $showrooms = Showroom::all();
        $statuses = \App\Models\ServiceAppointment::STATUSES;
        $serviceTypes = \App\Models\ServiceAppointment::APPOINTMENT_TYPES;

        return view('admin.service-appointments.index', compact('appointments', 'showrooms', 'statuses', 'serviceTypes'));
    }

    public function show(ServiceAppointment $appointment)
    {
        $appointment->load(['user', 'showroom', 'carVariant.carModel.carBrand']);
        
        return view('admin.service-appointments.show', compact('appointment'));
    }

    public function edit(ServiceAppointment $appointment)
    {
        $showrooms = Showroom::all();
        $users = User::all();
        $statuses = \App\Models\ServiceAppointment::STATUSES;
        
        return view('admin.service-appointments.edit', compact('appointment', 'showrooms', 'users', 'statuses'));
    }

    public function update(Request $request, ServiceAppointment $appointment)
    {
        $request->validate([
            'showroom_id' => 'required|exists:showrooms,id',
            'car_variant_id' => 'required|exists:car_variants,id',
            'appointment_type' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::APPOINTMENT_TYPES),
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:255',
            'current_mileage' => 'nullable|numeric|min:0',
            'service_description' => 'nullable|string|max:1000',
            'priority' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::PRIORITIES),
            'special_instructions' => 'nullable|string|max:500',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:' . implode(',', \App\Models\ServiceAppointment::PAYMENT_METHODS),
            'status' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::STATUSES),
            'technician_notes' => 'nullable|string|max:1000',
        ]);

        $data = $request->only([
            'showroom_id',
            'car_variant_id',
            'appointment_type',
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
            'payment_method',
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

        $appointment->update($data);

        return redirect()->route('admin.service-appointments.index')
            ->with('success', 'Cập nhật lịch bảo dưỡng thành công!');
    }

    public function destroy(ServiceAppointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.service-appointments.index')
            ->with('success', 'Đã xóa lịch bảo dưỡng thành công!');
    }

    public function updateStatus(Request $request, ServiceAppointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', \App\Models\ServiceAppointment::STATUSES),
            'notes' => 'nullable|string|max:500',
        ]);

        $data = ['status' => $request->status];

        // Add completion date if status is completed
        if ($request->status === 'completed') {
            $data['completed_at'] = now();
        }

        // Add cancellation date if status is cancelled
        if ($request->status === 'cancelled') {
            $data['cancelled_at'] = now();
            $data['cancellation_reason'] = $request->notes ?? 'Cancelled by admin';
        }

        // Add technician notes if provided
        if ($request->notes && $request->status !== 'cancelled') {
            $data['technician_notes'] = $request->notes;
        }

        $appointment->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công!',
            'status' => $request->status
        ]);
    }

    public function calendar()
    {
        $appointments = ServiceAppointment::with(['user', 'showroom', 'carVariant.carModel.carBrand'])
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
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
            'total_pending' => ServiceAppointment::where('status', 'pending')->count(),
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

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'pending':
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
