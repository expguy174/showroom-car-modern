<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderLogSeeder extends Seeder
{
    public function run(): void
    {
        // Get staff users (exclude customers)
        $staffUsers = User::whereIn('role', ['admin', 'sales_person', 'technician', 'manager'])
            ->where('is_active', true)
            ->with('userProfile')
            ->get();

        if ($staffUsers->isEmpty()) {
            $this->command->warn('No staff users found. OrderLogs will be created without user_id.');
        }

        foreach (Order::cursor() as $order) {
            // Randomly assign a staff to handle this order
            // Sales person has higher probability (70%), others share 30%
            $handlingStaff = $this->getRandomStaff($staffUsers);

            $flow = ['pending','confirmed','shipping','delivered'];
            $statusLabels = [
                'pending' => 'Chờ xác nhận',
                'confirmed' => 'Đã xác nhận',
                'shipping' => 'Đang giao hàng',
                'delivered' => 'Đã giao hàng',
            ];
            $current = 'pending';
            
            // Order created log - might not have staff yet
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => null, // System created
                'action' => 'order_created',
                'details' => json_encode(['from' => null, 'to' => 'pending']),
                'message' => 'Đơn hàng được tạo bởi hệ thống',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ]);

            // Status change logs - handled by staff
            foreach ($flow as $next) {
                if ($current === $next) continue;
                
                $staffName = $handlingStaff ? ($handlingStaff->userProfile->name ?? $handlingStaff->email) : 'Hệ thống';
                
                OrderLog::create([
                    'order_id' => $order->id,
                    'user_id' => $handlingStaff?->id, // Staff handling the order
                    'action' => 'status_changed',
                    'details' => json_encode(['from' => $current, 'to' => $next]),
                    'message' => sprintf(
                        'Đơn hàng chuyển từ "%s" sang "%s" bởi %s',
                        $statusLabels[$current] ?? $current,
                        $statusLabels[$next] ?? $next,
                        $staffName
                    ),
                    'ip_address' => '127.0.0.' . rand(1, 254),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                ]);
                
                $current = $next;
                if ($order->status === $next) break;
            }
        }
    }

    /**
     * Get random staff with weighted probability
     * Sales person: 70% chance
     * Others: 30% chance split between them
     */
    private function getRandomStaff($staffUsers)
    {
        if ($staffUsers->isEmpty()) {
            return null;
        }

        $salesPeople = $staffUsers->where('role', 'sales_person');
        $otherStaff = $staffUsers->whereNotIn('role', ['sales_person']);

        // 70% chance to pick sales person
        if ($salesPeople->isNotEmpty() && rand(1, 100) <= 70) {
            return $salesPeople->random();
        }

        // 30% chance for others, or fallback to sales if no others
        if ($otherStaff->isNotEmpty()) {
            return $otherStaff->random();
        }

        return $salesPeople->isNotEmpty() ? $salesPeople->random() : $staffUsers->random();
    }
}


