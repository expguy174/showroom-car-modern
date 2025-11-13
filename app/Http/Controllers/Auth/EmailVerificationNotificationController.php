<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmailNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        try {
            // Use custom Mailable instead of sendEmailVerificationNotification()
            // This works the same way as EmailService which is already working
            Mail::to($request->user()->email)->send(new VerifyEmailNotification($request->user()));
        return back()->with('status', 'verification-link-sent');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send email verification notification', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check if it's a Mailtrap limit error
            $errorMessage = $e->getMessage();
            $userMessage = 'Không thể gửi email xác thực. Vui lòng thử lại sau.';
            
            if (str_contains($errorMessage, 'email limit is reached') || str_contains($errorMessage, 'upgrade your plan')) {
                $userMessage = 'Mailtrap đã đạt giới hạn email. Vui lòng nâng cấp gói hoặc đợi reset giới hạn.';
            } elseif (str_contains($errorMessage, 'Trying to access array offset')) {
                $userMessage = 'Lỗi kết nối email server. Vui lòng kiểm tra cấu hình Mailtrap hoặc thử lại sau.';
            }
            
            return back()->with('error', $userMessage);
        }
    }
}
