<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Usage: Route::middleware('role:admin,manager')->group(...)
     * Or: Route::middleware('role:admin')->get(...)
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;
        
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Role-specific error messages
        $roleLabels = [
            'admin' => 'Quản trị viên',
            'manager' => 'Quản lý', 
            'sales_person' => 'Nhân viên Kinh doanh',
            'technician' => 'Kỹ thuật viên',
            'user' => 'Người dùng'
        ];

        $allowedRoles = array_map(function($role) use ($roleLabels) {
            return $roleLabels[$role] ?? ucfirst($role);
        }, $roles);

        abort(403, 'Truy cập bị từ chối - Chỉ dành cho: ' . implode(', ', $allowedRoles));
    }
}
