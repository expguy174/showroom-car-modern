<?php

namespace Database\Factories;

use App\Models\Installment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Installment>
 */
class InstallmentFactory extends Factory
{
    protected $model = Installment::class;

    public function definition(): array
    {
        $terms = $this->faker->randomElement([6, 12, 24, 36]);
        $amount = $this->faker->numberBetween(50000000, 2000000000);
        $monthly = (int) round($amount / max(1, $terms));
        return [
            'order_id' => Order::query()->inRandomOrder()->value('id'),
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'installment_number' => 1,
            'amount' => $amount,
            'due_date' => now()->addDays(30),
            'bank_name' => $this->faker->company(),
            'interest_rate' => $this->faker->randomFloat(2, 0, 30),
            'tenure_months' => $terms,
            'down_payment_amount' => 0,
            'monthly_payment_amount' => $monthly,
            'schedule' => [
                'total_amount' => $amount,
                'monthly_payment' => $monthly,
                'interest_rate' => 0.0,
                'tenure_months' => $terms,
            ],
            'status' => $this->faker->randomElement(['pending','paid','overdue','cancelled']),
        ];
    }
}


