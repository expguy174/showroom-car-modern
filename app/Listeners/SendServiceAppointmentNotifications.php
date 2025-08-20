<?php

namespace App\Listeners;

use App\Events\ServiceAppointmentBooked;
use App\Services\EmailService;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendServiceAppointmentNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ServiceAppointmentBooked $event): void
    {
        $appointment = $event->appointment;

        // Send admin notification (db)
        try {
            Notification::create([
                'user_id' => null,
                'type' => 'system',
                'title' => 'Lịch hẹn dịch vụ mới',
                'message' => 'Khách ' . ($appointment->customer_name ?? $appointment->contact_name) . ' đặt lịch vào ' . ($appointment->appointment_date ?? $appointment->scheduled_date) . ' ' . ($appointment->appointment_time ?? $appointment->scheduled_time),
                'data' => [
                    'appointment_id' => $appointment->id,
                    'service_type' => $appointment->appointment_type ?? $appointment->service_type,
                ],
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('ServiceAppointmentBooked listener: admin notify failed', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Email confirmation to customer
        try {
            app(EmailService::class)->sendServiceAppointmentConfirmation($appointment);
        } catch (\Throwable $e) {
            Log::error('ServiceAppointmentBooked listener: email failed', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}


