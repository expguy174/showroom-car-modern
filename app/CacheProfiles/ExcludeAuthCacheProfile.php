<?php

namespace App\CacheProfiles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\CacheProfiles\CacheAllSuccessfulGetRequests;

class ExcludeAuthCacheProfile extends CacheAllSuccessfulGetRequests
{
    /**
     * Determine if the given request should be cached.
     */
    public function shouldCacheRequest(Request $request): bool
    {
        // Don't cache if user is authenticated
        if (Auth::check()) {
            return false;
        }

        // Don't cache home page (it has dynamic content based on auth state)
        if ($request->routeIs('home') || $request->is('/')) {
            return false;
        }

        // Call parent to check other conditions
        return parent::shouldCacheRequest($request);
    }

    /**
     * Determine if the given response should be cached.
     */
    public function shouldCacheResponse($response): bool
    {
        // Don't cache if user is authenticated
        if (Auth::check()) {
            return false;
        }

        return parent::shouldCacheResponse($response);
    }
}

