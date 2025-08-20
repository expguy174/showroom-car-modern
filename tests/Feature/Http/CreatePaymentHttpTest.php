<?php

namespace Tests\Feature\Http;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\PaymentMethod;

class CreatePaymentHttpTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_payment_transaction(): void
    {
        $user = User::factory()->create();
        $method = PaymentMethod::factory()->create(['is_active' => true]);

        $this->actingAs($user)
            ->post(route('user.payments.store'), [
                'payment_method_id' => $method->id,
                'amount' => 1000000,
                'currency' => 'VND',
                'payment_type' => 'full',
            ])
            ->assertStatus(302); // redirects to show page
    }
}


