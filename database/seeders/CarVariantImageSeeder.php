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
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($variant->name),
                    'alt_text' => $variant->name,
                    'title' => $variant->name . ' - Exterior',
                    'description' => null,
                    'image_type' => 'exterior',
                    'angle' => 'front',
                    'is_main' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Interior+' . urlencode($variant->name),
                    'alt_text' => $variant->name,
                    'title' => $variant->name . ' - Interior',
                    'description' => null,
                    'image_type' => 'interior',
                    'angle' => 'interior',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 2,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Rear+' . urlencode($variant->name),
                    'alt_text' => $variant->name,
                    'title' => $variant->name . ' - Rear',
                    'description' => null,
                    'image_type' => 'exterior',
                    'angle' => 'rear',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 3,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Side+' . urlencode($variant->name),
                    'alt_text' => $variant->name,
                    'title' => $variant->name . ' - Side',
                    'description' => null,
                    'image_type' => 'exterior',
                    'angle' => 'side',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 4,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Wheel+' . urlencode($variant->name),
                    'alt_text' => $variant->name,
                    'title' => $variant->name . ' - Wheel',
                    'description' => null,
                    'image_type' => 'detail',
                    'angle' => 'wheel',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 5,
                ],
                [
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => null,
                    'image_url' => 'https://placehold.co/1200x800?text=Dashboard+' . urlencode($variant->name),
                    'alt_text' => $variant->name,
                    'title' => $variant->name . ' - Dashboard',
                    'description' => null,
                    'image_type' => 'interior',
                    'angle' => 'dashboard',
                    'is_main' => false,
                    'is_active' => true,
                    'sort_order' => 6,
                ],
            ];
            foreach ($images as $img) {
                CarVariantImage::updateOrCreate([
                    'car_variant_id' => $img['car_variant_id'],
                    'image_url' => $img['image_url'],
                ], $img);
            }

            // Seed per-color color-specific exterior image (no swatch records)
            $colors = CarVariantColor::where('car_variant_id', $variant->id)->get();
            foreach ($colors as $color) {
                // Color-specific exterior image (front) for this color
                CarVariantImage::updateOrCreate([
                    'car_variant_id' => $variant->id,
                    'car_variant_color_id' => $color->id,
                    'image_type' => 'exterior',
                    'angle' => 'front',
                ], [
                    'image_url' => 'https://placehold.co/1200x800?text=' . urlencode($variant->name . ' - ' . $color->color_name),
                    'alt_text' => $variant->name . ' - ' . $color->color_name,
                    'title' => $variant->name . ' - Exterior - ' . $color->color_name,
                    'description' => null,
                    'is_main' => true,
                    'is_active' => true,
                    'sort_order' => 10 + ($color->sort_order ?? 0),
                ]);
            }
        }
    }
}


