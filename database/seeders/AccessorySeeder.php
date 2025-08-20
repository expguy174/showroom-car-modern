<?php

namespace Database\Seeders;

use App\Models\Accessory;
use Illuminate\Database\Seeder;

class AccessorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $searchCommons = function (string $query) {
            return null; // Always use placeholder
        };

        $accessories = [
            [
                'name' => 'Bọc ghế da cao cấp',
                'slug' => 'boc-ghe-da-cao-cap',
                'code' => 'ACC-INT-001',
                'sku' => 'LEATHER-SEAT-001',
                'description' => 'Bọc ghế da cao cấp với chất liệu da thật, tăng tính thẩm mỹ và sự thoải mái',
                'short_description' => 'Bọc ghế da cao cấp, chất liệu da thật',
                'category' => 'Nội thất',
                'brand' => 'Premium Leather',
                'price' => 5000000,
                'original_price' => 6000000,
                'is_on_sale' => true,
                'sale_price' => 5000000,
                'stock_quantity' => 20,
                'is_active' => true,
                'is_featured' => true,
                'is_available' => true,
                'is_popular' => true,
                'is_bestseller' => true,
                // Leather seats query ensures relevant image
                'main_image_path' => $searchCommons('car leather seat interior') ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Bọc ghế da'),
                'sort_order' => 1,
                'view_count' => 150,
                'average_rating' => 4.5,
                'rating_count' => 89,
                'meta_title' => 'Bọc ghế da cao cấp - Giá từ 5 triệu',
                'meta_description' => 'Bọc ghế da cao cấp với chất liệu da thật, tăng tính thẩm mỹ và sự thoải mái',
                'meta_keywords' => 'bọc ghế da, ghế da cao cấp, nội thất xe'
            ],
            [
                'name' => 'Điều hòa không khí',
                'slug' => 'dieu-hoa-khong-khi',
                'code' => 'ACC-INT-002',
                'sku' => 'AC-SYSTEM-001',
                'description' => 'Hệ thống điều hòa không khí hiện đại, tiết kiệm nhiên liệu',
                'short_description' => 'Điều hòa không khí hiện đại',
                'category' => 'Hệ thống',
                'brand' => 'CoolTech',
                'price' => 8000000,
                'original_price' => 9000000,
                'is_on_sale' => true,
                'sale_price' => 8000000,
                'stock_quantity' => 15,
                'is_active' => true,
                'is_featured' => true,
                'is_available' => true,
                'is_popular' => true,
                'is_bestseller' => false,
                'main_image_path' => $searchCommons('car air conditioner vent') ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Điều hòa không khí'),
                'sort_order' => 2,
                'view_count' => 120,
                'average_rating' => 4.3,
                'rating_count' => 67,
                'meta_title' => 'Điều hòa không khí - Giá từ 8 triệu',
                'meta_description' => 'Hệ thống điều hòa không khí hiện đại, tiết kiệm nhiên liệu',
                'meta_keywords' => 'điều hòa xe, hệ thống làm mát, tiện nghi xe'
            ],
            [
                'name' => 'Hệ thống âm thanh cao cấp',
                'slug' => 'he-thong-am-thanh-cao-cap',
                'code' => 'ACC-ENT-001',
                'sku' => 'AUDIO-SYS-001',
                'description' => 'Hệ thống âm thanh cao cấp với loa subwoofer và amplifier',
                'short_description' => 'Hệ thống âm thanh cao cấp',
                'category' => 'Giải trí',
                'brand' => 'SoundMaster',
                'price' => 12000000,
                'original_price' => 15000000,
                'is_on_sale' => true,
                'sale_price' => 12000000,
                'stock_quantity' => 10,
                'is_active' => true,
                'is_featured' => true,
                'is_available' => true,
                'is_popular' => false,
                'is_bestseller' => false,
                'main_image_path' => $searchCommons('car audio speaker subwoofer interior') ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Âm thanh cao cấp'),
                'sort_order' => 3,
                'view_count' => 80,
                'average_rating' => 4.7,
                'rating_count' => 45,
                'meta_title' => 'Hệ thống âm thanh cao cấp - Giá từ 12 triệu',
                'meta_description' => 'Hệ thống âm thanh cao cấp với loa subwoofer và amplifier',
                'meta_keywords' => 'âm thanh xe, loa xe, giải trí xe'
            ],
            [
                'name' => 'Camera hành trình',
                'slug' => 'camera-hanh-trinh',
                'code' => 'ACC-TECH-001',
                'sku' => 'DASH-CAM-001',
                'description' => 'Camera hành trình HD với GPS và cảm biến va chạm',
                'short_description' => 'Camera hành trình HD',
                'category' => 'Công nghệ',
                'brand' => 'TechVision',
                'price' => 3000000,
                'original_price' => 3500000,
                'is_on_sale' => true,
                'sale_price' => 3000000,
                'stock_quantity' => 50,
                'is_active' => true,
                'is_featured' => false,
                'is_available' => true,
                'is_popular' => true,
                'is_bestseller' => true,
                'main_image_path' => $searchCommons('dashcam car camera interior') ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Camera hành trình'),
                'sort_order' => 4,
                'view_count' => 200,
                'average_rating' => 4.4,
                'rating_count' => 156,
                'meta_title' => 'Camera hành trình - Giá từ 3 triệu',
                'meta_description' => 'Camera hành trình HD với GPS và cảm biến va chạm',
                'meta_keywords' => 'camera hành trình, dash cam, an toàn xe'
            ],
            [
                'name' => 'Lốp xe cao cấp',
                'slug' => 'lop-xe-cao-cap',
                'code' => 'ACC-EXT-001',
                'sku' => 'TIRE-PREM-001',
                'description' => 'Lốp xe cao cấp với độ bám đường tốt và tuổi thọ cao',
                'short_description' => 'Lốp xe cao cấp',
                'category' => 'Ngoại thất',
                'brand' => 'TireMax',
                'price' => 4000000,
                'original_price' => 4500000,
                'is_on_sale' => true,
                'sale_price' => 4000000,
                'stock_quantity' => 100,
                'is_active' => true,
                'is_featured' => false,
                'is_available' => true,
                'is_popular' => true,
                'is_bestseller' => false,
                'main_image_path' => $searchCommons('car tire wheel close-up') ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Lốp xe cao cấp'),
                'sort_order' => 5,
                'view_count' => 180,
                'average_rating' => 4.6,
                'rating_count' => 234,
                'meta_title' => 'Lốp xe cao cấp - Giá từ 4 triệu',
                'meta_description' => 'Lốp xe cao cấp với độ bám đường tốt và tuổi thọ cao',
                'meta_keywords' => 'lốp xe, lốp cao cấp, an toàn xe'
            ]
        ];

        // Force placeholder image with background color and the product name only
        foreach ($accessories as &$acc) {
            $acc['main_image_path'] = 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode($acc['name']);
        }
        unset($acc);

        foreach ($accessories as $accessory) {
            Accessory::create($accessory);
        }

        // Guarantee images for featured accessories using stable placeholders
        $featured = Accessory::where('is_active', 1)->where('is_featured', 1)->get();
        foreach ($featured as $acc) {
            if (empty($acc->main_image_path)) {
                $term = $acc->name ?: ($acc->category ?: 'Phụ kiện xe');
                $acc->main_image_path = 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode($term);
                $acc->save();
            }
        }
    }
}
