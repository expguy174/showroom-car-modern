<?php

namespace Tests\Feature\Payments;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Application\Payments\UseCases\CreatePaymentTransaction;
use App\Models\User;
use App\Models\PaymentMethod;

class CreatePaymentTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_basic_payment_transaction(): void
    {
        $user = User::factory()->create();
        $method = PaymentMethod::factory()->create(['is_active' => true]);

        $txn = app(CreatePaymentTransaction::class)->handle([
            'user_id' => $user->id,
            'payment_method_id' => $method->id,
            'amount' => 500000,
            'currency' => 'VND',
            'payment_type' => 'full',
        ]);

        $this->assertNotNull($txn->id);
        $this->assertEquals('pending', $txn->status);
        $this->assertEquals(500000, (float) $txn->amount);
    }
}


