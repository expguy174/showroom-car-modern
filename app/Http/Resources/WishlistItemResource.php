<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'user_id' => $this->user_id,
            'item_type' => $this->item_type,
            'item_id' => $this->item_id,
            'is_active' => (bool) $this->is_active,
            'priority' => $this->priority,
            'notes' => $this->notes,
            'created_at' => optional($this->created_at)->toISOString(),
        ];
    }
}


