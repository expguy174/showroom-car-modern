<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarVariant;
use App\Models\CarVariantImage;
use App\Models\CarVariantColor;

class CarVariantImageSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CarVariant::all() as $variant) {
            $images = [
                // Gallery images
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Gallery+1+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Gallery 1',
                    'title' => $variant->name . ' - Gallery Image 1',
                    'description' => 'Ảnh gallery chính của ' . $variant->name,
                    'image_type' => 'gallery',
                    'angle' => 'front',
                    'is_main' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Gallery+2+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Gallery 2',
                    'title' => $variant->name . ' - Gallery Image 2',
                    'description' => 'Ảnh gallery thứ 2 của ' . $variant->name,
                    'image_type' => 'gallery',
                    'angle' => 'side',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Gallery+3+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Gallery 3',
                    'title' => $variant->name . ' - Gallery Image 3',
                    'description' => 'Ảnh gallery thứ 3 của ' . $variant->name,
                    'image_type' => 'gallery',
                    'angle' => 'rear',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                
                // Exterior images
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Exterior+Front+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Exterior Front',
                    'title' => $variant->name . ' - Ngoại thất phía trước',
                    'description' => 'Ảnh ngoại thất phía trước của ' . $variant->name,
                    'image_type' => 'exterior',
                    'angle' => 'front',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 10,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Exterior+Side+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Exterior Side',
                    'title' => $variant->name . ' - Ngoại thất bên hông',
                    'description' => 'Ảnh ngoại thất bên hông của ' . $variant->name,
                    'image_type' => 'exterior',
                    'angle' => 'side',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 11,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Exterior+Rear+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Exterior Rear',
                    'title' => $variant->name . ' - Ngoại thất phía sau',
                    'description' => 'Ảnh ngoại thất phía sau của ' . $variant->name,
                    'image_type' => 'exterior',
                    'angle' => 'rear',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 12,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Exterior+Wheel+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Exterior Wheel',
                    'title' => $variant->name . ' - Mâm xe',
                    'description' => 'Ảnh mâm xe của ' . $variant->name,
                    'image_type' => 'exterior',
                    'angle' => 'wheel',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 13,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Exterior+Headlight+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Exterior Headlight',
                    'title' => $variant->name . ' - Đèn pha',
                    'description' => 'Ảnh đèn pha của ' . $variant->name,
                    'image_type' => 'exterior',
                    'angle' => 'headlight',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 14,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Exterior+Grille+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Exterior Grille',
                    'title' => $variant->name . ' - Lưới tản nhiệt',
                    'description' => 'Ảnh lưới tản nhiệt của ' . $variant->name,
                    'image_type' => 'exterior',
                    'angle' => 'grille',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 15,
                ],
                
                // Interior images
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Interior+Dashboard+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Interior Dashboard',
                    'title' => $variant->name . ' - Bảng điều khiển',
                    'description' => 'Ảnh bảng điều khiển của ' . $variant->name,
                    'image_type' => 'interior',
                    'angle' => 'dashboard',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 20,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Interior+Seats+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Interior Seats',
                    'title' => $variant->name . ' - Ghế ngồi',
                    'description' => 'Ảnh ghế ngồi của ' . $variant->name,
                    'image_type' => 'interior',
                    'angle' => 'seats',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 21,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Interior+Console+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Interior Console',
                    'title' => $variant->name . ' - Console trung tâm',
                    'description' => 'Ảnh console trung tâm của ' . $variant->name,
                    'image_type' => 'interior',
                    'angle' => 'console',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 22,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Interior+Trunk+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Interior Trunk',
                    'title' => $variant->name . ' - Cốp xe',
                    'description' => 'Ảnh cốp xe của ' . $variant->name,
                    'image_type' => 'interior',
                    'angle' => 'trunk',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 23,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Interior+Steering+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Interior Steering',
                    'title' => $variant->name . ' - Vô lăng',
                    'description' => 'Ảnh vô lăng của ' . $variant->name,
                    'image_type' => 'interior',
                    'angle' => 'steering',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 24,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Interior+Door+' . urlencode($variant->name),
                    'alt_text' => $variant->name . ' - Interior Door',
                    'title' => $variant->name . ' - Cửa xe',
                    'description' => 'Ảnh cửa xe của ' . $variant->name,
                    'image_type' => 'interior',
                    'angle' => 'door',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 25,
                ],
            ];
            foreach ($images as $img) {
                CarVariantImage::updateOrCreate([
                    'car_variant_id' => $img['car_variant_id'],
                    'image_url' => $img['image_url'],
                ], $img);
            }

            // Seed color-specific images for each color (chỉ gallery để tránh trùng lặp)
            $colors = CarVariantColor::where('car_variant_id', $variant->id)->get();
            foreach ($colors as $color) {
                // Chỉ tạo 1 ảnh gallery cho mỗi màu, hiển thị trong "Thư viện ảnh"
                CarVariantImage::updateOrCreate([
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => $color->id,
                    'image_type' => 'gallery',
                    'angle' => 'front',
                ], [
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($variant->name . ' - ' . $color->color_name),
                    'alt_text' => $variant->name . ' - ' . $color->color_name,
                    'title' => $variant->name . ' - ' . $color->color_name,
                    'description' => 'Ảnh màu ' . $color->color_name . ' của ' . $variant->name,
                    'is_main' => true,
                    'is_active' => true,
                    'sort_order' => 100 + ($color->sort_order ?? 0),
                ]);
            }
        }
    }
}


