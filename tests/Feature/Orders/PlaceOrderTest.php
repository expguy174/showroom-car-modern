<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Application\Orders\UseCases\PlaceOrder;
use App\Models\CarVariant;
use App\Models\User;

class PlaceOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_place_single_variant_order(): void
    {
        $user = User::factory()->create();
        $variant = CarVariant::factory()->create(['price' => 100000000, 'is_active' => 1]);

        $order = app(PlaceOrder::class)->handle([
            'user_id' => $user->id,
            'name' => 'Nguyen Van A',
            'phone' => '0900000000',
            'email' => 'a@example.com',
            'address' => 'HN',
            'items' => [[
                'item_type' => 'car_variant',
                'item_id' => $variant->id,
                'quantity' => 1,
            ]],
        ]);

        $this->assertNotNull($order->id);
        $this->assertEquals(1, $order->items()->count());
        $this->assertEquals('pending', $order->status);
    }
}


