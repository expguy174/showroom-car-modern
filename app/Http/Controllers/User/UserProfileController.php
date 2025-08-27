<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfile as CustomerProfile;
use App\Models\Order;
use App\Models\TestDrive;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function index()
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())
            ->with(['user'])
            ->first();

        if (!$customerProfile) {
            return redirect()->route('user.customer-profiles.create');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with(['items.item'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $testDrives = TestDrive::where('user_id', Auth::id())
            ->with(['carVariant.carModel.carBrand'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('user.customer-profiles.index', compact('customerProfile', 'orders', 'testDrives'));
    }

    public function create()
    {
        return view('user.customer-profiles.create');
    }

    public function store(Request $request)
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
        $data['user_id'] = Auth::id();
        $data['preferred_car_types'] = $request->preferred_car_types ? json_encode($request->preferred_car_types) : null;
        $data['preferred_brands'] = $request->preferred_brands ? json_encode($request->preferred_brands) : null;
        $data['preferred_colors'] = $request->preferred_colors ? json_encode($request->preferred_colors) : null;

        $customerProfile = CustomerProfile::create($data);

        return redirect()->route('user.customer-profiles.index')
            ->with('success', 'Hồ sơ khách hàng đã được tạo thành công!');
    }

    public function edit()
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())->firstOrFail();
        return view('user.customer-profiles.edit', compact('customerProfile'));
    }

    public function update(Request $request)
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())->firstOrFail();

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

        $customerProfile->update($data);

        return redirect()->route('user.customer-profiles.index')
            ->with('success', 'Hồ sơ khách hàng đã được cập nhật thành công!');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.item','paymentMethod','paymentTransactions','billingAddress','shippingAddress'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.customer-profiles.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        $order->load(['items.item', 'paymentMethod', 'paymentTransactions', 'billingAddress', 'shippingAddress']);
        return view('user.customer-profiles.show-order', compact('order'));
    }

    public function testDrives()
    {
        $testDrives = TestDrive::where('user_id', Auth::id())
            ->with(['carVariant.carModel.carBrand'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.customer-profiles.test-drives', compact('testDrives'));
    }

    public function preferences()
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())->firstOrFail();
        return view('user.customer-profiles.preferences', compact('customerProfile'));
    }

    public function updatePreferences(Request $request)
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'preferred_car_types' => 'nullable|array',
            'preferred_brands' => 'nullable|array',
            'preferred_colors' => 'nullable|array',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'purchase_purpose' => 'nullable|string|max:200',
        ]);

        $data = [
            'preferred_car_types' => $request->preferred_car_types ? json_encode($request->preferred_car_types) : null,
            'preferred_brands' => $request->preferred_brands ? json_encode($request->preferred_brands) : null,
            'preferred_colors' => $request->preferred_colors ? json_encode($request->preferred_colors) : null,
            'budget_min' => $request->budget_min,
            'budget_max' => $request->budget_max,
            'purchase_purpose' => $request->purchase_purpose,
        ];

        $customerProfile->update($data);

        return redirect()->route('user.customer-profiles.preferences')
            ->with('success', 'Sở thích đã được cập nhật thành công!');
    }
}


