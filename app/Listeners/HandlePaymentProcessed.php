<?php

namespace App\Listeners;

use App\Events\PaymentProcessed;
use App\Models\Order;
use App\Services\NotificationService;
use App\Models\OrderLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandlePaymentProcessed implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(PaymentProcessed $event): void
    {
        $transaction = $event->transaction;

        // If transaction is tied to an order, update order payment status
        if ($transaction->order_id) {
            $order = Order::find($transaction->order_id);
            if ($order) {
                if ($transaction->status === 'completed') {
                    $order->update([
                        'payment_status' => 'completed',
                        'paid_at' => now(),
                        'transaction_id' => $transaction->transaction_number,
                    ]);
                }

                if ($order->user_id) {
                    app(NotificationService::class)->send(
                        $order->user_id,
                        'payment',
                        $transaction->status === 'completed' ? 'Thanh toán thành công' : 'Thanh toán thất bại',
                        'Giao dịch ' . $transaction->transaction_number . ' cho đơn ' . ($order->order_number ?? $order->id) . ' đã ' . ($transaction->status === 'completed' ? 'thành công' : 'thất bại')
                    );
                }

                OrderLog::create([
                    'order_id' => $order->id,
                    'user_id' => null,
                    'action' => 'payment_' . ($transaction->status === 'completed' ? 'completed' : 'failed'),
                    'details' => [
                        'transaction_number' => $transaction->transaction_number,
                        'status' => $transaction->status,
                        'amount' => $transaction->amount,
                    ],
                ]);
            }
        }
    }
}


