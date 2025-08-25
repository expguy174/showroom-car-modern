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
            'assigned_technician_id' => $this->assigned_technician_id,
            'car_variant_id' => $this->car_variant_id,
            'appointment_number' => $this->appointment_number,
            'appointment_date' => optional($this->appointment_date)->toISOString(),
            'appointment_time' => $this->appointment_time,
            'estimated_duration' => $this->estimated_duration,
            'appointment_type' => $this->appointment_type,
            'requested_services' => $this->requested_services,
            'service_description' => $this->service_description,
            'customer_complaints' => $this->customer_complaints,
            'special_instructions' => $this->special_instructions,
            'status' => $this->status,
            'priority' => $this->priority,
            'is_warranty_work' => (bool) $this->is_warranty_work,
            'warranty_number' => $this->warranty_number,
            'warranty_expiry_date' => optional($this->warranty_expiry_date)->toISOString(),
            'estimated_cost' => (string) $this->estimated_cost,
            'actual_cost' => (string) $this->actual_cost,
            'parts_cost' => (string) $this->parts_cost,
            'labor_cost' => (string) $this->labor_cost,
            'tax_amount' => (string) $this->tax_amount,
            'discount_amount' => (string) $this->discount_amount,
            'total_amount' => (string) $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'payment_date' => optional($this->payment_date)->toISOString(),
            'actual_start_time' => $this->actual_start_time,
            'actual_end_time' => $this->actual_end_time,
            'work_performed' => $this->work_performed,
            'parts_used' => $this->parts_used,
            'technician_notes' => $this->technician_notes,
            'quality_check_passed' => (bool) $this->quality_check_passed,
            'quality_check_by' => $this->quality_check_by,
            'quality_check_notes' => $this->quality_check_notes,
            'vehicle_ready' => (bool) $this->vehicle_ready,
            'vehicle_ready_time' => $this->vehicle_ready_time,
            'customer_notified' => (bool) $this->customer_notified,
            'customer_notified_time' => $this->customer_notified_time,
            'customer_satisfaction' => (string) $this->customer_satisfaction,
            'customer_recommend' => (bool) $this->customer_recommend,
            'customer_feedback' => $this->customer_feedback,
            'notes' => $this->notes,
            'documents' => $this->documents,
            'tags' => $this->tags,
        ];
    }
}


