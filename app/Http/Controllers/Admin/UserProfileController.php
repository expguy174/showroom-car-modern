<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfile as CustomerProfile;
use App\Models\User;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerProfile::with(['user']);

        if ($request->has('customer_type') && $request->customer_type) {
            $query->where('customer_type', $request->customer_type);
        }

        if ($request->has('is_vip') && $request->is_vip !== '') {
            $query->where('is_vip', $request->is_vip);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('driver_license_number', 'like', '%' . $search . '%')
                  ->orWhere('purchase_purpose', 'like', '%' . $search . '%');
            });
        }

        $customerProfiles = $query->orderBy('created_at', 'desc')->paginate(15);
        $customerTypes = ['new', 'returning', 'vip', 'prospect'];

        return view('admin.customer-profiles.index', compact('customerProfiles', 'customerTypes'));
    }

    public function show(CustomerProfile $customerProfile)
    {
        $customerProfile->load(['user']);
        return view('admin.customer-profiles.show', compact('customerProfile'));
    }

    public function edit(CustomerProfile $customerProfile)
    {
        $users = User::all();
        return view('admin.customer-profiles.edit', compact('customerProfile', 'users'));
    }

    public function update(Request $request, CustomerProfile $customerProfile)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'driver_license_number' => 'nullable|string|max:50',
            'driver_license_issue_date' => 'nullable|date',
            'driver_license_expiry_date' => 'nullable|date|after:today',
            'driving_experience_years' => 'nullable|integer|min:0|max:50',
            'preferred_car_types' => 'nullable|array',
            'preferred_brands' => 'nullable|array',
            'preferred_colors' => 'nullable|array',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'purchase_purpose' => 'nullable|string|max:200',
            'customer_type' => 'required|in:new,returning,vip,prospect',
            'is_vip' => 'boolean',
        ]);

        $data = $request->only([
            'name','birth_date','gender','driver_license_number','driver_license_issue_date',
            'driver_license_expiry_date','driver_license_class','driving_experience_years',
            'budget_min','budget_max','purchase_purpose','customer_type','is_vip','employee_salary','employee_skills'
        ]);
        $data['preferred_car_types'] = $request->preferred_car_types ? json_encode($request->preferred_car_types) : null;
        $data['preferred_brands'] = $request->preferred_brands ? json_encode($request->preferred_brands) : null;
        $data['preferred_colors'] = $request->preferred_colors ? json_encode($request->preferred_colors) : null;
        $data['is_vip'] = $request->has('is_vip');

        $customerProfile->update($data);

        return redirect()->route('admin.customer-profiles.index')
            ->with('success', 'Hồ sơ khách hàng đã được cập nhật thành công!');
    }

    public function destroy(CustomerProfile $customerProfile)
    {
        $customerProfile->delete();
        return redirect()->route('admin.customer-profiles.index')
            ->with('success', 'Hồ sơ khách hàng đã được xóa thành công!');
    }

    public function toggleVip(CustomerProfile $customerProfile)
    {
        $customerProfile->update(['is_vip' => !$customerProfile->is_vip]);
        return response()->json([
            'success' => true,
            'message' => 'Trạng thái VIP đã được cập nhật thành công!',
            'is_vip' => $customerProfile->is_vip
        ]);
    }
}


