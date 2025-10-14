<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Installment;
use Carbon\Carbon;

class InstallmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all installment orders (have finance_option_id and tenure_months)
        $installmentOrders = Order::whereNotNull('finance_option_id')
            ->whereNotNull('tenure_months')
            ->with(['user', 'financeOption'])
            ->get();
            
        if ($installmentOrders->isEmpty()) {
            $this->command->warn('No installment orders found. Run OrderSeeder first.');
            return;
        }

        foreach ($installmentOrders as $order) {
            $financeOptionName = $order->financeOption ? $order->financeOption->name : 'N/A';
            $this->command->info("Creating installments for Order #{$order->order_number} ({$financeOptionName})");
            
            // Calculate installment details
            $totalAmount = (float) $order->grand_total;
            $downPayment = (float) ($order->down_payment_amount ?? 0);
            $remainingAmount = $totalAmount - $downPayment;
            $tenure = (int) $order->tenure_months;
            $monthlyAmount = round($remainingAmount / $tenure, 2);
            
            // Validate tenure is within finance option range
            if ($order->financeOption) {
                $minTenure = $order->financeOption->min_tenure;
                $maxTenure = $order->financeOption->max_tenure;
                
                if ($tenure < $minTenure || $tenure > $maxTenure) {
                    $this->command->warn("  ⚠️  Order tenure ({$tenure}) outside range ({$minTenure}-{$maxTenure})");
                }
            }
            
            // Random number of paid installments (0-3)
            $paidCount = rand(0, min(3, $tenure));
            
            // Create installments
            for ($i = 1; $i <= $tenure; $i++) {
                $dueDate = Carbon::parse($order->created_at)->addMonths($i);
                $isPaid = $i <= $paidCount;
                
                // Determine status
                if ($isPaid) {
                    $status = 'paid';
                } elseif ($dueDate->isPast()) {
                    $status = 'overdue';
                } else {
                    $status = 'pending';
                }
                
                Installment::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'finance_option_id' => $order->finance_option_id,
                    'payment_transaction_id' => null, // Will be set when paid
                    'installment_number' => $i,
                    'amount' => $monthlyAmount,
                    'due_date' => $dueDate,
                    'status' => $status,
                    'paid_at' => $isPaid ? $dueDate->subDays(rand(1, 5)) : null,
                    'approved_at' => null,
                    'cancelled_at' => null,
                ]);
            }
            
            $this->command->info("✓ Created {$tenure} installments ({$paidCount} paid, " . ($tenure - $paidCount) . " pending)");
        }
        
        $this->command->info('✓ Installment seeding completed!');
    }
}


