<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        $userId = User::query()->inRandomOrder()->value('id') ?? User::factory()->create()->id;
        return [
            'user_id' => $userId,
            'type' => $this->faker->randomElement(['home','work','billing','shipping','other']),
            'contact_name' => $this->faker->name(),
            'phone' => $this->faker->optional()->numerify('09########'),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->optional()->state(),
            'postal_code' => $this->faker->optional()->postcode(),
            'country' => 'Vietnam',
            'is_default' => false,
            'notes' => $this->faker->optional()->sentence(6),
        ];
    }
}


