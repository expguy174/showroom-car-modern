<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accessory;
use Illuminate\Support\Str;

class AccessorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Thảm lót sàn cao su',
                'sku' => 'ACC-FLR-' . Str::upper(Str::random(5)),
                'description' => 'Thảm lót sàn cao su chống trơn trượt, dễ vệ sinh.',
                'short_description' => 'Thảm lót sàn cao su',
                'category' => 'interior',
                'subcategory' => 'floor_mat',
                'compatible_car_brands' => json_encode(['Toyota','Hyundai','VinFast']),
                'compatible_car_models' => null,
                'compatible_car_years' => null,
                'base_price' => 550000,
                'current_price' => 450000,
                'is_on_sale' => true,
                'sale_start_date' => now()->subDays(7)->toDateString(),
                'sale_end_date' => now()->addDays(7)->toDateString(),
                'stock_quantity' => 50,
                'stock_status' => 'in_stock',
                'gallery' => json_encode([
                    'https://placehold.co/1200x800?text=' . urlencode('Tham lot san'),
                    'https://placehold.co/1200x800?text=Detail+' . urlencode('Tham lot san'),
                    'https://placehold.co/1200x800?text=Usage+' . urlencode('Tham lot san'),
                    'https://placehold.co/1200x800?text=Package+' . urlencode('Tham lot san'),
                    'https://placehold.co/1200x800?text=Install+' . urlencode('Tham lot san'),
                    'https://placehold.co/1200x800?text=Material+' . urlencode('Tham lot san'),
                ]),
                'specifications' => null,
                'features' => null,
                'installation_instructions' => null,
                'warranty_info' => 'Bảo hành 6 tháng',
                'warranty_months' => 6,
                'slug' => 'tham-lot-san-cao-su',
                'meta_title' => 'Thảm lót sàn cao su',
                'meta_description' => 'Thảm lót sàn cao su cho xe ô tô',
                'meta_keywords' => 'tham lot san, phu kien noi that',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_popular' => true,
                'sort_order' => 1,
                'is_active' => true,
                'installation_service_available' => false,
                'installation_fee' => null,
                'installation_requirements' => null,
                'installation_time_minutes' => 0,
                'warranty_terms' => 'Đổi trả trong 7 ngày nếu lỗi.',
                'warranty_contact' => null,
                'return_policy' => 'Hỗ trợ trả hàng nếu chưa sử dụng.',
                'support_contact' => null,
                'return_policy_days' => 7,
                'weight' => 2.5,
                'dimensions' => '60x45x2 cm',
                'material' => 'Cao su',
                'color_options' => json_encode(['Đen','Be']),
                'is_new_arrival' => false,
            ],
            [
                'name' => 'Camera hành trình 2K',
                'sku' => 'ACC-DASH-' . Str::upper(Str::random(5)),
                'description' => 'Camera hành trình 2K, góc rộng, hỗ trợ ghi hình ban đêm.',
                'short_description' => 'Camera hành trình 2K',
                'category' => 'electronics',
                'subcategory' => 'dash_cam',
                'compatible_car_brands' => json_encode(['Toyota','Hyundai','VinFast']),
                'compatible_car_models' => null,
                'compatible_car_years' => null,
                'base_price' => 2500000,
                'current_price' => 2200000,
                'is_on_sale' => true,
                'sale_start_date' => now()->subDays(3)->toDateString(),
                'sale_end_date' => now()->addDays(14)->toDateString(),
                'stock_quantity' => 30,
                'stock_status' => 'in_stock',
                'gallery' => json_encode([
                    'https://placehold.co/1200x800?text=' . urlencode('Camera hanh trinh'),
                    'https://placehold.co/1200x800?text=Detail+' . urlencode('Camera 2K'),
                    'https://placehold.co/1200x800?text=Mounted',
                    'https://placehold.co/1200x800?text=Night+Vision',
                    'https://placehold.co/1200x800?text=App+Control',
                    'https://placehold.co/1200x800?text=Box+Package',
                ]),
                'specifications' => null,
                'features' => null,
                'installation_instructions' => 'Cắm nguồn tẩu 12V, dán kính lái.',
                'warranty_info' => 'Bảo hành 12 tháng',
                'warranty_months' => 12,
                'slug' => 'camera-hanh-trinh-2k',
                'meta_title' => 'Camera hành trình 2K',
                'meta_description' => 'Camera hành trình chất lượng 2K',
                'meta_keywords' => 'camera hanh trinh, dash cam',
                'is_featured' => true,
                'is_bestseller' => false,
                'is_popular' => true,
                'sort_order' => 2,
                'is_active' => true,
                'installation_service_available' => true,
                'installation_fee' => 100000,
                'installation_requirements' => null,
                'installation_time_minutes' => 30,
                'warranty_terms' => null,
                'warranty_contact' => null,
                'return_policy' => null,
                'support_contact' => null,
                'return_policy_days' => null,
                'weight' => 0.3,
                'dimensions' => '10x6x4 cm',
                'material' => 'Nhựa',
                'color_options' => json_encode(['Đen']),
                'is_new_arrival' => true,
            ],
        ];

        foreach ($items as $data) {
            Accessory::updateOrCreate(['slug' => $data['slug']], $data);
        }

        // Thêm nhiều phụ kiện ngẫu nhiên
        $categories = [
            // an toàn
            ['category' => 'safety', 'subcategory' => 'dash_cam'],
            ['category' => 'safety', 'subcategory' => 'tire_pressure_monitor'],
            ['category' => 'safety', 'subcategory' => 'blind_spot_mirror'],
            // tiện ích
            ['category' => 'utility', 'subcategory' => 'phone_holder'],
            ['category' => 'utility', 'subcategory' => 'trunk_organizer'],
            ['category' => 'utility', 'subcategory' => 'seat_back_hook'],
            // chăm sóc xe
            ['category' => 'car_care', 'subcategory' => 'shampoo'],
            ['category' => 'car_care', 'subcategory' => 'wax'],
            ['category' => 'car_care', 'subcategory' => 'microfiber_towel'],
            // nội thất/ngoại thất/electronics thêm
            ['category' => 'interior', 'subcategory' => 'seat_cover'],
            ['category' => 'interior', 'subcategory' => 'sunshade'],
            ['category' => 'exterior', 'subcategory' => 'spoiler'],
            ['category' => 'electronics', 'subcategory' => 'charger'],
        ];
        for ($i = 1; $i <= 30; $i++) {
            $cat = $categories[array_rand($categories)];
            Accessory::updateOrCreate(['slug' => 'phu-kien-' . $i], [
                'name' => 'Phụ kiện #' . $i,
                'sku' => 'ACC-PK' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'description' => 'Phụ kiện số ' . $i . ' chất lượng cho xe.',
                'short_description' => 'Phụ kiện #' . $i,
                'category' => $cat['category'],
                'subcategory' => $cat['subcategory'],
                'compatible_car_brands' => json_encode(['Toyota','Hyundai','Kia']),
                'compatible_car_models' => null,
                'compatible_car_years' => null,
                'base_price' => (200 + ($i * 50)) * 1000,
                'current_price' => (150 + ($i * 40)) * 1000,
                'is_on_sale' => ($i % 3 == 0),
                'sale_start_date' => null,
                'sale_end_date' => null,
                'stock_quantity' => 50 + ($i * 5),
                'stock_status' => 'in_stock',
                'gallery' => json_encode([
                    'https://placehold.co/1200x800?text=' . urlencode('Phu kien #' . $i),
                    'https://placehold.co/1200x800?text=Detail+' . urlencode('Phu kien #' . $i),
                    'https://placehold.co/1200x800?text=Packaging+' . urlencode('#' . $i),
                    'https://placehold.co/1200x800?text=Usage+' . urlencode('Phu kien #' . $i),
                    'https://placehold.co/1200x800?text=Install+' . urlencode('Phu kien #' . $i),
                    'https://placehold.co/1200x800?text=Quality+' . urlencode('#' . $i),
                ]),
                'specifications' => null,
                'features' => null,
                'installation_instructions' => null,
                'warranty_info' => 'Bảo hành 6 tháng',
                'warranty_months' => 6,
                'slug' => 'phu-kien-' . $i,
                'meta_title' => 'Phụ kiện #' . $i,
                'meta_description' => 'Phụ kiện cho xe #' . $i,
                'meta_keywords' => 'phu kien, xe',
                'is_featured' => ($i <= 5), // First 5 are featured
                'is_bestseller' => ($i % 5 == 0), // Every 5th is bestseller
                'is_popular' => ($i <= 10), // First 10 are popular
                'sort_order' => $i + 2,
                'is_active' => true,
                'installation_service_available' => ($i % 2 == 0), // Every 2nd has installation
                'installation_fee' => ($i % 2 == 0) ? (50 + ($i * 10)) * 1000 : null,
                'installation_requirements' => null,
                'installation_time_minutes' => 30 + ($i * 2),
                'warranty_terms' => null,
                'warranty_contact' => null,
                'return_policy' => 'Trả hàng trong 7 ngày nếu chưa sử dụng',
                'support_contact' => null,
                'return_policy_days' => 7,
                'weight' => 1.0 + ($i * 0.5),
                'dimensions' => (20 + $i) . 'x' . (15 + $i) . 'x' . (5 + ($i % 10)) . ' cm',
                'material' => 'Nhựa',
                'color_options' => json_encode(['Đen','Bạc','Đỏ']),
                'is_new_arrival' => ($i <= 3), // First 3 are new arrivals
            ]);
        }
    }
}


