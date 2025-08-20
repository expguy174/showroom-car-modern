<?php

namespace App\Application\Payments\UseCases;

use App\Models\PaymentTransaction;
use App\Models\Installment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreatePaymentTransaction
{
    /**
     * Create a payment transaction, optionally create installment plan.
     */
    public function handle(array $payload): PaymentTransaction
    {
        return DB::transaction(function () use ($payload) {
            $transaction = PaymentTransaction::create([
                'user_id' => $payload['user_id'] ?? Auth::id(),
                'order_id' => $payload['order_id'] ?? null,
                'payment_method_id' => $payload['payment_method_id'],
                'amount' => $payload['amount'],
                'currency' => $payload['currency'],
                'payment_type' => $payload['payment_type'],
                'transaction_number' => $payload['transaction_number'] ?? ('TXN' . date('Ymd') . rand(1000, 9999)),
                'status' => $payload['status'] ?? 'pending',
                'payment_date' => $payload['payment_date'] ?? now(),
                'reference_number' => $payload['reference_number'] ?? null,
                'notes' => $payload['notes'] ?? null,
                // totals (simple for demo)
                'gateway_fee' => 0,
                'processing_fee' => 0,
                'tax_amount' => 0,
                'total_amount' => $payload['amount'],
            ]);

            if (($payload['payment_type'] ?? null) === 'installment' && !empty($payload['installment_terms'])) {
                $this->createInstallments($transaction, (int) $payload['installment_terms'], (float) ($payload['down_payment'] ?? 0));
            }

            return $transaction;
        });
    }

    private function createInstallments(PaymentTransaction $transaction, int $terms, float $downPayment): void
    {
        $amount = (float) $transaction->amount;
        $remainingAmount = $amount - $downPayment;
        $monthlyAmount = $terms > 0 ? $remainingAmount / $terms : $remainingAmount;

        for ($i = 1; $i <= $terms; $i++) {
            Installment::create([
                'order_id' => $transaction->order_id,
                'user_id' => $transaction->user_id,
                'payment_transaction_id' => $transaction->id,
                'installment_number' => $i,
                'amount' => $monthlyAmount,
                'due_date' => now()->addMonths($i),
                'status' => 'pending',
            ]);
        }
    }
}


