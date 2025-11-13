<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]+$/', 'min:10', 'max:15'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'terms.accepted' => 'Bạn phải đồng ý với điều khoản sử dụng.',
            'name.required' => 'Vui lòng nhập họ và tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Don't auto-verify - user must verify email
            'email_verified' => false,
            'email_verified_at' => null,
        ]);

        // Create minimal user profile with optional name/phone
        UserProfile::create([
            'user_id' => $user->id,
            'name' => $request->input('name') ?: null,
            'phone' => $request->input('phone') ?: null,
        ]);

        Auth::login($user);

        // Send email verification using custom Mailable
        // Wrapped in try-catch to prevent registration failure if email fails
        $emailSent = false;
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerifyEmailNotification($user));
            $emailSent = true;
        } catch (\Exception $e) {
            // Log error but don't fail registration
            \Illuminate\Support\Facades\Log::error('Failed to send email verification during registration', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        // Don't fire Registered event to prevent duplicate email sending
        // We handle email verification manually above

        // Redirect to email verification notice page
        $message = $emailSent 
            ? 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.'
            : 'Đăng ký thành công! Tuy nhiên, không thể gửi email xác thực. Vui lòng nhấn "Gửi lại email xác thực" sau.';
        
        return redirect()->route('verification.notice')
            ->with('status', $message)
            ->with('email_sent', $emailSent);
    }
}
