<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Installment;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusChanged;
use App\Mail\OrderCancelled;
use App\Mail\PaymentStatusChanged;
use App\Mail\InstallmentPaid;
use App\Mail\InstallmentReminder;
use App\Mail\InstallmentOverdue;
use App\Mail\VerifyEmailNotification;

class TestEmailNotifications extends Command
{
    protected $signature = 'email:test {email} {--type=all}';
    protected $description = 'Test email notifications (including email verification)';

    public function handle()
    {
        $email = $this->argument('email');
        $type = $this->option('type');
        
        $this->info("Testing email notifications to: {$email}");
        $this->newLine();

        try {
            switch ($type) {
                case 'order-status':
                    $this->testOrderStatusChanged($email);
                    break;
                case 'order-cancelled':
                    $this->testOrderCancelled($email);
                    break;
                case 'payment-status':
                    $this->testPaymentStatusChanged($email);
                    break;
                case 'installment-paid':
                    $this->testInstallmentPaid($email);
                    break;
                case 'installment-reminder':
                    $this->testInstallmentReminder($email);
                    break;
                case 'installment-overdue':
                    $this->testInstallmentOverdue($email);
                    break;
                case 'verify-email':
                    $this->testVerifyEmail($email);
                    break;
                case 'all':
                default:
                    $this->testAll($email);
                    break;
            }

            $this->newLine();
            $this->info('✓ Test completed! Check your inbox.');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function testOrderStatusChanged($email)
    {
        $order = Order::first();
        if (!$order) {
            $this->warn('No orders found. Skipping OrderStatusChanged test.');
            return;
        }

        Mail::to($email)->send(new OrderStatusChanged($order, 'pending', 'confirmed'));
        $this->line('→ Sent: OrderStatusChanged');
    }

    private function testOrderCancelled($email)
    {
        $order = Order::first();
        if (!$order) {
            $this->warn('No orders found. Skipping OrderCancelled test.');
            return;
        }

        Mail::to($email)->send(new OrderCancelled($order, 'Khách hàng yêu cầu hủy'));
        $this->line('→ Sent: OrderCancelled');
    }

    private function testPaymentStatusChanged($email)
    {
        $order = Order::first();
        if (!$order) {
            $this->warn('No orders found. Skipping PaymentStatusChanged test.');
            return;
        }

        Mail::to($email)->send(new PaymentStatusChanged($order, 'pending', 'completed'));
        $this->line('→ Sent: PaymentStatusChanged');
    }

    private function testInstallmentPaid($email)
    {
        $installment = Installment::first();
        if (!$installment) {
            $this->warn('No installments found. Skipping InstallmentPaid test.');
            return;
        }

        Mail::to($email)->send(new InstallmentPaid($installment, false));
        $this->line('→ Sent: InstallmentPaid (regular)');

        Mail::to($email)->send(new InstallmentPaid($installment, true));
        $this->line('→ Sent: InstallmentPaid (last installment)');
    }

    private function testInstallmentReminder($email)
    {
        $installment = Installment::where('status', 'pending')->first();
        if (!$installment) {
            $this->warn('No pending installments found. Skipping InstallmentReminder test.');
            return;
        }

        Mail::to($email)->send(new InstallmentReminder($installment, 3));
        $this->line('→ Sent: InstallmentReminder');
    }

    private function testInstallmentOverdue($email)
    {
        $installment = Installment::first();
        if (!$installment) {
            $this->warn('No installments found. Skipping InstallmentOverdue test.');
            return;
        }

        Mail::to($email)->send(new InstallmentOverdue($installment, 5));
        $this->line('→ Sent: InstallmentOverdue');
    }

    private function testVerifyEmail($email)
    {
        $user = User::first();
        if (!$user) {
            $this->warn('No users found. Skipping VerifyEmail test.');
            return;
        }

        Mail::to($email)->send(new VerifyEmailNotification($user));
        $this->line('→ Sent: VerifyEmailNotification');
    }

    private function testAll($email)
    {
        $this->testOrderStatusChanged($email);
        $this->info('⏳ Waiting 5 seconds to avoid rate limit...');
        sleep(5); // Wait 5 seconds between emails
        
        $this->testOrderCancelled($email);
        $this->info('⏳ Waiting 5 seconds...');
        sleep(5);
        
        $this->testPaymentStatusChanged($email);
        $this->info('⏳ Waiting 5 seconds...');
        sleep(5);
        
        $this->testInstallmentPaid($email);
        $this->info('⏳ Waiting 5 seconds...');
        sleep(5);
        
        $this->testInstallmentReminder($email);
        $this->info('⏳ Waiting 5 seconds...');
        sleep(5);
        
        $this->testInstallmentOverdue($email);
        $this->info('⏳ Waiting 5 seconds...');
        sleep(5);
        
        $this->testVerifyEmail($email);
    }
}
