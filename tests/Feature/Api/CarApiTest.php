<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CarBrand;

class CarApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_car_brands_endpoint(): void
    {
        // API v1 prefix
        CarBrand::factory()->create(['name' => 'TestBrand']);
        $response = $this->getJson('/api/v1/cars');
        $response->assertStatus(200);
    }
}
