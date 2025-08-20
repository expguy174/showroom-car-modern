<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerProfile;
use App\Models\Order;
use App\Models\TestDrive;
// use App\Models\CustomerPoint;
use App\Models\Showroom;
use Illuminate\Support\Facades\Auth;

class CustomerProfileController extends Controller
{
    public function index()
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())
            ->with(['user', 'preferredShowroom'])
            ->first();

        if (!$customerProfile) {
            return redirect()->route('user.customer-profiles.create');
        }

        // Get customer's orders
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.item'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get customer's test drives
        $testDrives = TestDrive::where('user_id', Auth::id())
            ->with(['carVariant.carModel.carBrand'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('user.customer-profiles.index', compact('customerProfile', 'orders', 'testDrives'));
    }

    public function create()
    {
        $showrooms = Showroom::where('is_active', true)->get();
        return view('user.customer-profiles.create', compact('showrooms'));
    }

    public function store(Request $request)
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
            'consent_to_marketing' => 'boolean',
            'consent_to_sms' => 'boolean',
            'consent_to_email' => 'boolean',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['preferred_car_types'] = $request->preferred_car_types ? json_encode($request->preferred_car_types) : null;
        $data['preferred_brands'] = $request->preferred_brands ? json_encode($request->preferred_brands) : null;
        $data['consent_to_marketing'] = $request->has('consent_to_marketing');
        $data['consent_to_sms'] = $request->has('consent_to_sms');
        $data['consent_to_email'] = $request->has('consent_to_email');

        $customerProfile = CustomerProfile::create($data);

        return redirect()->route('user.customer-profiles.index')
            ->with('success', 'Hồ sơ khách hàng đã được tạo thành công!');
    }

    public function edit()
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())->firstOrFail();
        $showrooms = Showroom::where('is_active', true)->get();
        
        return view('user.customer-profiles.edit', compact('customerProfile', 'showrooms'));
    }

    public function update(Request $request)
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())->firstOrFail();

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
            'consent_to_marketing' => 'boolean',
            'consent_to_sms' => 'boolean',
            'consent_to_email' => 'boolean',
        ]);

        $data = $request->all();
        $data['preferred_car_types'] = $request->preferred_car_types ? json_encode($request->preferred_car_types) : null;
        $data['preferred_brands'] = $request->preferred_brands ? json_encode($request->preferred_brands) : null;
        $data['consent_to_marketing'] = $request->has('consent_to_marketing');
        $data['consent_to_sms'] = $request->has('consent_to_sms');
        $data['consent_to_email'] = $request->has('consent_to_email');

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
        // Kiểm tra xem order có thuộc về user hiện tại không
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

    // public function points() { /* Module loyalty đã lược bỏ */ }

    public function preferences()
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())
            ->with(['preferredShowroom'])
            ->firstOrFail();

        $showrooms = Showroom::where('is_active', true)->get();
        
        return view('user.customer-profiles.preferences', compact('customerProfile', 'showrooms'));
    }

    public function updatePreferences(Request $request)
    {
        $customerProfile = CustomerProfile::where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'preferred_car_types' => 'nullable|array',
            'preferred_brands' => 'nullable|array',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'purchase_purpose' => 'nullable|string|max:200',
            'preferred_showroom_id' => 'nullable|exists:showrooms,id',
            'consent_to_marketing' => 'boolean',
            'consent_to_sms' => 'boolean',
            'consent_to_email' => 'boolean',
        ]);

        $data = [
            'preferred_car_types' => $request->preferred_car_types ? json_encode($request->preferred_car_types) : null,
            'preferred_brands' => $request->preferred_brands ? json_encode($request->preferred_brands) : null,
            'budget_min' => $request->budget_min,
            'budget_max' => $request->budget_max,
            'purchase_purpose' => $request->purchase_purpose,
            'preferred_showroom_id' => $request->preferred_showroom_id,
            'consent_to_marketing' => $request->has('consent_to_marketing'),
            'consent_to_sms' => $request->has('consent_to_sms'),
            'consent_to_email' => $request->has('consent_to_email'),
        ];

        $customerProfile->update($data);

        return redirect()->route('user.customer-profiles.preferences')
            ->with('success', 'Sở thích đã được cập nhật thành công!');
    }
}
