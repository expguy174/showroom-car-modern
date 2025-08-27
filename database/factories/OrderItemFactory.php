<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Accessory;
use App\Models\CarVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        // Determine item type and ensure referenced record exists
        $type = $this->faker->randomElement(['car_variant', 'accessory']);

        if ($type === 'car_variant') {
            $itemId = CarVariant::query()->inRandomOrder()->value('id')
                ?? CarVariant::factory()->create()->id;
        } else {
            $itemId = Accessory::query()->inRandomOrder()->value('id')
                ?? Accessory::factory()->create()->id;
        }

        $price = $this->faker->randomFloat(2, 100000, 5000000);
        $qty = $this->faker->numberBetween(1, 5);

        return [
            'order_id' => null,
            'item_type' => $type,
            'item_id' => $itemId,
            'color_id' => null,
            'item_name' => $this->faker->words(3, true),
            'item_sku' => $this->faker->optional()->bothify('SKU-####'),
            'item_metadata' => null,
            'quantity' => $qty,
            'price' => $price,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'line_total' => (float) number_format($price * $qty, 2, '.', ''),
        ];
    }
}


