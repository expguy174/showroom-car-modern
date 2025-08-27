<?php

namespace Database\Factories;

use App\Models\Accessory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Accessory>
 */
class AccessoryFactory extends Factory
{
    protected $model = Accessory::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'name' => $name,
            'sku' => strtoupper(Str::random(8)),
            'description' => $this->faker->sentence(8),
            'short_description' => $this->faker->sentence(5),
            'category' => $this->faker->randomElement(['interior','exterior','electronics','performance']),
            'subcategory' => $this->faker->optional()->word(),
            'compatible_car_brands' => null,
            'compatible_car_models' => null,
            'compatible_car_years' => null,
            'price' => $this->faker->randomFloat(2, 100000, 5000000),
            'original_price' => $this->faker->optional()->randomFloat(2, 100000, 6000000),
            'is_on_sale' => false,
            'sale_price' => null,
            'sale_start_date' => null,
            'sale_end_date' => null,
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'stock_status' => 'in_stock',
            'gallery' => null,
            'specifications' => null,
            'features' => null,
            'installation_instructions' => null,
            'warranty_info' => null,
            'warranty_months' => $this->faker->optional()->numberBetween(6, 36),
            'slug' => Str::slug($name) . '-' . Str::lower(Str::random(4)),
            'meta_title' => $this->faker->optional()->sentence(),
            'meta_description' => $this->faker->optional()->sentence(10),
            'meta_keywords' => $this->faker->optional()->words(3, true),
            'is_featured' => false,
            'is_bestseller' => false,
            'is_popular' => false,
            'sort_order' => 0,
            'is_active' => true,
            'installation_service_available' => false,
            'installation_fee' => null,
            'installation_requirements' => null,
            'installation_time_minutes' => null,
            'warranty_terms' => null,
            'warranty_contact' => null,
            'return_policy' => null,
            'support_contact' => null,
            'return_policy_days' => null,
            'weight' => $this->faker->optional()->randomFloat(2, 0.1, 20),
            'dimensions' => $this->faker->optional()->randomElement(['10x5x2 cm','20x10x5 cm']),
            'material' => $this->faker->optional()->randomElement(['ABS','Aluminum','Carbon fiber']),
            'color_options' => null,
            'is_new_arrival' => false,
        ];
    }
}


