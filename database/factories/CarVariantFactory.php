<?php

namespace Database\Factories;

use App\Models\CarVariant;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\CarVariant>
 */
class CarVariantFactory extends Factory
{
    protected $model = CarVariant::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        return [
            'car_model_id' => CarModel::factory(),
            'name' => $name,
            'slug' => null,
            'sku' => strtoupper(Str::random(8)),
            'description' => $this->faker->optional()->sentence(10),
            'short_description' => $this->faker->optional()->sentence(6),
            'price' => $this->faker->randomFloat(2, 500000000, 3000000000),
            'original_price' => null,
            'has_discount' => false,
            'discount_percentage' => 0,
            'color_inventory' => null,
            'is_active' => true,
            'is_featured' => false,
            'is_available' => true,
            'is_new_arrival' => false,
            'is_bestseller' => false,
            'meta_title' => null,
            'meta_description' => null,
            'keywords' => null,
        ];
    }
}


