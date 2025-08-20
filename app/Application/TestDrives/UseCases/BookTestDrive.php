<?php

namespace App\Application\TestDrives\UseCases;

use App\Models\TestDrive;
use Illuminate\Support\Facades\Log;
use App\Events\TestDriveBooked;
use Illuminate\Support\Str;

class BookTestDrive
{
    /**
     * Create a test drive booking and dispatch event.
     * Expects validated payload.
     */
    public function handle(array $payload): TestDrive
    {
        // Generate a unique test drive number if not provided
        $testDriveNumber = $payload['test_drive_number'] ?? null;
        if (!$testDriveNumber) {
            do {
                $testDriveNumber = 'TD-' . strtoupper(Str::random(4)) . '-' . now()->format('ymdHis');
            } while (TestDrive::where('test_drive_number', $testDriveNumber)->exists());
        }

        $testDrive = TestDrive::create([
            'test_drive_number' => $testDriveNumber,
            'user_id' => $payload['user_id'] ?? null,
            'car_variant_id' => $payload['car_variant_id'],
            'name' => $payload['name'],
            'phone' => $payload['phone'],
            'email' => $payload['email'] ?? null,
            'preferred_date' => $payload['preferred_date'],
            'preferred_time' => $payload['preferred_time'],
            'notes' => $payload['notes'] ?? null,
            'driver_license' => $payload['driver_license'] ?? null,
            'id_card' => $payload['id_card'] ?? null,
            'status' => 'pending',
        ]);

        try {
            event(new TestDriveBooked($testDrive));
        } catch (\Throwable $e) {
            Log::warning('Failed to dispatch TestDriveBooked event', [
                'test_drive_id' => $testDrive->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $testDrive;
    }
}


