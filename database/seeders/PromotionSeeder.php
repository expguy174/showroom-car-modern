<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = [
            [
                'name' => 'Khuyến mãi mùa hè - Giảm giá lên đến 20%',
                'code' => 'SUMMER2024',
                'description' => 'Chương trình khuyến mãi đặc biệt cho mùa hè với mức giảm giá lên đến 20% cho tất cả các dòng xe',
                'type' => 'percentage',
                'discount_value' => 20,
                'min_order_amount' => 500000000,
                'usage_limit' => 100,
                'usage_count' => 25,
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(30),
                'is_active' => true,
                'created_at' => now()->subDays(35),
                'updated_at' => now()->subDays(5)
            ],
            [
                'name' => 'Giảm giá 50 triệu cho xe cao cấp',
                'code' => 'LUXURY50M',
                'description' => 'Giảm giá trực tiếp 50 triệu đồng cho các dòng xe cao cấp từ 1 tỷ đồng trở lên',
                'type' => 'fixed_amount',
                'discount_value' => 50000000,
                'min_order_amount' => 1000000000,
                'usage_limit' => 50,
                'usage_count' => 10,
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(45),
                'is_active' => true,
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(2)
            ],
            [
                'name' => 'Miễn phí vận chuyển toàn quốc',
                'code' => 'FREESHIP',
                'description' => 'Miễn phí vận chuyển xe từ showroom đến nhà khách hàng trên toàn quốc',
                'type' => 'free_shipping',
                'discount_value' => 0,
                'min_order_amount' => 300000000,
                'usage_limit' => 200,
                'usage_count' => 45,
                'start_date' => now()->subDays(10),
                'end_date' => now()->addDays(20),
                'is_active' => true,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(3)
            ],
            [
                'name' => 'Khuyến mãi xe gia đình - Giảm 15%',
                'code' => 'FAMILY15',
                'description' => 'Giảm giá 15% cho các dòng xe gia đình phổ biến',
                'type' => 'percentage',
                'discount_value' => 15,
                'min_order_amount' => 400000000,
                'usage_limit' => 150,
                'usage_count' => 30,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(25),
                'is_active' => true,
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(1)
            ],
            [
                'name' => 'Giảm giá 30 triệu cho xe điện',
                'code' => 'ELECTRIC30M',
                'description' => 'Hỗ trợ mua xe điện với mức giảm giá 30 triệu đồng',
                'type' => 'fixed_amount',
                'discount_value' => 30000000,
                'min_order_amount' => 800000000,
                'usage_limit' => 80,
                'usage_count' => 15,
                'start_date' => now()->subDays(20),
                'end_date' => now()->addDays(40),
                'is_active' => true,
                'created_at' => now()->subDays(25),
                'updated_at' => now()->subDays(4)
            ]
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}
