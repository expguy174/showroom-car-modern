<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_orders_index_loads(): void
    {
        $response = $this->get('/admin/orders');
        $response->assertStatus(200);
    }
}
