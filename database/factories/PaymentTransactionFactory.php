<?php

namespace Database\Factories;

use App\Models\PaymentTransaction;
use App\Models\Order;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentTransaction>
 */
class PaymentTransactionFactory extends Factory
{
    protected $model = PaymentTransaction::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(\App\Models\PaymentTransaction::STATUSES);
        return [
            'order_id' => Order::query()->inRandomOrder()->value('id'),
            'user_id' => User::query()->inRandomOrder()->value('id'),
            'payment_method_id' => PaymentMethod::query()->inRandomOrder()->value('id'),
            'transaction_number' => 'TXN-' . date('Ymd') . '-' . strtoupper($this->faker->bothify('######')),
            'amount' => $this->faker->numberBetween(1000000, 500000000),
            'currency' => 'VND',
            'status' => $status,
            'payment_date' => in_array($status, ['completed']) ? now() : null,
            'notes' => $this->faker->optional()->sentence(8),
        ];
    }
}


