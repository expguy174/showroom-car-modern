<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarVariant;
use App\Models\CarVariantColor;

class CarVariantColorSeeder extends Seeder
{
    public function run(): void
    {
        $variants = CarVariant::all();
        foreach ($variants as $variant) {
            $colors = [
                [
                    'car_variant_id' => $variant->id,
                    'color_name' => 'Trắng',
                    'color_code' => 'WHT',
                    'hex_code' => '#FFFFFF',
                    'rgb_code' => '255,255,255',
                    'color_type' => 'solid',
                    'availability' => 'standard',
                    'price_adjustment' => 0,
                    'is_free' => true,
                    'description' => null,
                    'is_popular' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'color_name' => 'Bạc',
                    'color_code' => 'SIL',
                    'hex_code' => '#C0C0C0',
                    'rgb_code' => '192,192,192',
                    'color_type' => 'metallic',
                    'availability' => 'optional',
                    'price_adjustment' => 3000000,
                    'is_free' => false,
                    'description' => 'Sơn bạc metallic',
                    'is_popular' => true,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'color_name' => 'Đen',
                    'color_code' => 'BLK',
                    'hex_code' => '#000000',
                    'rgb_code' => '0,0,0',
                    'color_type' => 'solid',
                    'availability' => 'standard',
                    'price_adjustment' => 0,
                    'is_free' => true,
                    'description' => null,
                    'is_popular' => true,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'color_name' => 'Đỏ',
                    'color_code' => 'RED',
                    'hex_code' => '#C3002F',
                    'rgb_code' => '195,0,47',
                    'color_type' => 'metallic',
                    'availability' => 'optional',
                    'price_adjustment' => 5000000,
                    'is_free' => false,
                    'description' => 'Sơn đỏ ánh kim',
                    'is_popular' => false,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'color_name' => 'Xanh dương',
                    'color_code' => 'BLU',
                    'hex_code' => '#0047AB',
                    'rgb_code' => '0,71,171',
                    'color_type' => 'metallic',
                    'availability' => 'optional',
                    'price_adjustment' => 4000000,
                    'is_free' => false,
                    'description' => 'Sơn xanh ánh kim',
                    'is_popular' => false,
                    'is_active' => true,
                    'sort_order' => 4,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'color_name' => 'Xám',
                    'color_code' => 'GRY',
                    'hex_code' => '#808080',
                    'rgb_code' => '128,128,128',
                    'color_type' => 'solid',
                    'availability' => 'standard',
                    'price_adjustment' => 0,
                    'is_free' => true,
                    'description' => null,
                    'is_popular' => true,
                    'is_active' => true,
                    'sort_order' => 5,
                ],
            ];
            foreach ($colors as $c) {
                CarVariantColor::updateOrCreate([
                    'car_variant_id' => $c['car_variant_id'],
                    'color_name' => $c['color_name'],
                ], $c);
            }
        }
    }
}


