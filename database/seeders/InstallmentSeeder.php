<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;
use App\Models\PaymentTransaction;
use App\Models\Installment;

class InstallmentSeeder extends Seeder
{
    public function run(): void
    {
        $order = Order::first();
        $user = $order?->user ?? User::where('role','user')->first();
        if (!$order || !$user) return;

        $amount = (float) $order->grand_total;
        if ($amount <= 0) { $amount = 1000000000; }
        $tenure = 12;
        $monthly = round($amount / $tenure, 2);

        $txn = PaymentTransaction::first();

        for ($i = 1; $i <= 6; $i++) {
            Installment::updateOrCreate([
                'order_id' => $order->id,
                'installment_number' => $i,
            ], [
                'order_id' => $order->id,
                'user_id' => $user->id,
                'finance_option_id' => 1, // Reference to first finance option
                'payment_transaction_id' => $txn?->id,
                'installment_number' => $i,
                'amount' => $monthly,
                'due_date' => now()->addMonths($i),
                'status' => $i <= 2 ? 'paid' : 'pending',
                'paid_at' => $i <= 2 ? now()->subDays($i) : null,
                'approved_at' => now(),
                'cancelled_at' => null,
            ]);
        }
    }
}


