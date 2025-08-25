<?php

namespace Database\Factories;

use App\Models\CarModel;
use App\Models\CarBrand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\CarModel>
 */
class CarModelFactory extends Factory
{
    protected $model = CarModel::class;

    public function definition(): array
    {
        return [
            'car_brand_id' => CarBrand::factory(),
            'name' => $this->faker->unique()->word() . ' ' . $this->faker->bothify('##'),
            'slug' => null,
            'description' => $this->faker->sentence(8),
            'body_type' => $this->faker->randomElement(['sedan','suv','hatchback']),
            'segment' => $this->faker->randomElement(['compact','mid-size','full-size','luxury']),
            'fuel_type' => $this->faker->randomElement(['gasoline','diesel','hybrid','electric']),
            'production_start_year' => $this->faker->numberBetween(2010, 2024),
            'production_end_year' => null,
            'generation' => 'Gen ' . $this->faker->numberBetween(1, 5),
            'meta_title' => null,
            'meta_description' => null,
            'keywords' => null,
            'is_active' => true,
            'is_featured' => false,
            'is_new' => false,
            'is_discontinued' => false,
            'sort_order' => 0,
            'total_variants' => 0,
            'starting_price' => $this->faker->randomFloat(2, 300000000, 3000000000),
            'average_rating' => 0,
            'rating_count' => 0,
        ];
    }
}


