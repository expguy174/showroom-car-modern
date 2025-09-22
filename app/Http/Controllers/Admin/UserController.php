<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

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
}