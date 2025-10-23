<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $status = $request->input('status');

        $query = User::with(['userProfile', 'addresses']);

        // Search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhere('employee_id', 'like', "%$search%")
                  ->orWhere('department', 'like', "%$search%")
                  ->orWhere('position', 'like', "%$search%")
                  ->orWhereHas('userProfile', function($profile) use ($search) {
                      $profile->where('name', 'like', "%$search%")
                              ->orWhere('phone', 'like', "%$search%");
                  });
            });
        }

        // Role filter
        if ($role) {
            $query->where('role', $role);
        }

        // Status filter
        if ($status !== null && $status !== '') {
            $query->where('is_active', $status == 'active' ? 1 : 0);
        }

        $users = $query->orderByDesc('created_at')->paginate(15);
        
        // Calculate stats
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', 1)->count(),
            'inactive' => User::where('is_active', 0)->count(),
        ];

        // Role counts for dropdown
        $roleCounts = [
            'all' => User::count(),
            'user' => User::where('role', 'user')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'manager' => User::where('role', 'manager')->count(),
            'sales_person' => User::where('role', 'sales_person')->count(),
            'technician' => User::where('role', 'technician')->count(),
        ];

        // AJAX request - return table HTML only (giống Services)
        if ($request->ajax() || $request->wantsJson()) {
            return view('admin.users.partials.table', compact('users'))->render();
        }
        
        return view('admin.users.index', compact('users', 'search', 'role', 'status', 'stats', 'roleCounts'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Check if soft deleted user exists with this email
        $existingUser = User::withTrashed()->where('email', $request->email)->first();
        $isRestoring = $existingUser && $existingUser->trashed();
        
        // Validate with different rules if restoring
        $emailRule = $isRestoring 
            ? ['required', 'email', 'max:255', Rule::unique('users')->ignore($existingUser->id)]
            : 'required|email|unique:users,email|max:255';
            
        $employeeIdRule = $isRestoring
            ? ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($existingUser->id)]
            : 'nullable|string|max:50|unique:users,employee_id';
        
        $validated = $request->validate([
            // Account info (users table)
            'email' => $emailRule,
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,manager,sales_person,technician',
            'is_active' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
            'employee_id' => $employeeIdRule,
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            
            // Profile info (user_profiles table)
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:32',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Driver license info
            'driver_license_number' => 'nullable|string|max:255',
            'driver_license_issue_date' => 'nullable|date',
            'driver_license_expiry_date' => 'nullable|date|after:driver_license_issue_date',
            'driver_license_class' => 'nullable|string|max:255',
            'driving_experience_years' => 'nullable|integer|min:0',
            
            // Customer info
            'customer_type' => 'nullable|in:new,returning,vip,prospect',
            'profile_type' => 'nullable|in:customer,employee',
            'is_vip' => 'nullable|boolean',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0|gte:budget_min',
            'purchase_purpose' => 'nullable|in:personal,business,family,investment',
            'preferred_car_types' => 'nullable|string',
            'preferred_brands' => 'nullable|string',
            'preferred_colors' => 'nullable|string',
            
            // Employee info (for non-user roles)
            'employee_salary' => 'nullable|numeric|min:0',
            'employee_skills' => 'nullable|string',
            
            // Multiple addresses array (all optional - user có thể thêm sau)
            'addresses' => 'nullable|array',
            'addresses.*.type' => 'nullable|in:home,work,billing,shipping,other',
            'addresses.*.contact_name' => 'required_with:addresses.*.address|string|max:255',
            'addresses.*.phone' => 'nullable|string|max:20',
            'addresses.*.address' => 'nullable|string',
            'addresses.*.city' => 'required_with:addresses.*.address|string|max:255',
            'addresses.*.state' => 'nullable|string|max:255',
            'addresses.*.postal_code' => 'nullable|string|max:20',
            'addresses.*.country' => 'nullable|string|max:255',
            'addresses.*.notes' => 'nullable|string',
            'addresses.*.is_default' => 'nullable|in:0,1',
        ], [
            // Email validation messages
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            
            // Password validation messages
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            
            // Role validation messages
            'role.required' => 'Vai trò là bắt buộc.',
            'role.in' => 'Vai trò được chọn không hợp lệ.',
            
            // Employee validation messages
            'employee_id.string' => 'Mã nhân viên phải là chuỗi ký tự.',
            'employee_id.max' => 'Mã nhân viên không được vượt quá 50 ký tự.',
            'employee_id.unique' => 'Mã nhân viên này đã tồn tại.',
            'department.string' => 'Phòng ban phải là chuỗi ký tự.',
            'department.max' => 'Phòng ban không được vượt quá 100 ký tự.',
            'position.string' => 'Chức vụ phải là chuỗi ký tự.',
            'position.max' => 'Chức vụ không được vượt quá 100 ký tự.',
            'hire_date.date' => 'Ngày tuyển dụng phải là ngày hợp lệ.',
            
            // Profile validation messages
            'name.required' => 'Họ và tên là bắt buộc.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 32 ký tự.',
            'birth_date.date' => 'Ngày sinh phải là ngày hợp lệ.',
            'birth_date.before' => 'Ngày sinh phải trước ngày hôm nay.',
            'gender.in' => 'Giới tính được chọn không hợp lệ.',
            'avatar.image' => 'Avatar phải là hình ảnh.',
            'avatar.mimes' => 'Avatar phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước avatar không được vượt quá 2MB.',
            
            // Driver license messages
            'driver_license_expiry_date.after' => 'Ngày hết hạn phải sau ngày cấp.',
            'driving_experience_years.integer' => 'Số năm kinh nghiệm phải là số nguyên.',
            'driving_experience_years.min' => 'Số năm kinh nghiệm không được âm.',
        ]);

        // Check for duplicate addresses
        if (!empty($validated['addresses']) && is_array($validated['addresses'])) {
            $addressKeys = [];
            foreach ($validated['addresses'] as $index => $address) {
                // Skip empty addresses
                if (empty($address['address']) || empty($address['city'])) {
                    continue;
                }
                
                // Create unique key from address fields
                $key = strtolower(trim($address['address'])) . '|' . strtolower(trim($address['city'])) . '|' . strtolower(trim($address['country'] ?? 'vietnam'));
                
                if (in_array($key, $addressKeys)) {
                    $errorMessage = 'Địa chỉ bị trùng lặp. Vui lòng kiểm tra lại các địa chỉ đã nhập.';
                    
                    // Return JSON for AJAX requests
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage,
                            'errors' => ['addresses' => $errorMessage]
                        ], 422);
                    }
                    
                    // Regular redirect for non-AJAX
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['addresses' => $errorMessage]);
                }
                
                $addressKeys[] = $key;
            }
        }

        // Create or restore user account
        if ($isRestoring) {
            // Restore and update existing user
            $existingUser->restore();
            
            // Delete old addresses to avoid duplicates
            $existingUser->addresses()->delete();
            
            $existingUser->update([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => $validated['is_active'] ?? true,
                'email_verified' => $validated['email_verified'] ?? false,
                'email_verified_at' => ($validated['email_verified'] ?? false) ? now() : null,
                'employee_id' => $validated['employee_id'] ?? null,
                'department' => $validated['department'] ?? null,
                'position' => $validated['position'] ?? null,
                'hire_date' => $validated['hire_date'] ?? null,
            ]);
            $user = $existingUser;
        } else {
            // Create new user
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => $validated['is_active'] ?? true,
                'email_verified' => $validated['email_verified'] ?? false,
                'email_verified_at' => ($validated['email_verified'] ?? false) ? now() : null,
                'employee_id' => $validated['employee_id'] ?? null,
                'department' => $validated['department'] ?? null,
                'position' => $validated['position'] ?? null,
                'hire_date' => $validated['hire_date'] ?? null,
            ]);
        }

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Convert comma-separated strings to JSON arrays
        $preferredCarTypes = !empty($validated['preferred_car_types']) 
            ? array_map('trim', explode(',', $validated['preferred_car_types']))
            : null;
        $preferredBrands = !empty($validated['preferred_brands'])
            ? array_map('trim', explode(',', $validated['preferred_brands']))
            : null;
        $preferredColors = !empty($validated['preferred_colors'])
            ? array_map('trim', explode(',', $validated['preferred_colors']))
            : null;

        // Create or update user profile (handles both new and restored users)
        $user->userProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
            'profile_type' => $validated['profile_type'] ?? ($validated['role'] === 'user' ? 'customer' : 'employee'),
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'avatar_path' => $avatarPath,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'driver_license_number' => $validated['driver_license_number'] ?? null,
            'driver_license_issue_date' => $validated['driver_license_issue_date'] ?? null,
            'driver_license_expiry_date' => $validated['driver_license_expiry_date'] ?? null,
            'driver_license_class' => $validated['driver_license_class'] ?? null,
            'driving_experience_years' => $validated['driving_experience_years'] ?? null,
            'customer_type' => $validated['customer_type'] ?? 'new',
            'is_vip' => $validated['is_vip'] ?? false,
            'budget_min' => $validated['budget_min'] ?? null,
            'budget_max' => $validated['budget_max'] ?? null,
            'purchase_purpose' => $validated['purchase_purpose'] ?? null,
            'preferred_car_types' => $preferredCarTypes,
            'preferred_brands' => $preferredBrands,
            'preferred_colors' => $preferredColors,
            'employee_salary' => $validated['employee_salary'] ?? null,
            'employee_skills' => $validated['employee_skills'] ?? null,
            ]
        );

        // Create addresses if provided
        if (!empty($validated['addresses']) && is_array($validated['addresses'])) {
            foreach ($validated['addresses'] as $addressData) {
                $user->addresses()->create([
                    'type' => $addressData['type'] ?? 'home',
                    'contact_name' => $addressData['contact_name'],
                    'phone' => $addressData['phone'] ?? null,
                    'address' => $addressData['address'],
                    'city' => $addressData['city'],
                    'state' => $addressData['state'] ?? null,
                    'postal_code' => $addressData['postal_code'] ?? null,
                    'country' => $addressData['country'] ?? 'Vietnam',
                    'is_default' => isset($addressData['is_default']) && $addressData['is_default'] == '1',
                    'notes' => $addressData['notes'] ?? null,
                ]);
            }
        }

        // Return JSON for AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Người dùng đã được tạo thành công.',
                'redirect' => route('admin.users.index')
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công.');
    }

    public function show(User $user)
    {
        $user->load(['userProfile', 'addresses', 'orders', 'testDrives', 'serviceAppointments']);
        
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $user->load(['userProfile', 'addresses']);
        
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            // Account info
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)->whereNull('deleted_at')],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin,manager,sales_person,technician',
            'is_active' => 'nullable|boolean',
            'email_verified' => 'nullable|boolean',
            'employee_id' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)->whereNull('deleted_at')],
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            
            // Profile info
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:32',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Driver license info
            'driver_license_number' => 'nullable|string|max:255',
            'driver_license_issue_date' => 'nullable|date',
            'driver_license_expiry_date' => 'nullable|date|after:driver_license_issue_date',
            'driver_license_class' => 'nullable|string|max:255',
            'driving_experience_years' => 'nullable|integer|min:0',
            
            // Customer info
            'customer_type' => 'nullable|in:new,returning,vip,prospect',
            'profile_type' => 'nullable|in:customer,employee',
            'is_vip' => 'nullable|boolean',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0|gte:budget_min',
            'purchase_purpose' => 'nullable|in:personal,business,family,investment',
            'preferred_car_types' => 'nullable|string',
            'preferred_brands' => 'nullable|string',
            'preferred_colors' => 'nullable|string',
            
            // Employee info (for non-user roles)
            'employee_salary' => 'nullable|numeric|min:0',
            'employee_skills' => 'nullable|string',
            
            // Multiple addresses array (all optional)
            'addresses' => 'nullable|array',
            'addresses.*.id' => 'nullable|integer|exists:addresses,id',
            'addresses.*.type' => 'nullable|in:home,work,billing,shipping,other',
            'addresses.*.contact_name' => 'required_with:addresses.*.address|string|max:255',
            'addresses.*.phone' => 'nullable|string|max:20',
            'addresses.*.address' => 'nullable|string',
            'addresses.*.city' => 'required_with:addresses.*.address|string|max:255',
            'addresses.*.state' => 'nullable|string|max:255',
            'addresses.*.postal_code' => 'nullable|string|max:20',
            'addresses.*.country' => 'nullable|string|max:255',
            'addresses.*.notes' => 'nullable|string',
            'addresses.*.is_default' => 'nullable|in:0,1',
        ], [
            // Same validation messages as store method
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'name.required' => 'Họ và tên là bắt buộc.',
            'role.required' => 'Vai trò là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Check for duplicate addresses
        if (!empty($validated['addresses']) && is_array($validated['addresses'])) {
            $addressKeys = [];
            foreach ($validated['addresses'] as $index => $address) {
                // Skip empty addresses
                if (empty($address['address']) || empty($address['city'])) {
                    continue;
                }
                
                // Create unique key from address fields
                $key = strtolower(trim($address['address'])) . '|' . strtolower(trim($address['city'])) . '|' . strtolower(trim($address['country'] ?? 'vietnam'));
                
                if (in_array($key, $addressKeys)) {
                    $errorMessage = 'Địa chỉ bị trùng lặp. Vui lòng kiểm tra lại các địa chỉ đã nhập.';
                    
                    // Return JSON for AJAX requests
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $errorMessage,
                            'errors' => ['addresses' => $errorMessage]
                        ], 422);
                    }
                    
                    // Regular redirect for non-AJAX
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['addresses' => $errorMessage]);
                }
                
                $addressKeys[] = $key;
            }
        }

        // Update user account
        $userData = [
            'email' => $validated['email'],
            'role' => $validated['role'],
            'is_active' => $request->has('is_active') ? true : false,
            'email_verified' => $request->has('email_verified') ? true : false,
            'employee_id' => $validated['employee_id'] ?? null,
            'department' => $validated['department'] ?? null,
            'position' => $validated['position'] ?? null,
            'hire_date' => $validated['hire_date'] ?? null,
        ];

        // Update email_verified_at based on checkbox
        if ($request->has('email_verified')) {
            // Checkbox checked - set verified
            if (!$user->email_verified_at) {
                $userData['email_verified_at'] = now();
            }
        } else {
            // Checkbox unchecked - clear verified
            $userData['email_verified_at'] = null;
        }

        // Update password only if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->userProfile && $user->userProfile->avatar_path) {
                Storage::disk('public')->delete($user->userProfile->avatar_path);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } else {
            $avatarPath = $user->userProfile->avatar_path ?? null;
        }

        // Convert comma-separated strings to JSON arrays
        $preferredCarTypes = !empty($validated['preferred_car_types']) 
            ? array_map('trim', explode(',', $validated['preferred_car_types']))
            : null;
        $preferredBrands = !empty($validated['preferred_brands'])
            ? array_map('trim', explode(',', $validated['preferred_brands']))
            : null;
        $preferredColors = !empty($validated['preferred_colors'])
            ? array_map('trim', explode(',', $validated['preferred_colors']))
            : null;

        // Update or create user profile
        $user->userProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'profile_type' => $validated['profile_type'] ?? ($validated['role'] === 'user' ? 'customer' : 'employee'),
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'avatar_path' => $avatarPath,
                'birth_date' => $validated['birth_date'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'driver_license_number' => $validated['driver_license_number'] ?? null,
                'driver_license_issue_date' => $validated['driver_license_issue_date'] ?? null,
                'driver_license_expiry_date' => $validated['driver_license_expiry_date'] ?? null,
                'driver_license_class' => $validated['driver_license_class'] ?? null,
                'driving_experience_years' => $validated['driving_experience_years'] ?? null,
                'customer_type' => $validated['customer_type'] ?? 'new',
                'is_vip' => $validated['is_vip'] ?? false,
                'budget_min' => $validated['budget_min'] ?? null,
                'budget_max' => $validated['budget_max'] ?? null,
                'purchase_purpose' => $validated['purchase_purpose'] ?? null,
                'preferred_car_types' => $preferredCarTypes,
                'preferred_brands' => $preferredBrands,
                'preferred_colors' => $preferredColors,
                'employee_salary' => $validated['employee_salary'] ?? null,
                'employee_skills' => $validated['employee_skills'] ?? null,
            ]
        );

        // Sync addresses
        if (!empty($validated['addresses']) && is_array($validated['addresses'])) {
            $submittedAddressIds = [];
            
            foreach ($validated['addresses'] as $addressData) {
                if (!empty($addressData['id'])) {
                    // Update existing address
                    $address = $user->addresses()->find($addressData['id']);
                    if ($address) {
                        $address->update([
                            'type' => $addressData['type'],
                            'contact_name' => $addressData['contact_name'],
                            'phone' => $addressData['phone'] ?? null,
                            'address' => $addressData['address'],
                            'city' => $addressData['city'],
                            'state' => $addressData['state'] ?? null,
                            'postal_code' => $addressData['postal_code'] ?? null,
                            'country' => $addressData['country'] ?? 'Vietnam',
                            'is_default' => isset($addressData['is_default']) && $addressData['is_default'] == '1',
                            'notes' => $addressData['notes'] ?? null,
                        ]);
                        $submittedAddressIds[] = $address->id;
                    }
                } else {
                    // Create new address
                    $newAddress = $user->addresses()->create([
                        'type' => $addressData['type'],
                        'contact_name' => $addressData['contact_name'],
                        'phone' => $addressData['phone'] ?? null,
                        'address' => $addressData['address'],
                        'city' => $addressData['city'],
                        'state' => $addressData['state'] ?? null,
                        'postal_code' => $addressData['postal_code'] ?? null,
                        'country' => $addressData['country'] ?? 'Vietnam',
                        'is_default' => isset($addressData['is_default']) && $addressData['is_default'] == '1',
                        'notes' => $addressData['notes'] ?? null,
                    ]);
                    $submittedAddressIds[] = $newAddress->id;
                }
            }
            
            // Delete addresses that were removed from the form
            $user->addresses()->whereNotIn('id', $submittedAddressIds)->delete();
        }

        // Return JSON for AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Người dùng đã được cập nhật thành công.',
                'redirect' => route('admin.users.index')
            ]);
        }

        return redirect()->route('admin.users.edit', $user)
            ->with('success', 'Người dùng đã được cập nhật thành công.');
    }

    public function destroy(User $user, Request $request)
    {
        try {
            $userName = $user->userProfile->name ?? $user->email;
            
            // 1. Check if user is currently active
            if ($user->is_active) {
                $message = "Không thể xóa người dùng \"{$userName}\" vì đang ở trạng thái HOẠT ĐỘNG. Vui lòng tắt tài khoản trước khi xóa.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->back()->with('error', $message);
            }

            // 2. Check for pending/active orders
            $pendingOrders = $user->orders()
                ->whereIn('status', ['pending', 'confirmed', 'processing', 'shipping'])
                ->count();
            
            if ($pendingOrders > 0) {
                $message = "Không thể xóa người dùng \"{$userName}\" vì còn {$pendingOrders} đơn hàng đang xử lý. Vui lòng hoàn thành hoặc hủy các đơn hàng trước.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->back()->with('error', $message);
            }

            // 3. Check for upcoming test drives
            $upcomingTestDrives = $user->testDrives()
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('preferred_date', '>=', now())
                ->count();
            
            if ($upcomingTestDrives > 0) {
                $message = "Không thể xóa người dùng \"{$userName}\" vì còn {$upcomingTestDrives} lịch lái thử sắp tới. Vui lòng hủy các lịch hẹn trước.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->back()->with('error', $message);
            }

            // 4. Check for upcoming service appointments
            $upcomingServices = $user->serviceAppointments()
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->where('appointment_date', '>=', now())
                ->count();
            
            if ($upcomingServices > 0) {
                $message = "Không thể xóa người dùng \"{$userName}\" vì còn {$upcomingServices} lịch dịch vụ sắp tới. Vui lòng hủy các lịch hẹn trước.";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->back()->with('error', $message);
            }

            // 5. Prevent deleting employees (anyone with employee_id)
            if ($user->employee_id) {
                $roleLabels = [
                    'admin' => 'Quản trị viên',
                    'sales_person' => 'Nhân viên bán hàng',
                    'technician' => 'Kỹ thuật viên',
                    'manager' => 'Quản lý'
                ];
                
                $roleLabel = $roleLabels[$user->role] ?? $user->role;
                $message = "Không thể xóa {$roleLabel} \"{$userName}\" (Mã NV: {$user->employee_id}). Chỉ có thể vô hiệu hóa tài khoản (tắt trạng thái hoạt động).";
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->back()->with('error', $message);
            }

            // All checks passed - proceed with deletion
            
            // Delete avatar if exists
            if ($user->userProfile && $user->userProfile->avatar_path) {
                Storage::disk('public')->delete($user->userProfile->avatar_path);
            }

            // Soft delete user (preserves historical data in related tables)
            $user->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Người dùng \"{$userName}\" đã được xóa thành công. Dữ liệu lịch sử đã được giữ lại."
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', "Người dùng \"{$userName}\" đã được xóa thành công.");

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xóa người dùng: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa người dùng.');
        }
    }

    public function toggle(User $user, Request $request)
    {
        try {
            $user->is_active = !$user->is_active;
            $user->save();

            $status = $user->is_active ? 'kích hoạt' : 'tạm khóa';
            $userName = $user->userProfile->name ?? $user->email;

            // Calculate updated stats
            $stats = [
                'total' => User::count(),
                'active' => User::where('is_active', 1)->count(),
                'inactive' => User::where('is_active', 0)->count(),
            ];

            return response()->json([
                'success' => true,
                'is_active' => $user->is_active,
                'message' => "Đã {$status} người dùng \"{$userName}\" thành công.",
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }
}
