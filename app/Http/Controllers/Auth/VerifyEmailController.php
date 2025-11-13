<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Clear response cache after email verification
            if (class_exists(\Spatie\ResponseCache\Facades\ResponseCache::class)) {
                \Spatie\ResponseCache\Facades\ResponseCache::clear();
            }
            return redirect()->route('verification.notice')->with('status', 'email-verified');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            
            // Clear response cache after email verification
            if (class_exists(\Spatie\ResponseCache\Facades\ResponseCache::class)) {
                \Spatie\ResponseCache\Facades\ResponseCache::clear();
            }
            
            // Regenerate session to ensure fresh state
            $request->session()->regenerate();
        }

        return redirect()->route('verification.notice')->with('status', 'email-verified');
    }
}
