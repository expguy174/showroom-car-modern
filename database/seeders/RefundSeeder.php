<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentTransaction;
use App\Models\Refund;

class RefundSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PaymentTransaction::inRandomOrder()->get() as $txn) {
            if (rand(1,100) > 15) continue; // ~15% đơn được hoàn 1 phần
            $amount = round(min($txn->amount * (rand(5,20)/100), (float) $txn->amount), 0);
            $refund = [
                'payment_transaction_id' => $txn->id,
                'amount' => $amount,
                'reason' => 'Hoàn tiền theo chương trình khuyến mãi/điều chỉnh',
                'status' => ['pending','processing','refunded','failed'][array_rand(['pending','processing','refunded','failed'])],
                'processed_at' => now()->subDays(rand(0,5)),
                'meta' => null,
            ];
            Refund::updateOrCreate([
                'payment_transaction_id' => $txn->id,
                'amount' => $amount,
            ], $refund);
        }
    }
}


