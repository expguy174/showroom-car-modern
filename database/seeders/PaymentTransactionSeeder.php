<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\PaymentMethod;
use App\Models\User;

class PaymentTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $methods = PaymentMethod::all();
        foreach (Order::where('payment_status','paid')->cursor() as $order) {
            $user = $order->user ?? User::where('role','user')->first();
            $method = $methods->random();
            if (!$user || !$method) continue;
            $txn = [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'payment_method_id' => $method->id,
                'transaction_number' => 'TXN-' . $order->order_number,
                'amount' => $order->grand_total,
                'currency' => 'VND',
                'status' => 'completed',
                'payment_date' => now()->subDays(rand(0,7)),
                'notes' => 'Thanh toán đơn hàng',
            ];
            PaymentTransaction::updateOrCreate(['transaction_number' => $txn['transaction_number']], $txn);
        }
    }
}


