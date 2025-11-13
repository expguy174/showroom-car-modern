<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\EmailService;
use App\Services\NotificationService;
// Execute synchronously so nav badge updates without queue worker
use Illuminate\Support\Facades\Log;

class SendOrderCreatedNotifications
{
    public function __construct()
    {
        //
    }

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        
        // Prevent duplicate processing using cache lock
        $lockKey = "order_created_notifications_{$order->id}";
        $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, 60); // 60 seconds lock
        
        if (!$lock->get()) {
            Log::warning('SendOrderCreatedNotifications: Already processing for order', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);
            return;
        }

        try {
        // Add logging to debug duplicate calls
        Log::info('SendOrderCreatedNotifications listener called', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'timestamp' => now(),
        ]);

            // Check if email already sent (prevent duplicate emails)
            $emailSentKey = "order_confirmation_email_sent_{$order->id}";
            if (!\Illuminate\Support\Facades\Cache::has($emailSentKey)) {
        try {
            app(EmailService::class)->sendOrderConfirmation($order);
                    \Illuminate\Support\Facades\Cache::put($emailSentKey, true, 3600); // Cache for 1 hour
        } catch (\Throwable $e) {
            Log::error('OrderCreated listener: email failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::info('OrderCreated listener: Email already sent, skipping', [
                    'order_id' => $order->id,
            ]);
        }

        try {
                // Notify user
            if ($order->user_id) {
                // Check if notification already exists to prevent duplicates
                $existingNotification = \App\Models\Notification::where('user_id', $order->user_id)
                    ->where('type', 'order_status')
                    ->where('title', 'Đơn hàng đã tạo')
                    ->where('message', 'Đơn hàng ' . $order->order_number . ' đã được tạo, chờ thanh toán/xác nhận.')
                    ->where('created_at', '>=', now()->subMinutes(5)) // Within last 5 minutes
                    ->first();
                
                if (!$existingNotification) {
                    app(NotificationService::class)->send(
                        $order->user_id,
                        'order_status',
                        'Đơn hàng đã tạo',
                        'Đơn hàng ' . $order->order_number . ' đã được tạo, chờ thanh toán/xác nhận.'
                    );
                } else {
                    Log::info('Duplicate notification prevented', [
                        'order_id' => $order->id,
                        'existing_notification_id' => $existingNotification->id,
                    ]);
                }
            }
                
                // Notify admin (user_id = null means admin notification)
                // Check if admin notification already exists to prevent duplicates
                $existingAdminNotification = \App\Models\Notification::whereNull('user_id')
                    ->where('type', 'new_order')
                    ->where('title', 'Đơn hàng mới')
                    ->where('message', 'like', '%Đơn hàng #' . $order->order_number . '%')
                    ->where('created_at', '>=', now()->subMinutes(5)) // Within last 5 minutes
                    ->first();
                
                if (!$existingAdminNotification) {
                    try {
                        \App\Models\Notification::create([
                            'user_id' => null,
                            'type' => 'new_order',
                            'title' => 'Đơn hàng mới',
                            'message' => 'Đơn hàng #' . $order->order_number . ' từ ' . ($order->user->userProfile->name ?? 'Khách hàng') . ' - Tổng tiền: ' . number_format($order->grand_total) . ' VNĐ',
                            'is_read' => false,
                        ]);
                    } catch (\Throwable $e) {
                        Log::error('OrderCreated listener: admin notification failed', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                } else {
                    Log::info('Duplicate admin notification prevented', [
                        'order_id' => $order->id,
                        'existing_notification_id' => $existingAdminNotification->id,
                    ]);
                }
        } catch (\Throwable $e) {
            Log::error('OrderCreated listener: notification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            }
        } finally {
            $lock->release();
        }
    }
}


