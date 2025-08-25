<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'payment_method_id' => $this->payment_method_id,
            'transaction_number' => $this->transaction_number,
            'amount' => (string) $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'payment_date' => optional($this->payment_date)->toISOString(),
            'notes' => $this->notes,
            'created_at' => optional($this->created_at)->toISOString(),
        ];
    }
}


