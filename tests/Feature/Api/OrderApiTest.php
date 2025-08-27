<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_index_endpoint(): void
    {
        /** @var \App\Models\User $user */
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);
        $response = $this->withoutMiddleware()->getJson('/api/v1/orders');
        $response->assertStatus(200);
    }
}
