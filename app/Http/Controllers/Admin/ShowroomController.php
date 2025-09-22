<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use Illuminate\Http\Request;

class ShowroomController extends Controller
{
    public function index(Request $request)
    {
        $query = Showroom::query();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by city
        if ($request->has('city') && $request->city !== '') {
            $query->where('city', $request->city);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $showrooms = $query->orderBy('name')->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => Showroom::count(),
            'active' => Showroom::where('is_active', true)->count(),
            'inactive' => Showroom::where('is_active', false)->count(),
        ];

        // Get cities for filter
        $cities = Showroom::distinct()->pluck('city')->filter()->sort();

        return view('admin.showrooms.index', compact('showrooms', 'stats', 'cities'));
    }

    public function show(Showroom $showroom)
    {
        $showroom->load(['serviceAppointments' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.showrooms.show', compact('showroom'));
    }

    public function create()
    {
        return view('admin.showrooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'opening_hours' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Showroom::create($request->all());

        return redirect()->route('admin.showrooms.index')
            ->with('success', 'Showroom đã được tạo thành công.');
    }

    public function edit(Showroom $showroom)
    {
        return view('admin.showrooms.edit', compact('showroom'));
    }

    public function update(Request $request, Showroom $showroom)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'opening_hours' => 'nullable|string',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $showroom->update($request->all());

        return redirect()->route('admin.showrooms.index')
            ->with('success', 'Showroom đã được cập nhật thành công.');
    }

    public function destroy(Showroom $showroom)
    {
        // Check if showroom has appointments
        if ($showroom->serviceAppointments()->count() > 0) {
            return redirect()->route('admin.showrooms.index')
                ->with('error', 'Không thể xóa showroom này vì đã có lịch hẹn liên quan.');
        }

        $showroom->delete();

        return redirect()->route('admin.showrooms.index')
            ->with('success', 'Showroom đã được xóa thành công.');
    }
}
