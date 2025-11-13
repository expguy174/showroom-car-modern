<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\TestDriveConfirmation;
use App\Mail\ServiceAppointmentConfirmation;
use App\Mail\VerifyEmailNotification;
use App\Models\Order;
use App\Models\TestDrive;
use App\Models\User;

class TestBasicEmails extends Command
{
    protected $signature = 'email:test-basic {email}';
    protected $description = 'Test basic emails (OrderConfirmation, TestDriveConfirmation, VerifyEmail)';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing basic emails to: {$email}");
        $this->newLine();

        $successCount = 0;
        $failCount = 0;

        // Test 1: OrderConfirmation
        try {
            $order = Order::first();
            if ($order) {
                Mail::to($email)->send(new OrderConfirmation($order));
                $this->info('✓ Sent: OrderConfirmation');
                $successCount++;
            } else {
                $this->warn('⚠ Skipped: OrderConfirmation (no orders found)');
            }
        } catch (\Exception $e) {
            $this->error('✗ Failed: OrderConfirmation - ' . $e->getMessage());
            $failCount++;
        }

        sleep(2); // Wait 2 seconds

        // Test 2: TestDriveConfirmation
        try {
            $testDrive = TestDrive::first();
            if ($testDrive) {
                Mail::to($email)->send(new TestDriveConfirmation($testDrive));
                $this->info('✓ Sent: TestDriveConfirmation');
                $successCount++;
            } else {
                $this->warn('⚠ Skipped: TestDriveConfirmation (no test drives found)');
            }
        } catch (\Exception $e) {
            $this->error('✗ Failed: TestDriveConfirmation - ' . $e->getMessage());
            $failCount++;
        }

        sleep(2); // Wait 2 seconds

        // Test 3: VerifyEmailNotification
        try {
            $user = User::first();
            if ($user) {
                Mail::to($email)->send(new VerifyEmailNotification($user));
                $this->info('✓ Sent: VerifyEmailNotification');
                $successCount++;
            } else {
                $this->warn('⚠ Skipped: VerifyEmailNotification (no users found)');
            }
        } catch (\Exception $e) {
            $this->error('✗ Failed: VerifyEmailNotification - ' . $e->getMessage());
            $failCount++;
        }

        $this->newLine();
        $this->info("Results: {$successCount} succeeded, {$failCount} failed");
        
        if ($failCount > 0) {
            $this->warn('Check Mailtrap inbox and logs for details.');
            return 1;
        }
        
        $this->info('✓ All tests completed! Check your Mailtrap inbox.');
        return 0;
    }
}

