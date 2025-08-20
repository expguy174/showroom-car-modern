<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\CarVariant;

class BookServiceAppointmentHttpTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_book_service_appointment(): void
    {
        $this->markTestSkipped('services.* routes removed per product decision');
    }
}


