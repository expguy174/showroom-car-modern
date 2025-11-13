<?php

namespace App\Listeners;

use App\Events\TestDriveBooked;
use App\Services\EmailService;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTestDriveBookedNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(TestDriveBooked $event): void
    {
        $testDrive = $event->testDrive;

        // Prevent duplicate processing using cache lock
        $lockKey = "test_drive_notifications_{$testDrive->id}";
        $lock = \Illuminate\Support\Facades\Cache::lock($lockKey, 60); // 60 seconds lock
        
        if (!$lock->get()) {
            Log::warning('SendTestDriveBookedNotifications: Already processing for test drive', [
                'test_drive_id' => $testDrive->id,
            ]);
            return;
        }

        try {
            // Check if email already sent
            $emailSentKey = "test_drive_confirmation_email_sent_{$testDrive->id}";
            if (!\Illuminate\Support\Facades\Cache::has($emailSentKey)) {
        try {
            app(EmailService::class)->sendTestDriveConfirmation($testDrive);
                    \Illuminate\Support\Facades\Cache::put($emailSentKey, true, 3600); // Cache for 1 hour
        } catch (\Throwable $e) {
            Log::error('TestDriveBooked listener: email failed', [
                'test_drive_id' => $testDrive->id,
                'error' => $e->getMessage(),
            ]);
        }
            } else {
                Log::info('TestDriveBooked listener: Email already sent, skipping', [
                    'test_drive_id' => $testDrive->id,
                ]);
            }

            // Check if admin notification already exists to prevent duplicates
            $existingAdminNotification = Notification::whereNull('user_id')
                ->where('type', 'test_drive')
                ->where('title', 'Đặt lịch lái thử mới')
                ->where('message', 'like', "%Khách hàng {$testDrive->name}%")
                ->where('created_at', '>=', now()->subMinutes(5)) // Within last 5 minutes
                ->first();

            if (!$existingAdminNotification) {
        try {
            // Create an admin notification entry
            Notification::create([
                'user_id' => null,
                'type' => 'test_drive',
                'title' => 'Đặt lịch lái thử mới',
                'message' => "Khách hàng {$testDrive->name} đã đặt lịch lái thử xe {$testDrive->carVariant->name}",
                'data' => [
                    'test_drive_id' => $testDrive->id,
                    'car_variant_id' => $testDrive->car_variant_id,
                ],
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('TestDriveBooked listener: admin notify failed', [
                'test_drive_id' => $testDrive->id,
                'error' => $e->getMessage(),
            ]);
                }
            } else {
                Log::info('Duplicate admin notification prevented for test drive', [
                    'test_drive_id' => $testDrive->id,
                    'existing_notification_id' => $existingAdminNotification->id,
                ]);
            }
        } finally {
            $lock->release();
        }
    }
}


