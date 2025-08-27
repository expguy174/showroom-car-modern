<?php

namespace Database\Factories;

use App\Models\Refund;
use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Refund>
 */
class RefundFactory extends Factory
{
    protected $model = Refund::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending','processing','refunded','failed']);
        $paymentTransactionId = PaymentTransaction::query()->inRandomOrder()->value('id')
            ?? PaymentTransaction::factory()->create()->id;
        return [
            'payment_transaction_id' => $paymentTransactionId,
            'amount' => $this->faker->numberBetween(100000, 10000000),
            'reason' => $this->faker->sentence(8),
            'status' => $status,
            'processed_at' => in_array($status, ['processing','refunded']) ? now() : null,
            'meta' => null,
        ];
    }
}


