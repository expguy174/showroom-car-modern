<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Danh sách user
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $query = User::with('userProfile');

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

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->orderByDesc('created_at')->paginate(15);
        
        // Get role counts for filter tabs
        $roleCounts = [
            'all' => User::count(),
            'user' => User::where('role', 'user')->count(),
            'admin' => User::where('role', 'admin')->count(),
            'manager' => User::where('role', 'manager')->count(),
            'sales_person' => User::where('role', 'sales_person')->count(),
            'technician' => User::where('role', 'technician')->count(),
        ];
        
        return view('admin.users.index', compact('users', 'search', 'role', 'roleCounts'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin,manager,sales_person,technician',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Họ và tên là bắt buộc.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'role.required' => 'Vai trò là bắt buộc.',
            'role.in' => 'Vai trò được chọn không hợp lệ.',
            'employee_id.string' => 'Mã nhân viên phải là chuỗi ký tự.',
            'employee_id.max' => 'Mã nhân viên không được vượt quá 50 ký tự.',
            'employee_id.unique' => 'Mã nhân viên này đã tồn tại.',
            'department.string' => 'Phòng ban phải là chuỗi ký tự.',
            'department.max' => 'Phòng ban không được vượt quá 100 ký tự.',
            'position.string' => 'Chức vụ phải là chuỗi ký tự.',
            'position.max' => 'Chức vụ không được vượt quá 100 ký tự.',
            'hire_date.date' => 'Ngày tuyển dụng phải là ngày hợp lệ.',
            'avatar.image' => 'Avatar phải là hình ảnh.',
            'avatar.mimes' => 'Avatar phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước avatar không được vượt quá 2MB.',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'role', 'employee_id', 'department', 'position', 'hire_date'
        ]);

        $data['password'] = bcrypt($request->password);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được tạo thành công!');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id . '|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin,manager,sales_person,technician',
            'employee_id' => 'nullable|string|max:50|unique:users,employee_id,' . $user->id,
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Họ và tên là bắt buộc.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải có định dạng hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'role.required' => 'Vai trò là bắt buộc.',
            'role.in' => 'Vai trò được chọn không hợp lệ.',
            'employee_id.string' => 'Mã nhân viên phải là chuỗi ký tự.',
            'employee_id.max' => 'Mã nhân viên không được vượt quá 50 ký tự.',
            'employee_id.unique' => 'Mã nhân viên này đã tồn tại.',
            'department.string' => 'Phòng ban phải là chuỗi ký tự.',
            'department.max' => 'Phòng ban không được vượt quá 100 ký tự.',
            'position.string' => 'Chức vụ phải là chuỗi ký tự.',
            'position.max' => 'Chức vụ không được vượt quá 100 ký tự.',
            'hire_date.date' => 'Ngày tuyển dụng phải là ngày hợp lệ.',
            'avatar.image' => 'Avatar phải là hình ảnh.',
            'avatar.mimes' => 'Avatar phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước avatar không được vượt quá 2MB.',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'role', 'employee_id', 'department', 'position', 'hire_date'
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được cập nhật thành công!');
    }

    public function destroy(User $user)
    {
        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được xóa thành công!');
    }
}