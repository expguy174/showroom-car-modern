<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RequireEmailVerification
{
    /**
     * Handle an incoming request.
     * Redirect to email verification if user is logged in but not verified (only for regular users).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            
            // Only require verification for regular users (not staff/admin)
            if ($user->role === 'user' && !$user->hasVerifiedEmail()) {
                $currentPath = $request->path();
                $routeName = $request->route()?->getName();
                
                // Allow access to verification routes, logout, and order success page
                $excludedPaths = [
                    'verify-email',
                    'email/verification-notification',
                    'logout',
                    'user/order/success', // Allow access to order success page even if not verified
                ];
                
                $excludedRouteNames = [
                    'verification.notice',
                    'verification.verify',
                    'verification.send',
                    'logout',
                    'user.order.success', // Allow access to order success page even if not verified
                ];
                
                // Check if current path is excluded
                $isExcludedPath = false;
                foreach ($excludedPaths as $excludedPath) {
                    if (str_starts_with($currentPath, $excludedPath)) {
                        $isExcludedPath = true;
                        break;
                    }
                }
                
                // Check if route name is excluded
                $isExcludedRoute = in_array($routeName, $excludedRouteNames);
                
                // If not excluded, redirect to verification immediately
                if (!$isExcludedPath && !$isExcludedRoute) {
                    return redirect()->route('verification.notice');
                }
            }
        }

        return $next($request);
    }
}
