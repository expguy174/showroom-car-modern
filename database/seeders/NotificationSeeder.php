<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role','user')->first();
        if (!$user) return;

        $rows = [
            [
                'user_id' => $user->id,
                'type' => 'order_status',
                'title' => 'Đơn hàng của bạn đang được xử lý',
                'message' => 'Đơn hàng SEED01 đã được tiếp nhận và đang xử lý.',
                'is_read' => false,
                'read_at' => null,
            ],
            [
                'user_id' => $user->id,
                'type' => 'promotion',
                'title' => 'Ưu đãi tháng này',
                'message' => 'Giảm 10 triệu cho xe sedan hạng B, áp dụng đến cuối tháng.',
                'is_read' => true,
                'read_at' => now()->subDays(1),
            ],
        ];

        foreach ($rows as $n) {
            Notification::create($n);
        }
    }
}


