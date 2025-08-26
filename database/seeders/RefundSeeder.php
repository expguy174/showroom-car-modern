<?php

namespace Database\Seeders;

use App\Models\Refund;
use App\Models\PaymentTransaction;
use Illuminate\Database\Seeder;

class RefundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentTransactions = PaymentTransaction::all();

        $refunds = [
            // Cancelled Order Refund
            [
                'payment_transaction_id' => $paymentTransactions->where('transaction_number', 'TXN-2024-005')->first()->id,
                'amount' => 940000000,
                'reason' => 'Khách hàng thay đổi kế hoạch mua xe',
                'status' => 'refunded',
                'processed_at' => now()->subDays(55),
                'meta' => [
                    'refund_method' => 'cash',
                    'processed_by' => 'admin@showroom.com',
                    'notes' => 'Hoàn tiền do hủy đơn hàng'
                ],
                'created_at' => now()->subDays(56),
                'updated_at' => now()->subDays(55)
            ]
        ];

        foreach ($refunds as $refund) {
            Refund::create($refund);
        }
    }
}
