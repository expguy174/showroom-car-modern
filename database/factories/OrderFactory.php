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
            'tracking_number' => null,
            'estimated_delivery' => null,
            'customer_notes' => null,
            'internal_notes' => null,
            'source' => 'website',
            'ip_address' => null,
            'user_agent' => null,
            'referrer' => null,
            'created_by' => null,
            'updated_by' => null,
            'cancelled_by' => null,
            'cancelled_at' => null,
            'cancellation_reason' => null,
            'sales_person_id' => null,
            'showroom_id' => null,
            'delivery_date' => null,
            'delivery_address' => null,
            'delivery_notes' => null,
            'billing_address_id' => null,
            'shipping_address_id' => null,
            'has_trade_in' => false,
            'trade_in_brand' => null,
            'trade_in_model' => null,
            'trade_in_year' => null,
            'trade_in_value' => null,
            'trade_in_condition' => null,
            'finance_option_id' => null,
            'down_payment_amount' => null,
            'monthly_payment_amount' => null,
            'loan_term_months' => null,
            'interest_rate' => null,
        ];
    }
}


