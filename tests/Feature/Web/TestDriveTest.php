<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class TestDriveTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_drive_page_loads(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/test-drives');
        $response->assertStatus(200);
    }
}
