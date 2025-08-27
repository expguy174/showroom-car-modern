<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarVariant;
use App\Models\CarSpecification;

class CarVariantSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CarVariant::all() as $variant) {
            $specs = [
                ['category' => 'engine', 'spec_name' => 'fuel_type', 'spec_value' => $variant->carModel->fuel_type ?? 'gasoline', 'unit' => null, 'is_important' => true, 'is_highlighted' => true, 'sort_order' => 1],
                ['category' => 'performance', 'spec_name' => 'power_output', 'spec_value' => '150', 'unit' => 'hp', 'is_important' => true, 'is_highlighted' => false, 'sort_order' => 2],
                ['category' => 'transmission', 'spec_name' => 'transmission', 'spec_value' => 'automatic', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 3],
                ['category' => 'dimensions', 'spec_name' => 'length', 'spec_value' => '4425', 'unit' => 'mm', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 4],
                ['category' => 'dimensions', 'spec_name' => 'width', 'spec_value' => '1730', 'unit' => 'mm', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 5],
                ['category' => 'dimensions', 'spec_name' => 'height', 'spec_value' => '1475', 'unit' => 'mm', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 6],
                ['category' => 'seating', 'spec_name' => 'seating_capacity', 'spec_value' => '5', 'unit' => null, 'is_important' => true, 'is_highlighted' => false, 'sort_order' => 7],
            ];
            $order = 1;
            foreach ($specs as $s) {
                CarSpecification::updateOrCreate([
                    'car_variant_id' => $variant->id,
                    'spec_name' => $s['spec_name'],
                ], [
                    'car_variant_id' => $variant->id,
                    'category' => $s['category'],
                    'spec_name' => $s['spec_name'],
                    'spec_value' => $s['spec_value'],
                    'unit' => $s['unit'],
                    'description' => null,
                    'spec_code' => null,
                    'is_important' => $s['is_important'],
                    'is_highlighted' => $s['is_highlighted'],
                    'sort_order' => $s['sort_order'] ?? $order,
                ]);
                $order++;
            }
        }
    }
}


