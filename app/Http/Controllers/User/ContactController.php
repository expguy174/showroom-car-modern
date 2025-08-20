<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        return view('user.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        try {
            $topic = $this->mapSubjectToTopic($validated['subject']);
            $contactMessage = ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'topic' => $topic,
                'status' => 'new',
                'source' => 'website',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => [
                    'page_url' => $request->headers->get('referer'),
                    'device_type' => $this->getDeviceType($request->userAgent()),
                    'form_data' => [
                        'subject' => $validated['subject'],
                        'submitted_at' => now()->toISOString(),
                    ]
                ],
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.'
                ]);
            }

            return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.'
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.');
        }
    }

    /**
     * Map subject to topic for better categorization
     */
    private function mapSubjectToTopic($subject)
    {
        $subject = strtolower($subject);
        
        if (str_contains($subject, 'mua xe') || str_contains($subject, 'tư vấn') || str_contains($subject, 'giá xe')) {
            return 'sales';
        }
        
        if (str_contains($subject, 'bảo dưỡng') || str_contains($subject, 'sửa chữa') || str_contains($subject, 'dịch vụ')) {
            return 'service';
        }
        
        if (str_contains($subject, 'lái thử') || str_contains($subject, 'test drive')) {
            return 'test_drive';
        }
        
        if (str_contains($subject, 'bảo hành') || str_contains($subject, 'khiếu nại')) {
            return 'warranty';
        }
        
        if (str_contains($subject, 'tài chính') || str_contains($subject, 'vay') || str_contains($subject, 'trả góp')) {
            return 'finance';
        }
        
        return 'other';
    }

    /**
     * Detect device type from user agent
     */
    private function getDeviceType($userAgent)
    {
        if (empty($userAgent)) {
            return 'unknown';
        }
        
        $userAgent = strtolower($userAgent);
        
        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'android') || str_contains($userAgent, 'iphone')) {
            return 'mobile';
        }
        
        if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return 'tablet';
        }
        
        return 'desktop';
    }
}
