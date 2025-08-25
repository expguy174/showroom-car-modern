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
        return [
            'car_model_id' => CarModel::factory(),
            'name' => $this->faker->unique()->words(3, true),
            'slug' => null,
            'sku' => strtoupper(Str::random(8)),
            'description' => $this->faker->sentence(10),
            'short_description' => $this->faker->sentence(6),
            'fuel_type' => $this->faker->randomElement(['petrol','diesel','electric','hybrid']),
            'transmission' => $this->faker->randomElement(['manual','automatic','cvt']),
            'engine_size' => $this->faker->randomElement(['1.5L','2.0L','2.5L']),
            'power' => $this->faker->numberBetween(100, 500) . 'hp',
            'torque' => $this->faker->numberBetween(150, 600) . 'Nm',
            'fuel_consumption' => $this->faker->randomFloat(1, 4, 12) . 'L/100km',
            'warranty_years' => $this->faker->randomElement([3,4,5]),
            'price' => $this->faker->randomFloat(2, 500000000, 3000000000),
            'original_price' => null,
            'has_discount' => false,
            'discount_percentage' => 0,
            'stock_quantity' => $this->faker->numberBetween(0, 50),
            'is_active' => true,
            'is_featured' => false,
            'is_available' => true,
            'is_new_arrival' => false,
            'is_bestseller' => false,
            'average_rating' => 0,
            'rating_count' => 0,
            'view_count' => 0,
            'meta_title' => null,
            'meta_description' => null,
            'keywords' => null,
        ];
    }
}


