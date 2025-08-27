<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ServiceAppointmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_page_loads(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/service-appointments');
        $response->assertStatus(200);
    }
}
