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
        $name = $this->faker->unique()->word() . ' ' . $this->faker->bothify('##');
        return [
            'car_brand_id' => CarBrand::factory(),
            'name' => $name,
            'slug' => null,
            'description' => $this->faker->optional()->sentence(8),
            'body_type' => $this->faker->optional()->randomElement(['sedan','suv','hatchback','wagon','coupe','convertible','pickup','van','minivan']),
            'segment' => $this->faker->optional()->randomElement(['economy','compact','mid-size','full-size','luxury','premium','sports','exotic']),
            'fuel_type' => $this->faker->optional()->randomElement(['gasoline','diesel','hybrid','electric','plug-in_hybrid','hydrogen']),
            'production_start_year' => $this->faker->optional()->numberBetween(2010, 2024),
            'production_end_year' => null,
            'generation' => $this->faker->optional()->randomElement(['Gen 1','Gen 2','Gen 3','Gen 4','Gen 5']),
            'meta_title' => $this->faker->optional()->sentence(),
            'meta_description' => $this->faker->optional()->sentence(10),
            'keywords' => $this->faker->optional()->sentence(3),
            'is_active' => true,
            'is_featured' => false,
            'is_new' => false,
            'is_discontinued' => false,
            'sort_order' => 0,
        ];
    }
}


