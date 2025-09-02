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
                // Engine & fuel
                ['category' => 'engine', 'spec_name' => 'fuel_type', 'spec_value' => $variant->carModel->fuel_type ?? 'gasoline', 'unit' => null, 'is_important' => true, 'is_highlighted' => true, 'sort_order' => 1],
                ['category' => 'engine', 'spec_name' => 'engine_type', 'spec_value' => 'DOHC', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 2],
                ['category' => 'engine', 'spec_name' => 'engine_displacement', 'spec_value' => '1496', 'unit' => 'cc', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 3],

                // Performance & transmission
                ['category' => 'performance', 'spec_name' => 'power_output', 'spec_value' => '150', 'unit' => 'hp', 'is_important' => true, 'is_highlighted' => false, 'sort_order' => 10],
                ['category' => 'performance', 'spec_name' => 'torque', 'spec_value' => '250', 'unit' => 'Nm', 'is_important' => true, 'is_highlighted' => false, 'sort_order' => 11],
                ['category' => 'performance', 'spec_name' => 'acceleration', 'spec_value' => '9.5', 'unit' => 's', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 12],
                ['category' => 'performance', 'spec_name' => 'max_speed', 'spec_value' => '200', 'unit' => 'km/h', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 13],
                ['category' => 'transmission', 'spec_name' => 'transmission', 'spec_value' => 'automatic', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 14],
                ['category' => 'performance', 'spec_name' => 'drivetrain', 'spec_value' => 'FWD', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 15],

                // Dimensions
                ['category' => 'dimensions', 'spec_name' => 'length', 'spec_value' => '4425', 'unit' => 'mm', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 20],
                ['category' => 'dimensions', 'spec_name' => 'width', 'spec_value' => '1730', 'unit' => 'mm', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 21],
                ['category' => 'dimensions', 'spec_name' => 'height', 'spec_value' => '1475', 'unit' => 'mm', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 22],
                ['category' => 'dimensions', 'spec_name' => 'wheelbase', 'spec_value' => '2550', 'unit' => 'mm', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 23],
                ['category' => 'dimensions', 'spec_name' => 'ground_clearance', 'spec_value' => '150', 'unit' => 'mm', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 24],
                ['category' => 'seating', 'spec_name' => 'seating_capacity', 'spec_value' => '5', 'unit' => null, 'is_important' => true, 'is_highlighted' => false, 'sort_order' => 25],

                // Chassis / Brakes / Wheels
                ['category' => 'chassis', 'spec_name' => 'front_suspension', 'spec_value' => 'McPherson', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 30],
                ['category' => 'chassis', 'spec_name' => 'rear_suspension', 'spec_value' => 'Thanh xoắn', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 31],
                ['category' => 'brake', 'spec_name' => 'front_brake', 'spec_value' => 'Đĩa', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 32],
                ['category' => 'brake', 'spec_name' => 'rear_brake', 'spec_value' => 'Đĩa', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 33],
                ['category' => 'wheels', 'spec_name' => 'wheel_size', 'spec_value' => '17', 'unit' => 'inch', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 34],
                ['category' => 'wheels', 'spec_name' => 'tire_size', 'spec_value' => '205/55 R17', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 35],

                // Comfort / Tech
                ['category' => 'comfort', 'spec_name' => 'auto_climate', 'spec_value' => '1', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 40],
                ['category' => 'comfort', 'spec_name' => 'sunroof', 'spec_value' => '0', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 41],
                ['category' => 'comfort', 'spec_name' => 'power_seats', 'spec_value' => '0', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 42],
                ['category' => 'comfort', 'spec_name' => 'memory_seats', 'spec_value' => '0', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 43],
                ['category' => 'technology', 'spec_name' => 'infotainment_screen_size', 'spec_value' => '8', 'unit' => 'inch', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 44],
                ['category' => 'technology', 'spec_name' => 'apple_carplay', 'spec_value' => '1', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 45],
                ['category' => 'technology', 'spec_name' => 'android_auto', 'spec_value' => '1', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 46],

                // Safety & warranty
                ['category' => 'safety', 'spec_name' => 'airbag_count', 'spec_value' => '6', 'unit' => null, 'is_important' => true, 'is_highlighted' => false, 'sort_order' => 50],
                ['category' => 'safety', 'spec_name' => 'abs', 'spec_value' => '1', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 51],
                ['category' => 'safety', 'spec_name' => 'ebd', 'spec_value' => '1', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 52],
                ['category' => 'safety', 'spec_name' => 'esc', 'spec_value' => '1', 'unit' => null, 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 53],
                ['category' => 'warranty', 'spec_name' => 'warranty_years', 'spec_value' => '3', 'unit' => 'years', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 60],
                ['category' => 'warranty', 'spec_name' => 'warranty_km', 'spec_value' => '100000', 'unit' => 'km', 'is_important' => false, 'is_highlighted' => false, 'sort_order' => 61],
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


