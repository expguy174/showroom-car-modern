<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestDriveResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'test_drive_number' => $this->test_drive_number,
            'user_id' => $this->user_id,
            'car_variant_id' => $this->car_variant_id,
            'showroom_id' => $this->showroom_id,
            'preferred_date' => optional($this->preferred_date)->toISOString(),
            'preferred_time' => $this->preferred_time,
            'duration_minutes' => $this->duration_minutes,
            'location' => $this->location,
            'notes' => $this->notes,
            'special_requirements' => $this->special_requirements,
            'has_experience' => (bool) $this->has_experience,
            'experience_level' => $this->experience_level,
            'status' => $this->status,
            'test_drive_type' => $this->test_drive_type,
            'confirmed_at' => optional($this->confirmed_at)->toISOString(),
            'completed_at' => optional($this->completed_at)->toISOString(),
            'feedback' => $this->feedback,
            'satisfaction_rating' => (string) $this->satisfaction_rating,
        ];
    }
}


