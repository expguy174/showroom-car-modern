<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InstallmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'payment_transaction_id' => $this->payment_transaction_id,
            'installment_number' => $this->installment_number,
            'amount' => (string) $this->amount,
            'due_date' => optional($this->due_date)->toISOString(),
            'bank_name' => $this->bank_name,
            'interest_rate' => (string) $this->interest_rate,
            'tenure_months' => $this->tenure_months,
            'down_payment_amount' => (string) $this->down_payment_amount,
            'monthly_payment_amount' => (string) $this->monthly_payment_amount,
            'schedule' => $this->schedule,
            'status' => $this->status,
            'paid_at' => optional($this->paid_at)->toISOString(),
            'approved_at' => optional($this->approved_at)->toISOString(),
            'cancelled_at' => optional($this->cancelled_at)->toISOString(),
        ];
    }
}


