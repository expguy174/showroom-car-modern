<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceAppointmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'showroom_id' => $this->showroom_id,
            'car_variant_id' => $this->car_variant_id,
            'appointment_number' => $this->appointment_number,
            'appointment_date' => optional($this->appointment_date)->toISOString(),
            'appointment_time' => $this->appointment_time,
            'appointment_type' => $this->appointment_type,
            'requested_services' => $this->requested_services,
            'service_description' => $this->service_description,
            'status' => $this->status,
            'priority' => $this->priority,
            'is_warranty_work' => (bool) $this->is_warranty_work,
            'estimated_cost' => (string) $this->estimated_cost,
            'vehicle_registration' => $this->vehicle_registration,
            'current_mileage' => $this->current_mileage,
            'satisfaction_rating' => $this->satisfaction_rating,
            'feedback' => $this->feedback,
        ];
    }
}


