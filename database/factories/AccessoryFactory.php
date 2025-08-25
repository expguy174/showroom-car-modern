<?php

namespace Database\Factories;

use App\Models\Accessory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Accessory>
 */
class AccessoryFactory extends Factory
{
    protected $model = Accessory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'code' => strtoupper(Str::random(6)),
            'sku' => strtoupper(Str::random(8)),
            'description' => $this->faker->sentence(8),
            'short_description' => $this->faker->sentence(5),
            'brand' => $this->faker->randomElement(['OEM','ThirdParty','Premium']),
            'model' => $this->faker->word(),
            'category' => $this->faker->randomElement(['interior','exterior','electronics','performance']),
            'subcategory' => null,
            'price' => $this->faker->randomFloat(2, 100000, 5000000),
            'original_price' => null,
            'cost_price' => null,
            'wholesale_price' => null,
            'is_on_sale' => false,
            'sale_price' => null,
            'sale_start_date' => null,
            'sale_end_date' => null,
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'min_stock_level' => 0,
            'max_stock_level' => null,
            'stock_status' => 'in_stock',
            'track_quantity' => true,
            'allow_backorder' => false,
            'backorder_quantity' => 0,
            'is_featured' => false,
            'is_bestseller' => false,
            'is_popular' => false,
            'is_new' => false,
            'sort_order' => 0,
            'is_active' => true,
            'is_visible' => true,
            'status' => 'active',
            'slug' => Str::slug($this->faker->unique()->words(3, true)) . '-' . Str::lower(Str::random(4)),
        ];
    }
}


