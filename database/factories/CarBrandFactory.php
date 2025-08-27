<?php

namespace Database\Factories;

use App\Models\CarBrand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CarBrand>
 */
class CarBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarBrand::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->company() . ' Motors';
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'logo_path' => null,
            'country' => $this->faker->optional()->country(),
            'description' => $this->faker->optional()->paragraph(),
            'meta_title' => $this->faker->optional()->sentence(),
            'meta_description' => $this->faker->optional()->sentence(10),
            'keywords' => $this->faker->optional()->words(3, true),
            'founded_year' => $this->faker->optional()->numberBetween(1900, 2020),
            'website' => $this->faker->optional()->url(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'email' => $this->faker->optional()->companyEmail(),
            'address' => $this->faker->optional()->address(),
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the brand is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the brand is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the brand is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
