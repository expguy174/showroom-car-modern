<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promos = [
            [
                'name' => 'Ưu đãi tháng này',
                'code' => 'SALE-THIS-MONTH',
                'description' => 'Giảm 10 triệu cho xe sedan hạng B.',
                'type' => 'fixed_amount',
                'discount_value' => 10000000,
                'min_order_amount' => 300000000,
                'usage_limit' => 100,
                'usage_count' => 0,
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'is_active' => true,
            ],
            [
                'name' => 'Freeship nội thành',
                'code' => 'FREESHIP-HCM',
                'description' => 'Miễn phí giao xe nội thành TPHCM.',
                'type' => 'free_shipping',
                'discount_value' => 0,
                'min_order_amount' => 0,
                'usage_limit' => null,
                'usage_count' => 0,
                'start_date' => now()->subDays(3),
                'end_date' => now()->addDays(27),
                'is_active' => true,
            ],
            [
                'name' => 'Giảm 5% cho thương hiệu Toyota',
                'code' => 'BRAND-TOYOTA-5',
                'description' => 'Giảm 5% cho tất cả xe Toyota trong tháng này.',
                'type' => 'percentage',
                'discount_value' => 5,
                'min_order_amount' => 0,
                'usage_limit' => null,
                'usage_count' => 0,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'name' => 'Quà tặng phụ kiện 1 triệu',
                'code' => 'GIFT-ACC-1M',
                'description' => 'Tặng phụ kiện trị giá 1.000.000đ cho đơn mua xe.',
                'type' => 'fixed_amount',
                'discount_value' => 1000000,
                'min_order_amount' => 500000000,
                'usage_limit' => 200,
                'usage_count' => 0,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(45),
                'is_active' => true,
            ],
            [
                'name' => 'Voucher bảo dưỡng 0đ giao hàng (free ship)',
                'code' => 'FREE-MAINT-SHIP',
                'description' => 'Miễn phí giao nhận xe cho khách dùng gói bảo dưỡng.',
                'type' => 'free_shipping',
                'discount_value' => 0,
                'min_order_amount' => 0,
                'usage_limit' => null,
                'usage_count' => 0,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(60),
                'is_active' => true,
            ],
        ];

        foreach ($promos as $p) {
            Promotion::updateOrCreate(['code' => $p['code']], $p);
        }
    }
}


