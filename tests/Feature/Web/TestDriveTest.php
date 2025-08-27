<?php

namespace Tests\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestDriveTest extends TestCase
{
    use RefreshDatabase;

    public function test_test_drive_page_loads(): void
    {
        $response = $this->get('/test-drives');
        $response->assertStatus(200);
    }
}
