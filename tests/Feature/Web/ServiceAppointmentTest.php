<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceAppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_page_loads(): void
    {
        $response = $this->get('/services');
        $response->assertStatus(200);
    }
}
