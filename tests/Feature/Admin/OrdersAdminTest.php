<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class OrdersAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_orders_index_loads(): void
    {
        /** @var \App\Models\User $admin */
        $admin = \App\Models\User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        $response = $this->get('/admin/orders');
        $response->assertStatus(200);
    }
}
