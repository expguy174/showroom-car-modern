<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\OrderLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogOrderCreated implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderCreated $event): void
    {
        $order = $event->order;

        // Check if order_created log already exists to prevent duplicates
        $existingLog = OrderLog::where('order_id', $order->id)
            ->where('action', 'order_created')
            ->first();

        if ($existingLog) {
            return; // Skip if already exists
        }

        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'action' => 'order_created',
            'message' => 'Đơn hàng được tạo',
            'details' => [
                'order_number' => $order->order_number,
                'grand_total' => $order->grand_total,
                'payment_method_id' => $order->payment_method_id,
                'status' => $order->status,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}


