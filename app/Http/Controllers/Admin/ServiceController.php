<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }

        $services = $query->withCount('serviceAppointments')->orderBy('sort_order')->orderBy('name')->paginate(15);
        
        // Append query parameters to pagination links
        $services->appends($request->except(['page', 'ajax', 'with_stats']));
        
        // Get statistics
        $totalServices = Service::count();
        $activeServices = Service::where('is_active', true)->count();
        $inactiveServices = Service::where('is_active', false)->count();

        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.services.partials.table', compact('services'))->render();
        }

        return view('admin.services.index', compact(
            'services',
            'totalServices',
            'activeServices',
            'inactiveServices'
        ));
    }

    public function show(Service $service)
    {
        $service->loadCount('serviceAppointments');
        return view('admin.services.show', compact('service'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:services,code',
            'description' => 'nullable|string',
            'category' => 'required|in:maintenance,repair,cosmetic,diagnostic,emergency',
            'duration_minutes' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'requirements' => 'nullable|string',
            'notes' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Handle checkboxes separately
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

        Service::create($validated);

        // Return JSON for AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Dịch vụ đã được tạo thành công.',
                'redirect' => route('admin.services.index')
            ]);
        }

        return redirect()->route('admin.services.create')
            ->with('success', 'Dịch vụ đã được tạo thành công.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:services,code,' . $service->id,
            'description' => 'nullable|string',
            'category' => 'required|in:maintenance,repair,cosmetic,diagnostic,emergency',
            'duration_minutes' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'requirements' => 'nullable|string',
            'notes' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Handle checkboxes separately
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

        $service->update($validated);

        // Return JSON for AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Dịch vụ đã được cập nhật thành công.',
                'redirect' => route('admin.services.index')
            ]);
        }

        return redirect()->route('admin.services.edit', $service)
            ->with('success', 'Dịch vụ đã được cập nhật thành công.');
    }

    public function destroy(Service $service, Request $request)
    {
        try {
            // Check if service is currently active
            if ($service->is_active) {
                $message = "Không thể xóa dịch vụ \"{$service->name}\" vì đang ở trạng thái HOẠT ĐỘNG. Vui lòng tắt dịch vụ trước khi xóa.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate'
                    ], 400);
                }
                return redirect()->route('admin.services.index')->with('error', $message);
            }

            // Check if service has been used in appointments
            $appointmentCount = $service->serviceAppointments()->count();

            if ($appointmentCount > 0) {
                $message = "KHÔNG THỂ XÓA dịch vụ \"{$service->name}\" vì đã có " .
                          "({$appointmentCount} lịch hẹn). " .
                          "Đây là dữ liệu kinh doanh quan trọng! Bạn chỉ có thể 'Tắt' để ngừng sử dụng nhưng vẫn giữ lịch sử.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'action_suggestion' => 'deactivate',
                        'business_data' => [
                            'appointment_count' => $appointmentCount
                        ]
                    ], 400);
                }
                return redirect()->route('admin.services.index')->with('error', $message);
            }

            // Safe to delete - no business data
            $serviceName = $service->name;
            $serviceCode = $service->code;
            
            $service->delete();

            $message = "Đã xóa dịch vụ \"{$serviceName}\" ({$serviceCode}) thành công!";
            
            if ($request->ajax() || $request->wantsJson()) {
                // Get updated stats after deletion
                $stats = [
                    'total' => Service::count(),
                    'active' => Service::where('is_active', true)->count(),
                    'inactive' => Service::where('is_active', false)->count()
                ];
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'stats' => $stats
                ]);
            }
            
            return redirect()->route('admin.services.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            $message = 'Có lỗi xảy ra khi xóa dịch vụ: ' . $e->getMessage();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            
            return redirect()->route('admin.services.index')
                ->with('error', $message);
        }
    }

    public function toggleStatus(Request $request, Service $service)
    {
        $service->is_active = !$service->is_active;
        $service->save();

        // Get updated stats
        $stats = [
            'total' => Service::count(),
            'active' => Service::where('is_active', true)->count(),
            'inactive' => Service::where('is_active', false)->count()
        ];

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $service->is_active 
                    ? 'Dịch vụ đã được kích hoạt!' 
                    : 'Dịch vụ đã bị tạm dừng!',
                'is_active' => $service->is_active,
                'stats' => $stats
            ]);
        }

        return redirect()->route('admin.services.index')
            ->with('success', $service->is_active 
                ? 'Dịch vụ đã được kích hoạt!' 
                : 'Dịch vụ đã bị tạm dừng!');
    }
}
