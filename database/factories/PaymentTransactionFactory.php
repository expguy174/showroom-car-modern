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
        $status = $this->faker->randomElement(['pending','processing','completed','failed','cancelled']);
        $orderId = Order::query()->inRandomOrder()->value('id') ?? Order::factory()->create()->id;
        $userId = User::query()->inRandomOrder()->value('id') ?? User::factory()->create()->id;
        $methodId = PaymentMethod::query()->inRandomOrder()->value('id') ?? PaymentMethod::factory()->create()->id;
        return [
            'order_id' => $orderId,
            'user_id' => $userId,
            'payment_method_id' => $methodId,
            'transaction_number' => 'TXN-' . date('Ymd') . '-' . strtoupper($this->faker->bothify('######')),
            'amount' => $this->faker->numberBetween(1000000, 500000000),
            'currency' => 'VND',
            'status' => $status,
            'payment_date' => in_array($status, ['completed']) ? now() : null,
            'notes' => $this->faker->optional()->sentence(8),
        ];
    }
}


