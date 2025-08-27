<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\Showroom;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role','user')->first();
        $showroom = Showroom::first();

        $rows = [
            [
                'user_id' => $user?->id,
                'contact_type' => $user ? 'user' : 'guest',
                'showroom_id' => $showroom?->id,
                'name' => $user?->userProfile->name ?? 'Khách lẻ',
                'phone' => '0901234567',
                'email' => $user?->email ?? 'guest@example.com',
                'subject' => 'Hỏi thông tin xe',
                'message' => 'Tôi muốn tư vấn về phiên bản và thời gian giao xe.',
                'topic' => 'sales',
                'status' => 'new',
                'handled_at' => null,
                'handled_by' => null,
                'source' => 'website',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'metadata' => null,
            ],
            [
                'user_id' => $user?->id,
                'contact_type' => $user ? 'user' : 'guest',
                'showroom_id' => $showroom?->id,
                'name' => $user?->userProfile->name ?? 'Khách lẻ',
                'phone' => '0901234567',
                'email' => $user?->email ?? 'guest@example.com',
                'subject' => 'Đặt lịch bảo dưỡng',
                'message' => 'Tôi muốn đặt lịch bảo dưỡng tuần sau.',
                'topic' => 'service',
                'status' => 'in_progress',
                'handled_at' => now(),
                'handled_by' => null,
                'source' => 'website',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'metadata' => null,
            ],
        ];

        // thêm nhiều liên hệ ngẫu nhiên
        for ($i = 1; $i <= 10; $i++) {
            $rows[] = [
                'user_id' => $user?->id,
                'contact_type' => $user ? 'user' : 'guest',
                'showroom_id' => $showroom?->id,
                'name' => $user?->userProfile->name ?? 'Khách #' . $i,
                'phone' => '09' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'email' => $user?->email ?? "guest{$i}@example.com",
                'subject' => 'Hỏi thông tin sản phẩm #' . $i,
                'message' => 'Tôi cần tư vấn thêm về sản phẩm và thời gian giao hàng #' . $i,
                'topic' => ['sales','service','test_drive','warranty','finance','other'][array_rand(['sales','service','test_drive','warranty','finance','other'])],
                'status' => ['new','in_progress','resolved'][array_rand(['new','in_progress','resolved'])],
                'handled_at' => null,
                'handled_by' => null,
                'source' => 'website',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'metadata' => null,
            ];
        }

        foreach ($rows as $r) {
            ContactMessage::create($r);
        }
    }
}


