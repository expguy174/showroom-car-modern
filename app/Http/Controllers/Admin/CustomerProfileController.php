<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerProfile;
use App\Models\Showroom;
use App\Models\User;

class CustomerProfileController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomerProfile::with(['user', 'preferredShowroom', 'customerPoint']);

        // Filter by showroom
        if ($request->has('showroom_id') && $request->showroom_id) {
            $query->where('preferred_showroom_id', $request->showroom_id);
        }

        // Filter by customer type
        if ($request->has('customer_type') && $request->customer_type) {
            $query->where('customer_type', $request->customer_type);
        }

        // Filter by VIP status
        if ($request->has('is_vip') && $request->is_vip !== '') {
            $query->where('is_vip', $request->is_vip);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('driver_license_number', 'like', '%' . $search . '%');
            });
        }

        $customerProfiles = $query->orderBy('created_at', 'desc')->paginate(15);
        $showrooms = Showroom::all();
        $customerTypes = ['new', 'returning', 'vip', 'prospect'];

        return view('admin.customer-profiles.index', compact('customerProfiles', 'showrooms', 'customerTypes'));
    }

    public function show(CustomerProfile $customerProfile)
    {
        $customerProfile->load(['user', 'preferredShowroom', 'customerPoint']);
        
        return view('admin.customer-profiles.show', compact('customerProfile'));
    }

    public function edit(CustomerProfile $customerProfile)
    {
        $showrooms = Showroom::all();
        $users = User::all();
        
        return view('admin.customer-profiles.edit', compact('customerProfile', 'showrooms', 'users'));
    }

    public function update(Request $request, CustomerProfile $customerProfile)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'occupation' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:200',
            'monthly_income' => 'nullable|numeric|min:0',
            'driver_license_number' => 'nullable|string|max:50',
            'driver_license_issue_date' => 'nullable|date',
            'driver_license_expiry_date' => 'nullable|date|after:today',
            'driving_experience_years' => 'nullable|integer|min:0|max:50',
            'preferred_car_types' => 'nullable|array',
            'preferred_brands' => 'nullable|array',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'purchase_purpose' => 'nullable|string|max:200',
            'preferred_showroom_id' => 'nullable|exists:showrooms,id',
            'customer_type' => 'required|in:new,returning,vip,prospect',
            'is_vip' => 'boolean',
            'consent_to_marketing' => 'boolean',
            'consent_to_sms' => 'boolean',
            'consent_to_email' => 'boolean',
        ]);

        $data = $request->all();
        $data['preferred_car_types'] = $request->preferred_car_types ? json_encode($request->preferred_car_types) : null;
        $data['preferred_brands'] = $request->preferred_brands ? json_encode($request->preferred_brands) : null;
        $data['is_vip'] = $request->has('is_vip');
        $data['consent_to_marketing'] = $request->has('consent_to_marketing');
        $data['consent_to_sms'] = $request->has('consent_to_sms');
        $data['consent_to_email'] = $request->has('consent_to_email');

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

    public function export(Request $request)
    {
        $query = CustomerProfile::with(['user', 'preferredShowroom']);

        // Apply filters
        if ($request->has('showroom_id') && $request->showroom_id) {
            $query->where('preferred_showroom_id', $request->showroom_id);
        }

        if ($request->has('customer_type') && $request->customer_type) {
            $query->where('customer_type', $request->customer_type);
        }

        $customerProfiles = $query->get();

        $filename = 'customer_profiles_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($customerProfiles) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Họ tên', 'Số điện thoại', 'Email', 'Ngày sinh', 'Giới tính',
                'Địa chỉ', 'Thành phố', 'Nghề nghiệp', 'Công ty', 'Thu nhập',
                'Số bằng lái', 'Kinh nghiệm lái', 'Loại khách hàng', 'VIP',
                'Showroom ưa thích', 'Ngày tạo'
            ]);

            foreach ($customerProfiles as $profile) {
                fputcsv($file, [
                    $profile->id,
                    $profile->full_name,
                    $profile->phone,
                    $profile->email,
                    $profile->birth_date ? $profile->birth_date->format('d/m/Y') : 'N/A',
                    ucfirst($profile->gender),
                    $profile->address,
                    $profile->city,
                    $profile->occupation ?? 'N/A',
                    $profile->company_name ?? 'N/A',
                    $profile->monthly_income ? number_format($profile->monthly_income) : 'N/A',
                    $profile->driver_license_number ?? 'N/A',
                    $profile->driving_experience_years ? $profile->driving_experience_years . ' năm' : 'N/A',
                    ucfirst($profile->customer_type),
                    $profile->is_vip ? 'Có' : 'Không',
                    $profile->preferredShowroom->name ?? 'N/A',
                    $profile->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
