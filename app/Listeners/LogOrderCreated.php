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

        OrderLog::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'action' => 'order_created',
            'details' => [
                'order_number' => $order->order_number,
                'total_price' => $order->total_price,
                'status' => $order->status,
            ],
        ]);
    }
}


