<?php

namespace App\Application\Payments\UseCases;

use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Log;
use App\Events\PaymentProcessed;

class ProcessPayment
{
    /**
     * Simulate gateway processing and update transaction
     */
    public function handle(PaymentTransaction $transaction, array $paymentData): PaymentTransaction
    {
        $status = $this->simulateGateway($paymentData);

        $transaction->update([
            'status' => $status,
            'payment_date' => now(),
            'notes' => 'Gateway response: ' . substr(json_encode($paymentData), 0, 2000),
        ]);

        try {
            event(new PaymentProcessed($transaction));
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch PaymentProcessed event', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $transaction;
    }

    private function simulateGateway(array $paymentData): string
    {
        $successRate = 0.95;
        return rand(1, 100) <= ($successRate * 100) ? 'completed' : 'failed';
    }
}


