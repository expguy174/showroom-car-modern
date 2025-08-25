<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'contact_type' => $this->contact_type,
            'showroom_id' => $this->showroom_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
            'topic' => $this->topic,
            'status' => $this->status,
            'handled_at' => optional($this->handled_at)->toISOString(),
            'handled_by' => $this->handled_by,
            'source' => $this->source,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'metadata' => $this->metadata,
            'created_at' => optional($this->created_at)->toISOString(),
        ];
    }
}


