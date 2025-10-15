<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promotion;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        // Base promotions (existing ones)
        $basePromos = [
            [
                'name' => 'Ưu đãi tháng này',
                'code' => 'SALE-THIS-MONTH',
                'description' => 'Giảm 10 triệu cho đơn hàng từ 300 triệu trở lên.',
                'type' => 'fixed_amount',
                'discount_value' => 10000000,
                'min_order_amount' => 300000000,
                'max_discount_amount' => null, // Fixed amount không cần max
                'usage_limit' => 100,
                'usage_count' => rand(0, 20),
                'start_date' => now()->startOfMonth(),
                'end_date' => now()->endOfMonth(),
                'is_active' => true,
            ],
            [
                'name' => 'Freeship nội thành',
                'code' => 'FREESHIP-HCM',
                'description' => 'Miễn phí giao xe tiêu chuẩn nội thành TPHCM.',
                'type' => 'free_shipping',
                'discount_value' => 0,
                'min_order_amount' => 0,
                'max_discount_amount' => null, // Free shipping không cần max
                'usage_limit' => null,
                'usage_count' => rand(50, 200),
                'start_date' => now()->subDays(3),
                'end_date' => now()->addDays(27),
                'is_active' => true,
            ],
            [
                'name' => 'Giảm 5% cho thương hiệu Toyota',
                'code' => 'BRAND-TOYOTA-5',
                'description' => 'Giảm 5% cho tất cả xe Toyota trong tháng này.',
                'type' => 'brand_specific',
                'discount_value' => 5,
                'min_order_amount' => 0,
                'max_discount_amount' => 15000000, // Tối đa 15 triệu
                'usage_limit' => null,
                'usage_count' => rand(10, 50),
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(25),
                'is_active' => true,
            ],
            [
                'name' => 'Giảm giá 1 triệu',
                'code' => 'GIFT-ACC-1M',
                'description' => 'Giảm 1.000.000đ cho đơn hàng từ 50 triệu trở lên.',
                'type' => 'fixed_amount',
                'discount_value' => 1000000,
                'min_order_amount' => 50000000,
                'max_discount_amount' => null, // Fixed amount không cần max
                'usage_limit' => 200,
                'usage_count' => rand(5, 30),
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(45),
                'is_active' => true,
            ],
            [
                'name' => 'Freeship nhanh toàn quốc',
                'code' => 'FREESHIP-EXPRESS',
                'description' => 'Miễn phí giao hàng nhanh toàn quốc cho đơn từ 100 triệu.',
                'type' => 'free_shipping',
                'discount_value' => 0,
                'min_order_amount' => 100000000,
                'max_discount_amount' => null, // Free shipping không cần max
                'usage_limit' => 50,
                'usage_count' => rand(5, 20),
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'name' => 'Voucher bảo dưỡng 0đ giao hàng',
                'code' => 'FREE-MAINT-SHIP',
                'description' => 'Miễn phí giao nhận xe tiêu chuẩn cho khách dùng gói bảo dưỡng.',
                'type' => 'free_shipping',
                'discount_value' => 0,
                'min_order_amount' => 0,
                'max_discount_amount' => null, // Free shipping không cần max
                'usage_limit' => null,
                'usage_count' => rand(20, 80),
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(60),
                'is_active' => true,
            ],
        ];

        // Generate additional promotions for testing all types
        $additionalPromos = [];
        
        // Use actual brands from CarBrandSeeder
        $brands = [
            'Toyota', 'Hyundai', 'VinFast', 'Honda', 'Mazda', 'Kia', 
            'Mitsubishi', 'Ford', 'BMW', 'Mercedes-Benz', 'Nissan', 
            'Peugeot', 'Subaru', 'Lexus', 'Audi', 'Volkswagen', 'Suzuki', 'MG'
        ];
        $months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'];
        
        // Simplified promotion types - only practical ones
        $allTypes = [
            'percentage', 'fixed_amount', 'free_shipping', 'brand_specific'
        ];

        // Create more promotions for each type to test pagination
        foreach ($allTypes as $typeIndex => $type) {
            for ($j = 1; $j <= 8; $j++) { // 8 promotions per type = 32 total
                $i = ($typeIndex * 8) + $j;
                $brand = $brands[array_rand($brands)];
                $month = $months[array_rand($months)];
                
                // Mix different statuses for testing filters
                $isActive = ($j <= 6); // First 6 are active, last 2 are inactive
                $hasExpired = ($j == 7); // 7th promotion is expired
            
                // Generate different content based on type
                switch ($type) {
                    case 'percentage':
                        $discountValue = rand(5, 15);
                        $name = "Giảm {$discountValue}% tất cả sản phẩm";
                        $description = "Ưu đãi giảm {$discountValue}% cho tất cả sản phẩm trong cửa hàng.";
                        $minOrder = rand(0, 1) ? rand(20, 100) * 1000000 : 0;
                        break;
                        
                    case 'fixed_amount':
                        $discountValue = rand(5, 20) * 100000;
                        $name = "Giảm " . number_format($discountValue, 0, ',', '.') . "đ cho đơn hàng";
                        $description = "Ưu đãi giảm cố định " . number_format($discountValue, 0, ',', '.') . "đ cho đơn hàng.";
                        $minOrder = rand(30, 200) * 1000000;
                        break;
                        
                    case 'free_shipping':
                        $discountValue = 0;
                        $shippingTypes = [
                            ['name' => 'Miễn phí giao hàng tiêu chuẩn', 'desc' => 'Miễn phí vận chuyển tiêu chuẩn cho tất cả đơn hàng.'],
                            ['name' => 'Miễn phí giao hàng nhanh', 'desc' => 'Miễn phí vận chuyển nhanh cho đơn hàng.']
                        ];
                        $shipping = $shippingTypes[array_rand($shippingTypes)];
                        $name = $shipping['name'];
                        $description = $shipping['desc'];
                        $minOrder = rand(0, 1) ? rand(50, 200) * 1000000 : 0;
                        break;
                        
                    case 'brand_specific':
                        $discountValue = rand(3, 12);
                        $name = "Thương hiệu {$brand} giảm {$discountValue}%";
                        $description = "Tất cả sản phẩm thương hiệu {$brand} giảm {$discountValue}%.";
                        $minOrder = rand(20, 100) * 1000000;
                        break;
                        
                    default:
                        $discountValue = rand(5, 15);
                        $name = "Khuyến mãi {$brand}";
                        $description = "Ưu đãi đặc biệt cho {$brand}.";
                        $minOrder = 0;
                }

                // Generate appropriate code based on type
                if ($type === 'brand_specific') {
                    $code = strtoupper(str_replace(['-', ' '], '', $brand)) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                } else {
                    $typePrefix = [
                        'percentage' => 'SALE',
                        'fixed_amount' => 'DISCOUNT',
                        'free_shipping' => 'FREESHIP'
                    ];
                    $code = ($typePrefix[$type] ?? 'PROMO') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                }
                
                // Set max_discount_amount based on type
                $maxDiscountAmount = null;
                if ($type === 'percentage') {
                    $maxDiscountAmount = rand(5, 20) * 1000000; // 5-20 triệu cho percentage
                } elseif ($type === 'brand_specific') {
                    $maxDiscountAmount = rand(10, 30) * 1000000; // 10-30 triệu cho brand specific
                }
                // fixed_amount và free_shipping không cần max_discount_amount
                
                $additionalPromos[] = [
                    'name' => $name,
                    'code' => $code,
                    'description' => $description,
                    'type' => $type,
                    'discount_value' => $discountValue,
                    'min_order_amount' => $minOrder,
                    'max_discount_amount' => $maxDiscountAmount,
                    'usage_limit' => rand(0, 1) ? rand(50, 500) : null,
                    'usage_count' => rand(0, 30),
                    'start_date' => $hasExpired ? now()->subDays(rand(30, 60)) : now()->subDays(rand(1, 10)),
                    'end_date' => $hasExpired ? now()->subDays(rand(1, 15)) : now()->addDays(rand(15, 90)),
                    'is_active' => $isActive,
                ];
            }
        }

        // Merge and create all promotions
        $allPromos = array_merge($basePromos, $additionalPromos);
        
        foreach ($allPromos as $p) {
            Promotion::updateOrCreate(['code' => $p['code']], $p);
        }
        
        echo "Created " . count($allPromos) . " promotions for testing.\n";
        echo "Active promotions: " . collect($allPromos)->where('is_active', true)->count() . "\n";
        echo "Types created: " . collect($allPromos)->pluck('type')->unique()->count() . " different types\n";
        echo "Types: " . collect($allPromos)->pluck('type')->unique()->implode(', ') . "\n";
    }
}


