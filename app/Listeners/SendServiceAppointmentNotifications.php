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

        // Prevent duplicate processing using cache lock
        $lockKey = "service_appointment_notifications_{$appointment->id}";
        $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, 60); // 60 seconds lock
        
        if (!$lock->get()) {
            Log::warning('SendServiceAppointmentNotifications: Already processing for appointment', [
                'appointment_id' => $appointment->id,
            ]);
            return;
        }

        try {
            // Check if email already sent
            $emailSentKey = "service_appointment_confirmation_email_sent_{$appointment->id}";
            if (!\Illuminate\Support\Facades\Cache::has($emailSentKey)) {
                try {
                    app(EmailService::class)->sendServiceAppointmentConfirmation($appointment);
                    \Illuminate\Support\Facades\Cache::put($emailSentKey, true, 3600); // Cache for 1 hour
                } catch (\Throwable $e) {
                    Log::error('ServiceAppointmentBooked listener: email failed', [
                        'appointment_id' => $appointment->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            } else {
                Log::info('ServiceAppointmentBooked listener: Email already sent, skipping', [
                    'appointment_id' => $appointment->id,
                ]);
            }

            // Check if admin notification already exists to prevent duplicates
            $customerName = $appointment->customer_name ?? $appointment->contact_name;
            $existingAdminNotification = Notification::whereNull('user_id')
                ->where('type', 'system')
                ->where('title', 'Lịch hẹn dịch vụ mới')
                ->where('message', 'like', "%Khách {$customerName}%")
                ->where('created_at', '>=', now()->subMinutes(5)) // Within last 5 minutes
                ->first();

            if (!$existingAdminNotification) {
                try {
                    // Send admin notification (db)
            Notification::create([
                'user_id' => null,
                'type' => 'system',
                'title' => 'Lịch hẹn dịch vụ mới',
                        'message' => 'Khách ' . $customerName . ' đặt lịch vào ' . ($appointment->appointment_date ?? $appointment->scheduled_date) . ' ' . ($appointment->appointment_time ?? $appointment->scheduled_time),
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
            } else {
                Log::info('Duplicate admin notification prevented for service appointment', [
                'appointment_id' => $appointment->id,
                    'existing_notification_id' => $existingAdminNotification->id,
            ]);
            }
        } finally {
            $lock->release();
        }
    }
}


