<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\CarVariant;

class BookTestDriveHttpTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_book_test_drive(): void
    {
        $user = User::factory()->create();
        $variant = CarVariant::factory()->create();

        $this->actingAs($user)
            ->post(route('test_drives.book'), [
                'car_variant_id' => $variant->id,
                'name' => 'Nguyen Van A',
                'phone' => '0900000000',
                'email' => 'a@example.com',
                'preferred_date' => now()->addDay()->toDateString(),
                'preferred_time' => '10:00',
            ])->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}


