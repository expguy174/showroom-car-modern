<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarVariant;
use App\Models\CarVariantFeature;

class CarVariantFeatureSeeder extends Seeder
{
    public function run(): void
    {
        $pairs = [
            ['category' => 'safety', 'feature_name' => 'Phanh ABS', 'availability' => 'standard', 'importance' => 'essential', 'price' => 0, 'is_included' => true, 'is_featured' => true],
            ['category' => 'comfort', 'feature_name' => 'Điều hoà tự động', 'availability' => 'standard', 'importance' => 'important', 'price' => 0, 'is_included' => true, 'is_featured' => false],
            ['category' => 'technology', 'feature_name' => 'Apple CarPlay', 'availability' => 'optional', 'importance' => 'nice_to_have', 'price' => 5000000, 'is_included' => false, 'is_featured' => true],
        ];
        foreach (CarVariant::all() as $variant) {
            $order = 1;
            foreach ($pairs as $f) {
                CarVariantFeature::updateOrCreate([
                    'car_variant_id' => $variant->id,
                    'feature_name' => $f['feature_name'],
                ], [
                    'car_variant_id' => $variant->id,
                    'feature_name' => $f['feature_name'],
                    'description' => null,
                    'feature_code' => null,
                    'category' => $f['category'],
                    'availability' => $f['availability'],
                    'importance' => $f['importance'],
                    'price' => $f['price'],
                    'is_included' => $f['price'] == 0,
                    'is_active' => true,
                    'is_featured' => $f['is_featured'],
                    'is_popular' => false,
                    'is_recommended' => false,
                    'sort_order' => $order++,
                ]);
            }
        }
    }
}


