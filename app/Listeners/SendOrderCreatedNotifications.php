<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\EmailService;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderCreatedNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

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
                app(NotificationService::class)->send(
                    $order->user_id,
                    'order_status',
                    'Đơn hàng đã tạo',
                    'Đơn hàng ' . $order->order_number . ' đã được tạo, chờ thanh toán/xác nhận.',
                    ['order_id' => $order->id]
                );
            }
        } catch (\Throwable $e) {
            Log::error('OrderCreated listener: notification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}


