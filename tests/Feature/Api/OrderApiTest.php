<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_index_endpoint(): void
    {
        $response = $this->getJson('/api/orders');
        $response->assertStatus(200);
    }
}
