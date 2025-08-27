<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_car_brands_endpoint(): void
    {
        $response = $this->getJson('/api/brands');
        $response->assertStatus(200);
    }
}
