<?php

namespace App\Console\Commands;

use App\Models\Installment;
use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstallmentReminder;

class RemindUpcomingInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installments:remind-upcoming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for upcoming installments (3 days before due date)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking upcoming installments...');
        
        // Find installments due in 3 days
        $upcomingInstallments = Installment::where('status', 'pending')
            ->whereBetween('due_date', [
                now()->addDays(3)->startOfDay(),
                now()->addDays(3)->endOfDay()
            ])
            ->with(['user', 'order'])
            ->get();
        
        if ($upcomingInstallments->isEmpty()) {
            $this->info('No upcoming installments found.');
            return 0;
        }
        
        $this->info("Found {$upcomingInstallments->count()} upcoming installments");
        
        $remindersSent = 0;
        
        foreach ($upcomingInstallments as $installment) {
            try {
                // Check if reminder already sent today
                $existingReminder = Notification::where('user_id', $installment->user_id)
                    ->where('type', 'installment_reminder')
                    ->where('title', 'like', "%{$installment->order->order_number}%")
                    ->where('created_at', '>=', now()->startOfDay())
                    ->exists();
                
                if ($existingReminder) {
                    $this->info("⊘ Skipped installment #{$installment->id} - Reminder already sent today");
                    continue;
                }
                
                // Send notification to user
                if ($installment->user_id) {
                    Notification::create([
                        'user_id' => $installment->user_id,
                        'type' => 'installment_reminder',
                        'title' => "Đơn hàng #{$installment->order->order_number}",
                        'message' => "Nhắc nhở: Kỳ {$installment->installment_number} sẽ đến hạn vào {$installment->due_date->format('d/m/Y')}. Số tiền: " . number_format($installment->amount) . " VNĐ",
                        'is_read' => false,
                    ]);

                    // Send email notification
                    try {
                        if ($installment->user && $installment->user->email) {
                            Mail::to($installment->user->email)->send(new InstallmentReminder($installment, 3));
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send reminder email', [
                            'installment_id' => $installment->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                    
                    $remindersSent++;
                    $this->info("✓ Sent reminder for installment #{$installment->id} - Order: {$installment->order->order_number}");
                }
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to send reminder for installment #{$installment->id}: " . $e->getMessage());
                Log::error('Failed to send installment reminder', [
                    'installment_id' => $installment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        $this->info("Successfully sent {$remindersSent} reminders");
        
        Log::info('Installment reminders sent', [
            'found' => $upcomingInstallments->count(),
            'sent' => $remindersSent,
        ]);
        
        return 0;
    }
}
