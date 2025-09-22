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

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by category
        if ($request->has('category') && $request->category !== '') {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $services = $query->orderBy('sort_order')->orderBy('name')->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => Service::count(),
            'active' => Service::where('is_active', true)->count(),
            'inactive' => Service::where('is_active', false)->count(),
            'maintenance' => Service::where('category', 'maintenance')->count(),
            'repair' => Service::where('category', 'repair')->count(),
        ];

        // Get categories for filter
        $categories = Service::distinct()->pluck('category')->filter();

        return view('admin.services.index', compact('services', 'stats', 'categories'));
    }

    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:services,code',
            'description' => 'nullable|string',
            'category' => 'required|in:maintenance,repair,cosmetic,diagnostic',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'requirements' => 'nullable|string',
            'notes' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')
            ->with('success', 'Dịch vụ đã được tạo thành công.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:services,code,' . $service->id,
            'description' => 'nullable|string',
            'category' => 'required|in:maintenance,repair,cosmetic,diagnostic',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'requirements' => 'nullable|string',
            'notes' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')
            ->with('success', 'Dịch vụ đã được cập nhật thành công.');
    }

    public function destroy(Service $service)
    {
        // Check if service has appointments
        if ($service->serviceAppointments()->count() > 0) {
            return redirect()->route('admin.services.index')
                ->with('error', 'Không thể xóa dịch vụ này vì đã có lịch hẹn liên quan.');
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Dịch vụ đã được xóa thành công.');
    }
}
