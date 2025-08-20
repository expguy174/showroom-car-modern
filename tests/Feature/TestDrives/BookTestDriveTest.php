<?php

namespace Tests\Feature\TestDrives;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Application\TestDrives\UseCases\BookTestDrive;
use App\Models\CarVariant;
use App\Models\User;

class BookTestDriveTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_book_test_drive(): void
    {
        $user = User::factory()->create();
        $variant = CarVariant::factory()->create();

        $testDrive = app(BookTestDrive::class)->handle([
            'user_id' => $user->id,
            'car_variant_id' => $variant->id,
            'name' => 'Le Thi B',
            'phone' => '0911111111',
            'email' => 'b@example.com',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time' => '10:00',
        ]);

        $this->assertNotNull($testDrive->id);
        $this->assertEquals('pending', $testDrive->status);
    }
}


