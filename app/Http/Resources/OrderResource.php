<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'total_price' => (string) $this->total_price,
            'subtotal' => (string) $this->subtotal,
            'discount_total' => (string) $this->discount_total,
            'tax_total' => (string) $this->tax_total,
            'shipping_fee' => (string) $this->shipping_fee,
            'payment_fee' => (string) $this->payment_fee,
            'grand_total' => (string) $this->grand_total,
            'note' => $this->note,
            'paid_at' => optional($this->paid_at)->toISOString(),
            'billing_address_id' => $this->billing_address_id,
            'shipping_address_id' => $this->shipping_address_id,
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),

            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'payment_transactions' => PaymentTransactionResource::collection($this->whenLoaded('paymentTransactions')),
            'installments' => InstallmentResource::collection($this->whenLoaded('installments')),
        ];
    }
}


