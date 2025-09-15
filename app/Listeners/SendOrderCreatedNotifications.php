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
        
        // Add logging to debug duplicate calls
        Log::info('SendOrderCreatedNotifications listener called', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'timestamp' => now(),
        ]);

        try {
            app(EmailService::class)->sendOrderConfirmation($order);
        } catch (\Throwable $e) {
            Log::error('OrderCreated listener: email failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }

        try {
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
        } catch (\Throwable $e) {
            Log::error('OrderCreated listener: notification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}


