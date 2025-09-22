<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dealership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DealershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Dealership::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $dealerships = $query->orderBy('created_at', 'desc')->paginate(15);

        // Stats for cards
        $stats = [
            'total' => Dealership::count(),
            'active' => Dealership::where('is_active', true)->count(),
            'inactive' => Dealership::where('is_active', false)->count(),
            'featured' => Dealership::where('is_featured', true)->count(),
        ];

        return view('admin.dealerships.index', compact('dealerships', 'stats'));
    }

    public function create()
    {
        return view('admin.dealerships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:dealerships,code',
            'type' => 'required|in:authorized,independent,franchise',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'business_license' => 'nullable|string|max:100',
            'tax_code' => 'nullable|string|max:50',
            'established_date' => 'nullable|date',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:20',
            'owner_email' => 'required|email|max:255',
            'provides_sales' => 'boolean',
            'provides_service' => 'boolean',
            'provides_parts' => 'boolean',
            'provides_finance' => 'boolean',
            'provides_insurance' => 'boolean',
            'opening_time' => 'nullable|string|max:10',
            'closing_time' => 'nullable|string|max:10',
            'working_days' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:pending,approved,suspended,rejected',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255'
        ]);

        // Handle file uploads
        if ($request->hasFile('logo_path')) {
            $validated['logo_path'] = $request->file('logo_path')->store('dealerships/logos', 'public');
        }

        if ($request->hasFile('banner_path')) {
            $validated['banner_path'] = $request->file('banner_path')->store('dealerships/banners', 'public');
        }

        Dealership::create($validated);

        return redirect()->route('admin.dealerships.index')
            ->with('success', 'Đại lý đã được tạo thành công!');
    }

    public function show(Dealership $dealership)
    {
        $dealership->load('showrooms');
        return view('admin.dealerships.show', compact('dealership'));
    }

    public function edit(Dealership $dealership)
    {
        return view('admin.dealerships.edit', compact('dealership'));
    }

    public function update(Request $request, Dealership $dealership)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:dealerships,code,' . $dealership->id,
            'type' => 'required|in:authorized,independent,franchise',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'business_license' => 'nullable|string|max:100',
            'tax_code' => 'nullable|string|max:50',
            'established_date' => 'nullable|date',
            'owner_name' => 'required|string|max:255',
            'owner_phone' => 'required|string|max:20',
            'owner_email' => 'required|email|max:255',
            'provides_sales' => 'boolean',
            'provides_service' => 'boolean',
            'provides_parts' => 'boolean',
            'provides_finance' => 'boolean',
            'provides_insurance' => 'boolean',
            'opening_time' => 'nullable|string|max:10',
            'closing_time' => 'nullable|string|max:10',
            'working_days' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:pending,approved,suspended,rejected',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255'
        ]);

        // Handle file uploads
        if ($request->hasFile('logo_path')) {
            // Delete old logo
            if ($dealership->logo_path) {
                Storage::disk('public')->delete($dealership->logo_path);
            }
            $validated['logo_path'] = $request->file('logo_path')->store('dealerships/logos', 'public');
        }

        if ($request->hasFile('banner_path')) {
            // Delete old banner
            if ($dealership->banner_path) {
                Storage::disk('public')->delete($dealership->banner_path);
            }
            $validated['banner_path'] = $request->file('banner_path')->store('dealerships/banners', 'public');
        }

        $dealership->update($validated);

        return redirect()->route('admin.dealerships.index')
            ->with('success', 'Đại lý đã được cập nhật thành công!');
    }

    public function destroy(Dealership $dealership)
    {
        // Delete associated files
        if ($dealership->logo_path) {
            Storage::disk('public')->delete($dealership->logo_path);
        }
        if ($dealership->banner_path) {
            Storage::disk('public')->delete($dealership->banner_path);
        }

        $dealership->delete();

        return redirect()->route('admin.dealerships.index')
            ->with('success', 'Đại lý đã được xóa thành công!');
    }
}
