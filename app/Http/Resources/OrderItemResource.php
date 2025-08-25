<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'item_type' => $this->item_type,
            'item_id' => $this->item_id,
            'color_id' => $this->color_id,
            'item_name' => $this->item_name,
            'item_sku' => $this->item_sku,
            'item_metadata' => $this->item_metadata,
            'quantity' => $this->quantity,
            'price' => (string) $this->price,
            'tax_amount' => (string) $this->tax_amount,
            'discount_amount' => (string) $this->discount_amount,
            'line_total' => (string) $this->line_total,
        ];
    }
}


