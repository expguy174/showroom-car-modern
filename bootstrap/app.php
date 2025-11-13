<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // Rate limiting and security
            'rate.limit' => \App\Http\Middleware\RateLimitMiddleware::class,
            'security.headers' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'performance' => \App\Http\Middleware\PerformanceOptimization::class,
            
            // Role-based middleware
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'staff' => \App\Http\Middleware\IsStaff::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            
            // Email verification
            'require.email.verification' => \App\Http\Middleware\RequireEmailVerification::class,
        ]);
        
        // Global middleware - RequireEmailVerification should run after auth middleware
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        $middleware->append(\App\Http\Middleware\PerformanceOptimization::class);
        
        // Apply RequireEmailVerification to web routes only (after auth is resolved)
        $middleware->web(append: [
            \App\Http\Middleware\RequireEmailVerification::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
