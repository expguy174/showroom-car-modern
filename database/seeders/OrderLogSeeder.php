<?php

namespace Database\Seeders;

use App\Models\OrderLog;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $users = User::all();

        $orderLogs = [
            // VIP Customer Order 1 Logs
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-001')->first()->id,
                'user_id' => $users->where('email', 'admin@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => null, 'to_status' => 'pending'],
                'message' => 'Đơn hàng được tạo bởi khách hàng VIP',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-001')->first()->id,
                'user_id' => $users->where('email', 'admin@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'pending', 'to_status' => 'confirmed'],
                'message' => 'Đơn hàng được xác nhận và chuyển sang xử lý',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-001')->first()->id,
                'user_id' => $users->where('email', 'admin@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'confirmed', 'to_status' => 'shipping'],
                'message' => 'Đơn hàng đang được chuẩn bị giao',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-001')->first()->id,
                'user_id' => $users->where('email', 'admin@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'shipping', 'to_status' => 'delivered'],
                'message' => 'Đơn hàng đã được giao thành công',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],

            // VIP Customer Order 2 Logs
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-002')->first()->id,
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => null, 'to_status' => 'pending'],
                'message' => 'Đơn hàng được tạo online bởi khách hàng VIP',
                'ip_address' => '203.162.1.50',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-002')->first()->id,
                'user_id' => $users->where('email', 'admin@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'pending', 'to_status' => 'confirmed'],
                'message' => 'Đơn hàng được xác nhận, chờ thanh toán',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],

            // Regular Customer 1 Order Logs
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => null, 'to_status' => 'pending'],
                'message' => 'Đơn hàng được tạo online',
                'ip_address' => '203.162.1.51',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'user_id' => $users->where('email', 'sales1@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'pending', 'to_status' => 'confirmed'],
                'message' => 'Đơn hàng được xác nhận bởi nhân viên bán hàng',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'user_id' => $users->where('email', 'sales1@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'confirmed', 'to_status' => 'shipping'],
                'message' => 'Đơn hàng đang được chuẩn bị giao',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'user_id' => $users->where('email', 'sales1@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'shipping', 'to_status' => 'delivered'],
                'message' => 'Đơn hàng đã được giao thành công',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],

            // Regular Customer 2 Order Logs
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-004')->first()->id,
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => null, 'to_status' => 'pending'],
                'message' => 'Đơn hàng được tạo qua điện thoại',
                'ip_address' => '203.162.1.52',
                'user_agent' => 'Mozilla/5.0 (Android 11; Mobile) AppleWebKit/537.36'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-004')->first()->id,
                'user_id' => $users->where('email', 'sales2@showroom.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'pending', 'to_status' => 'confirmed'],
                'message' => 'Đơn hàng được xác nhận, chờ thanh toán',
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],

            // Cancelled Order Logs
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-005')->first()->id,
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => null, 'to_status' => 'pending'],
                'message' => 'Đơn hàng được tạo online',
                'ip_address' => '203.162.1.52',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-005')->first()->id,
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'action' => 'status_changed',
                'details' => ['from_status' => 'pending', 'to_status' => 'cancelled'],
                'message' => 'Đơn hàng bị hủy do thay đổi kế hoạch',
                'ip_address' => '203.162.1.52',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
            ]
        ];

        foreach ($orderLogs as $orderLog) {
            OrderLog::create($orderLog);
        }
    }
}
