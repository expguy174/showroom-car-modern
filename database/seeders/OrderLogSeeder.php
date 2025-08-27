<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Support\Facades\Auth;

class OrderLogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Order::cursor() as $order) {
            $flow = ['pending','confirmed','shipping','delivered'];
            $current = 'pending';
            OrderLog::create([
                'order_id' => $order->id,
                'user_id' => null,
                'action' => 'order_created',
                'details' => json_encode(['from' => null, 'to' => 'pending']),
                'message' => 'Đơn hàng được tạo.',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
            ]);
            foreach ($flow as $next) {
                if ($current === $next) continue;
                OrderLog::create([
                    'order_id' => $order->id,
                    'user_id' => null,
                    'action' => 'status_changed',
                    'details' => json_encode(['from' => $current, 'to' => $next]),
                    'message' => 'Đơn chuyển từ ' . $current . ' sang ' . $next,
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Seeder',
                ]);
                $current = $next;
                if ($order->status === $next) break;
            }
        }
    }
}


