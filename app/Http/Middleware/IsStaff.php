<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStaff
{
    /**
     * Handle an incoming request.
     * Staff includes: admin, manager, sales_person, technician
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $staffRoles = ['admin', 'manager', 'sales_person', 'technician'];
        
        if (in_array(auth()->user()->role, $staffRoles)) {
            return $next($request);
        }

        abort(403, 'Truy cập bị từ chối - Chỉ dành cho nhân viên');
    }
}
