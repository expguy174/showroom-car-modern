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
        
        // Tạo payment transactions cho các orders đã completed
        // Điều này cần thiết để logic hoàn tiền hoạt động
        $completedOrders = Order::where('payment_status', 'completed')
            ->whereDoesntHave('paymentTransactions') // Chỉ tạo nếu chưa có
            ->get();
            
        echo "Tạo payment transactions cho " . $completedOrders->count() . " đơn hàng đã completed...\n";
        
        foreach ($completedOrders as $order) {
            $user = $order->user;
            $method = $order->paymentMethod ?? $methods->random();
            
            if (!$user || !$method) {
                echo "Bỏ qua order {$order->id} - thiếu user hoặc payment method\n";
                continue;
            }
            
            // Tạo transaction number unique
            $transactionNumber = 'TXN-' . ($order->order_number ?? $order->id) . '-' . time();
            
            $transaction = PaymentTransaction::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'payment_method_id' => $method->id,
                'transaction_number' => $transactionNumber,
                'amount' => $order->grand_total,
                'currency' => 'VND',
                'status' => 'completed', // Quan trọng: phải là 'completed' để có thể refund
                'payment_date' => $order->created_at->addMinutes(rand(5, 60)), // Thanh toán sau khi tạo order
                'notes' => 'Thanh toán đơn hàng (seeded data)',
            ]);
            
            echo "Tạo transaction {$transaction->transaction_number} cho order {$order->order_number}\n";
        }
        
        // Tạo một số transactions với status khác để test
        $pendingOrders = Order::where('payment_status', 'pending')
            ->whereDoesntHave('paymentTransactions')
            ->limit(10)
            ->get();
            
        foreach ($pendingOrders as $order) {
            $user = $order->user;
            $method = $order->paymentMethod ?? $methods->random();
            
            if (!$user || !$method) continue;
            
            PaymentTransaction::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'payment_method_id' => $method->id,
                'transaction_number' => 'TXN-' . ($order->order_number ?? $order->id) . '-PENDING-' . time(),
                'amount' => $order->grand_total,
                'currency' => 'VND',
                'status' => 'pending',
                'payment_date' => null,
                'notes' => 'Thanh toán đang chờ xử lý (seeded data)',
            ]);
        }
        
        echo "Hoàn thành tạo payment transactions!\n";
    }
}


