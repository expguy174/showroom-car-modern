<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $carVariants = CarVariant::all();
        $accessories = Accessory::all();

        $orders = [
            // VIP Customer Order
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'order_number' => 'ORD-2024-001',
                'status' => 'delivered',
                'total_price' => 2400000000,
                'subtotal' => 2400000000,
                'tax_total' => 120000000,
                'discount_total' => 0,
                'shipping_fee' => 0,
                'grand_total' => 2520000000,
                'note' => 'Khách hàng VIP - ưu tiên cao',
                'payment_status' => 'paid',
                'paid_at' => now()->subDays(25),
                'source' => 'walk_in',
                'delivery_date' => now()->subDays(25),
                'delivery_address' => '123 Đường Lê Lợi, Tầng 5, Tòa nhà A, Hà Nội',
                'delivery_notes' => 'Giao xe tại showroom',
                'has_trade_in' => false,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(25)
            ],
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'order_number' => 'ORD-2024-002',
                'status' => 'confirmed',
                'total_price' => 1700000000,
                'subtotal' => 1700000000,
                'tax_total' => 85000000,
                'discount_total' => 15000000,
                'shipping_fee' => 0,
                'grand_total' => 1775000000,
                'note' => 'Đặt xe thứ 2 cho gia đình',
                'payment_status' => 'pending',
                'source' => 'website',
                'estimated_delivery' => now()->addDays(15),
                'delivery_address' => '123 Đường Lê Lợi, Tầng 5, Tòa nhà A, Hà Nội',
                'delivery_notes' => 'Giao xe tại nhà',
                'has_trade_in' => true,
                'trade_in_brand' => 'Mercedes-Benz',
                'trade_in_model' => 'C-Class',
                'trade_in_year' => 2020,
                'trade_in_value' => 1500000000,
                'trade_in_condition' => 'Tốt, đã sử dụng 3 năm',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2)
            ],

            // Regular Customer 1 Orders
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'order_number' => 'ORD-2024-003',
                'status' => 'delivered',
                'total_price' => 800000000,
                'subtotal' => 800000000,
                'tax_total' => 40000000,
                'discount_total' => 10000000,
                'shipping_fee' => 0,
                'grand_total' => 830000000,
                'note' => 'Xe gia đình, cần giao sớm',
                'payment_status' => 'paid',
                'paid_at' => now()->subDays(40),
                'source' => 'website',
                'delivery_date' => now()->subDays(40),
                'delivery_address' => '321 Đường Lý Thường Kiệt, Phường 2, Hà Nội',
                'delivery_notes' => 'Giao xe tại showroom',
                'has_trade_in' => false,
                'created_at' => now()->subDays(45),
                'updated_at' => now()->subDays(40)
            ],

            // Regular Customer 2 Orders
            [
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'order_number' => 'ORD-2024-004',
                'status' => 'pending',
                'total_price' => 1150000000,
                'subtotal' => 1150000000,
                'tax_total' => 57500000,
                'discount_total' => 0,
                'shipping_fee' => 0,
                'grand_total' => 1207500000,
                'note' => 'Cần xe đa dụng cho công việc',
                'payment_status' => 'pending',
                'source' => 'phone',
                'estimated_delivery' => now()->addDays(30),
                'delivery_address' => '987 Đường Điện Biên Phủ, Phường 3, Hà Nội',
                'delivery_notes' => 'Giao xe tại nhà',
                'has_trade_in' => true,
                'trade_in_brand' => 'Ford',
                'trade_in_model' => 'Ranger',
                'trade_in_year' => 2019,
                'trade_in_value' => 800000000,
                'trade_in_condition' => 'Tốt, đã sử dụng 4 năm',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(8)
            ],
            [
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'order_number' => 'ORD-2024-005',
                'status' => 'cancelled',
                'total_price' => 900000000,
                'subtotal' => 900000000,
                'tax_total' => 45000000,
                'discount_total' => 5000000,
                'shipping_fee' => 0,
                'grand_total' => 940000000,
                'note' => 'Hủy do thay đổi kế hoạch',
                'payment_status' => 'refunded',
                'source' => 'website',
                'cancelled_at' => now()->subDays(55),
                'cancellation_reason' => 'Khách hàng thay đổi kế hoạch mua xe',
                'delivery_address' => '987 Đường Điện Biên Phủ, Phường 3, Hà Nội',
                'has_trade_in' => false,
                'created_at' => now()->subDays(60),
                'updated_at' => now()->subDays(55)
            ]
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
