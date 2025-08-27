<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\PaymentMethod;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role','user')->get();
        $variants = CarVariant::all();
        $accessories = Accessory::all();
        $paymentMethods = PaymentMethod::all();

        if ($users->isEmpty() || $variants->isEmpty() || $paymentMethods->isEmpty()) return;

        $orderCount = 220;
        for ($i = 1; $i <= $orderCount; $i++) {
            $user = $users->random();
            $pm = $paymentMethods->random();
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'user_id' => $user->id,
                'total_price' => 0,
                'subtotal' => 0,
                'discount_total' => 0,
                'tax_total' => 0,
                'shipping_fee' => 0,
                'grand_total' => 0,
                'note' => 'Đơn hàng seed #' . $i,
                'payment_method_id' => $pm->id,
                'payment_status' => (function(){ $r=rand(1,100); return $r<=60?'paid':($r<=90?'pending':'failed'); })(),
                'transaction_id' => null,
                'paid_at' => null,
                'status' => (function(){ $r=rand(1,100); if($r<=10)return 'pending'; if($r<=40)return 'confirmed'; if($r<=70)return 'shipping'; if($r<=90)return 'delivered'; return 'cancelled'; })(),
                'tracking_number' => null,
                'estimated_delivery' => null,
                'customer_notes' => null,
                'internal_notes' => null,
                'source' => 'website',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
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
                'billing_address_id' => $user->addresses()->first()->id ?? null,
                'shipping_address_id' => $user->addresses()->first()->id ?? null,
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
            ]);

            $numItems = rand(1, 3);
            $subtotal = 0;
            for ($j = 0; $j < $numItems; $j++) {
                if (rand(0,1) || $accessories->isEmpty()) {
                    $variant = $variants->random();
                    $price = $variant->price;
                    $line = [
                        'item_type' => 'car_variant',
                        'item_id' => $variant->id,
                        'color_id' => null,
                        'item_name' => $variant->name,
                        'item_sku' => $variant->sku,
                        'item_metadata' => null,
                        'quantity' => 1,
                        'price' => $price,
                        'tax_amount' => 0,
                        'discount_amount' => 0,
                        'line_total' => $price,
                    ];
                } else {
                    $acc = $accessories->random();
                    $price = $acc->price;
                    $quantity = rand(1,2);
                    $line = [
                        'item_type' => 'accessory',
                        'item_id' => $acc->id,
                        'color_id' => null,
                        'item_name' => $acc->name,
                        'item_sku' => $acc->sku,
                        'item_metadata' => null,
                        'quantity' => $quantity,
                        'price' => $price,
                        'tax_amount' => 0,
                        'discount_amount' => 0,
                        'line_total' => $price * $quantity,
                    ];
                }
                $subtotal += $line['line_total'];
                OrderItem::create(array_merge($line, ['order_id' => $order->id]));
            }

            $order->update([
                'subtotal' => $subtotal,
                'total_price' => $subtotal,
                'grand_total' => $subtotal,
            ]);
        }
    }
}


