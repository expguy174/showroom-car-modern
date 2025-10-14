<?php

namespace App\Console\Commands;

use App\Models\Installment;
use App\Models\OrderLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\InstallmentOverdue;

class CheckOverdueInstallments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'installments:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update overdue installments status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking overdue installments...');
        
        $today = now();
        
        // Find installments that are overdue (due_date < today and status = pending)
        $overdueInstallments = Installment::where('status', 'pending')
            ->where('due_date', '<', $today)
            ->with(['user', 'order'])
            ->get();
        
        if ($overdueInstallments->isEmpty()) {
            $this->info('No overdue installments found.');
            return 0;
        }
        
        $this->info("Found {$overdueInstallments->count()} overdue installments");
        
        $updatedCount = 0;
        
        foreach ($overdueInstallments as $installment) {
            try {
                $daysOverdue = $today->diffInDays($installment->due_date);
                
                // Update status to overdue
                $installment->update(['status' => 'overdue']);
                
                // Log the change
                OrderLog::create([
                    'order_id' => $installment->order_id,
                    'user_id' => null, // System action
                    'action' => 'installment_overdue',
                    'message' => "Kỳ {$installment->installment_number} quá hạn ({$daysOverdue} ngày)",
                    'details' => [
                        'installment_id' => $installment->id,
                        'installment_number' => $installment->installment_number,
                        'due_date' => $installment->due_date->toDateString(),
                        'days_overdue' => $daysOverdue,
                        'amount' => $installment->amount,
                    ],
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'System Command',
                ]);
                
                // Send notification to user
                if ($installment->user_id) {
                    \App\Models\Notification::create([
                        'user_id' => $installment->user_id,
                        'type' => 'installment_overdue',
                        'title' => "Đơn hàng #{$installment->order->order_number}",
                        'message' => "⚠️ Kỳ {$installment->installment_number} đã quá hạn {$daysOverdue} ngày. Vui lòng thanh toán sớm để tránh phát sinh phí.",
                        'is_read' => false,
                    ]);

                    // Send email notification
                    try {
                        if ($installment->user && $installment->user->email) {
                            Mail::to($installment->user->email)->send(new InstallmentOverdue($installment, $daysOverdue));
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send overdue email', [
                            'installment_id' => $installment->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                $updatedCount++;
                $this->info("✓ Updated installment #{$installment->id} - Order: {$installment->order->order_number} - {$daysOverdue} days overdue");
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to process installment #{$installment->id}: " . $e->getMessage());
                Log::error('Failed to process overdue installment', [
                    'installment_id' => $installment->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }
        
        $this->info("Successfully updated {$updatedCount} installments to overdue status");
        
        Log::info('Overdue installments check completed', [
            'found' => $overdueInstallments->count(),
            'updated' => $updatedCount,
        ]);
        
        return 0;
    }
}
