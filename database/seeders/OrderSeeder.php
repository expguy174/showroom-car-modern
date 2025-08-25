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
                'tracking_number' => 'TRK-2024-001',
                'ip_address' => '113.23.45.12',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125 Safari/537.36',
                'referrer' => 'https://zalo.me/oa/123456789',
                'delivery_date' => now()->subDays(25),
                'delivery_address' => '123 Đường Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                'delivery_notes' => 'Giao xe tại showroom, kiểm tra giấy tờ trước khi bàn giao',
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
                'tracking_number' => 'TRK-2024-002',
                'ip_address' => '14.169.88.201',
                'user_agent' => 'Mozilla/5.0 (Linux; Android 13; SM-S918B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125 Mobile Safari/537.36',
                'referrer' => 'https://www.facebook.com/?utm_source=fb_ads&utm_medium=cpc&utm_campaign=summer_sale',
                'estimated_delivery' => now()->addDays(15),
                'delivery_address' => '25 Ngô Quyền, Phường Hàng Bài, Quận Hoàn Kiếm, Hà Nội',
                'delivery_notes' => 'Giao xe tại nhà, hẹn giờ 9h sáng, gọi trước 30 phút',
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
                'tracking_number' => 'TRK-2024-003',
                'ip_address' => '113.176.97.33',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
                'referrer' => 'https://www.google.com/search?q=mua+xe+gia+%C4%91%C3%ACnh&utm_source=google&utm_medium=organic',
                'delivery_date' => now()->subDays(40),
                'delivery_address' => '321 Lý Thường Kiệt, Phường 7, Quận Tân Bình, TP. Hồ Chí Minh',
                'delivery_notes' => 'Giao xe tại showroom, dán film cách nhiệt trước khi giao',
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
                'tracking_number' => 'TRK-2024-004',
                'ip_address' => '171.224.55.78',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0',
                'referrer' => 'https://ads.shopee.vn/?utm_source=shopee&utm_medium=cpc&utm_campaign=auto',
                'estimated_delivery' => now()->addDays(30),
                'delivery_address' => '987 Điện Biên Phủ, Phường 25, Quận Bình Thạnh, TP. Hồ Chí Minh',
                'delivery_notes' => 'Giao xe tại nhà, cần bãi đỗ trước toà nhà',
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
                'tracking_number' => 'TRK-2024-005',
                'ip_address' => '27.67.120.14',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Safari/605.1.15',
                'referrer' => 'https://zalo.me/?utm_source=zalo&utm_medium=chat_share',
                'cancelled_at' => now()->subDays(55),
                'cancellation_reason' => 'Khách hàng thay đổi kế hoạch mua xe',
                'delivery_address' => '15 Cầu Giấy, Phường Quan Hoa, Quận Cầu Giấy, Hà Nội',
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
