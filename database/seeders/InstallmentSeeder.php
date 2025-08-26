<?php

namespace Database\Seeders;

use App\Models\Installment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class InstallmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $users = User::all();

        $installments = [
            // Regular Customer 1 Order Installments (12 months, 0% interest)
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'installment_number' => 1,
                'amount' => 830000000,
                'due_date' => now()->subDays(30),
                'bank_name' => 'Techcombank',
                'interest_rate' => 0.00,
                'tenure_months' => 12,
                'down_payment_amount' => 0,
                'monthly_payment_amount' => 69166667,
                'schedule' => [
                    'total_amount' => 830000000,
                    'monthly_payment' => 69166667,
                    'interest_rate' => 0.00,
                    'tenure_months' => 12
                ],
                'status' => 'paid',
                'paid_at' => now()->subDays(30),
                'approved_at' => now()->subDays(45),
                'cancelled_at' => null,
                'created_at' => now()->subDays(50),
                'updated_at' => now()->subDays(30)
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'installment_number' => 2,
                'amount' => 830000000,
                'due_date' => now()->subDays(0),
                'bank_name' => 'Techcombank',
                'interest_rate' => 0.00,
                'tenure_months' => 12,
                'down_payment_amount' => 0,
                'monthly_payment_amount' => 69166667,
                'schedule' => [
                    'total_amount' => 830000000,
                    'monthly_payment' => 69166667,
                    'interest_rate' => 0.00,
                    'tenure_months' => 12
                ],
                'status' => 'paid',
                'paid_at' => now()->subDays(0),
                'approved_at' => now()->subDays(45),
                'cancelled_at' => null,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(0)
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'installment_number' => 3,
                'amount' => 830000000,
                'due_date' => now()->addDays(30),
                'bank_name' => 'Techcombank',
                'interest_rate' => 0.00,
                'tenure_months' => 12,
                'down_payment_amount' => 0,
                'monthly_payment_amount' => 69166667,
                'schedule' => [
                    'total_amount' => 830000000,
                    'monthly_payment' => 69166667,
                    'interest_rate' => 0.00,
                    'tenure_months' => 12
                ],
                'status' => 'pending',
                'paid_at' => null,
                'approved_at' => now()->subDays(45),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1)
            ],

            // Regular Customer 2 Order Installments (24 months, 8.5% interest) - Pending approval
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-004')->first()->id,
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'installment_number' => 1,
                'amount' => 1207500000,
                'due_date' => now()->addDays(30),
                'bank_name' => 'BIDV',
                'interest_rate' => 8.50,
                'tenure_months' => 24,
                'down_payment_amount' => 0,
                'monthly_payment_amount' => 55320834,
                'schedule' => [
                    'total_amount' => 1207500000,
                    'monthly_payment' => 55320834,
                    'interest_rate' => 8.50,
                    'tenure_months' => 24
                ],
                'status' => 'pending',
                'paid_at' => null,
                'approved_at' => null,
                'cancelled_at' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1)
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-004')->first()->id,
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'installment_number' => 2,
                'amount' => 1207500000,
                'due_date' => now()->addDays(60),
                'bank_name' => 'BIDV',
                'interest_rate' => 8.50,
                'tenure_months' => 24,
                'down_payment_amount' => 0,
                'monthly_payment_amount' => 55320834,
                'schedule' => [
                    'total_amount' => 1207500000,
                    'monthly_payment' => 55320834,
                    'interest_rate' => 8.50,
                    'tenure_months' => 24
                ],
                'status' => 'pending',
                'paid_at' => null,
                'approved_at' => null,
                'cancelled_at' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(1)
            ]
        ];

        foreach ($installments as $installment) {
            Installment::create($installment);
        }
    }
}
