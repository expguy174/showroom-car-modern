<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'total_price' => 0,
            'subtotal' => 0,
            'discount_total' => 0,
            'tax_total' => 0,
            'shipping_fee' => 0,
            'grand_total' => 0,
            'note' => null,
            'payment_method_id' => null,
            'payment_status' => 'pending',
            'transaction_id' => null,
            'paid_at' => null,
            'status' => 'pending',
            'order_number' => 'ORD-'.date('Ymd').'-'.strtoupper(bin2hex(random_bytes(3))),
            'billing_address_id' => null,
            'shipping_address_id' => null,
        ];
    }
}


