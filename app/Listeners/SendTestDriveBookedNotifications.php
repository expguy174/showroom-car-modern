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

        try {
            app(EmailService::class)->sendTestDriveConfirmation($testDrive);
        } catch (\Throwable $e) {
            Log::error('TestDriveBooked listener: email failed', [
                'test_drive_id' => $testDrive->id,
                'error' => $e->getMessage(),
            ]);
        }

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
    }
}


