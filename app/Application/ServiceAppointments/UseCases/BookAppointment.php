<?php

namespace App\Application\ServiceAppointments\UseCases;

use App\Models\ServiceAppointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\ServiceAppointmentBooked;

class BookAppointment
{
    /**
     * Create a service appointment and dispatch event.
     */
    public function handle(array $payload): ServiceAppointment
    {
        $appointment = DB::transaction(function () use ($payload) {
            $appointment = ServiceAppointment::create([
                'user_id' => $payload['user_id'] ?? null,
                'appointment_type' => $payload['service_type'],
                'car_variant_id' => $payload['car_variant_id'] ?? null,
                'appointment_date' => $payload['scheduled_date'],
                'appointment_time' => $payload['scheduled_time'],
                'service_description' => $payload['description'] ?? null,
                'customer_name' => $payload['contact_name'],
                'customer_phone' => $payload['contact_phone'],
                'customer_email' => $payload['contact_email'],
                'status' => 'scheduled',
                'appointment_number' => 'SA' . date('Ymd') . strtoupper(uniqid()),
                'showroom_id' => $payload['showroom_id'] ?? null,
            ]);

            return $appointment;
        });

        try {
            event(new ServiceAppointmentBooked($appointment));
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch ServiceAppointmentBooked event', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $appointment;
    }
}


