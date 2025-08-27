<?php

namespace Database\Factories;

use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserProfile>
 */
class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition(): array
    {
        $userId = User::query()->inRandomOrder()->value('id') ?? User::factory()->create()->id;
        return [
            'user_id' => $userId,
            'profile_type' => 'customer',
            'name' => $this->faker->name(),
            'avatar_path' => null,
            'birth_date' => $this->faker->optional()->date(),
            'gender' => $this->faker->optional()->randomElement(['male','female','other']),
            'driver_license_number' => $this->faker->optional()->bothify('DL########'),
            'driver_license_issue_date' => $this->faker->optional()->date(),
            'driver_license_expiry_date' => $this->faker->optional()->date(),
            'driver_license_class' => $this->faker->optional()->randomElement(['A','B','C','D']),
            'driving_experience_years' => $this->faker->optional()->numberBetween(0, 30),
            'preferred_car_types' => null,
            'preferred_brands' => null,
            'preferred_colors' => null,
            'budget_min' => $this->faker->optional()->randomFloat(2, 200000000, 1000000000),
            'budget_max' => $this->faker->optional()->randomFloat(2, 1000000000, 5000000000),
            'purchase_purpose' => $this->faker->optional()->randomElement(['personal','business','family','travel']),
            'customer_type' => $this->faker->randomElement(['new','returning','vip','prospect']),
            'employee_salary' => null,
            'employee_skills' => null,
            'is_vip' => false,
        ];
    }
}


