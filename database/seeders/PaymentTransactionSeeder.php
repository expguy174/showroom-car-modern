<?php

namespace Database\Seeders;

use App\Models\PaymentTransaction;
use App\Models\Order;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $users = User::all();
        $paymentMethods = PaymentMethod::all();

        $paymentTransactions = [
            // VIP Customer Order 1 Payment
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-001')->first()->id,
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'payment_method_id' => $paymentMethods->where('code', 'CASH')->first()->id,
                'transaction_number' => 'TXN-2024-001',
                'amount' => 2520000000,
                'currency' => 'VND',
                'status' => 'completed',
                'payment_date' => now()->subDays(25),
                'notes' => 'Thanh toán tiền mặt tại showroom'
            ],

            // VIP Customer Order 2 Payment
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-002')->first()->id,
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'payment_method_id' => $paymentMethods->where('code', 'BANK_TRANSFER')->first()->id,
                'transaction_number' => 'TXN-2024-002',
                'amount' => 1775000000,
                'currency' => 'VND',
                'status' => 'pending',
                'payment_date' => null,
                'notes' => 'Khách chuyển khoản qua Vietcombank, đính kèm UTR sau khi duyệt'
            ],

            // Regular Customer 1 Order Payment
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'payment_method_id' => $paymentMethods->where('code', 'INSTALLMENT_0')->first()->id,
                'transaction_number' => 'TXN-2024-003',
                'amount' => 830000000,
                'currency' => 'VND',
                'status' => 'completed',
                'payment_date' => now()->subDays(40),
                'notes' => 'Thanh toán trả góp 12 tháng'
            ],

            // Regular Customer 2 Order Payment
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-004')->first()->id,
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'payment_method_id' => $paymentMethods->where('code', 'INSTALLMENT_NORMAL')->first()->id,
                'transaction_number' => 'TXN-2024-004',
                'amount' => 1207500000,
                'currency' => 'VND',
                'status' => 'pending',
                'payment_date' => null,
                'notes' => 'Chờ duyệt hồ sơ vay VPBank, giải ngân trong 1-2 ngày làm việc'
            ],

            // Cancelled Order Payment
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-005')->first()->id,
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'payment_method_id' => $paymentMethods->where('code', 'CASH')->first()->id,
                'transaction_number' => 'TXN-2024-005',
                'amount' => 940000000,
                'currency' => 'VND',
                'status' => 'cancelled',
                'payment_date' => now()->subDays(60),
                'notes' => 'Hoàn tiền do hủy đơn hàng'
            ]
        ];

        foreach ($paymentTransactions as $paymentTransaction) {
            PaymentTransaction::create($paymentTransaction);
        }
    }
}
