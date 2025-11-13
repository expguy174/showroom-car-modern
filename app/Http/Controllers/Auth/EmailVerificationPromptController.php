<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // If email is verified and no status message, redirect to dashboard
        // But if there's a status message (like 'email-verified'), show the view with success message
        if ($request->user()->hasVerifiedEmail() && !session('status')) {
            return redirect()->intended(route('dashboard', absolute: false));
        }
        
        return view('auth.verify-email');
    }
}
