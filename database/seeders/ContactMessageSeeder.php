<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\User;
use App\Models\Showroom;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $showrooms = Showroom::all();

        $contactMessages = [
            [
                'user_id' => null,
                'contact_type' => 'guest',
                'showroom_id' => $showrooms->where('code', 'HN_CENTER')->first()->id,
                'name' => 'Nguyễn Văn An',
                'phone' => '0901234567',
                'email' => 'nguyenvanan@email.com',
                'subject' => 'Tư vấn mua xe Toyota Vios',
                'message' => 'Tôi đang quan tâm đến xe Toyota Vios G. Mong được tư vấn về giá cả và các chương trình khuyến mãi hiện tại.',
                'topic' => 'sales',
                'status' => 'new',
                'handled_at' => null,
                'handled_by' => null,
                'source' => 'website',
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => json_encode(['referrer' => 'google.com']),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4)
            ],
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'contact_type' => 'user',
                'showroom_id' => $showrooms->where('code', 'HN_TAYHO')->first()->id,
                'name' => 'Trần Thị Bình',
                'phone' => '0912345678',
                'email' => 'customer1@example.com',
                'subject' => 'Đặt lịch bảo dưỡng xe',
                'message' => 'Tôi muốn đặt lịch bảo dưỡng định kỳ cho xe Toyota Vios G. Xe đã chạy được 10.000km.',
                'topic' => 'service',
                'status' => 'in_progress',
                'handled_at' => now()->subDays(2),
                'handled_by' => $users->where('email', 'admin@showroom.com')->first()->id,
                'source' => 'website',
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)',
                'metadata' => json_encode(['device' => 'mobile']),
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(2)
            ],
            [
                'user_id' => null,
                'contact_type' => 'guest',
                'showroom_id' => $showrooms->where('code', 'HCM_Q1')->first()->id,
                'name' => 'Lê Văn Cường',
                'phone' => '0923456789',
                'email' => 'levancuong@email.com',
                'subject' => 'Đăng ký lái thử xe Honda City',
                'message' => 'Tôi muốn đăng ký lái thử xe Honda City G. Mong được sắp xếp lịch vào cuối tuần.',
                'topic' => 'test_drive',
                'status' => 'new',
                'handled_at' => null,
                'handled_by' => null,
                'source' => 'phone',
                'ip_address' => null,
                'user_agent' => null,
                'metadata' => json_encode(['call_duration' => '5 minutes']),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ],
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'contact_type' => 'user',
                'showroom_id' => $showrooms->where('code', 'HCM_Q7')->first()->id,
                'name' => 'Phạm Thị Dung',
                'phone' => '0934567890',
                'email' => 'vip@example.com',
                'subject' => 'Tư vấn tài chính mua xe Mercedes',
                'message' => 'Tôi quan tâm đến xe Mercedes-Benz C-Class C200 và muốn được tư vấn về các gói tài chính phù hợp.',
                'topic' => 'finance',
                'status' => 'resolved',
                'handled_at' => now()->subDays(1),
                'handled_by' => $users->where('email', 'admin@showroom.com')->first()->id,
                'source' => 'website',
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'metadata' => json_encode(['vip_customer' => true]),
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(1)
            ],
            [
                'user_id' => null,
                'contact_type' => 'guest',
                'showroom_id' => $showrooms->where('code', 'DN_CENTER')->first()->id,
                'name' => 'Hoàng Văn Em',
                'phone' => '0945678901',
                'email' => 'hoangvanem@email.com',
                'subject' => 'Khiếu nại về dịch vụ bảo hành',
                'message' => 'Tôi có vấn đề với dịch vụ bảo hành xe. Xe bị lỗi sau khi bảo dưỡng và cần được kiểm tra lại.',
                'topic' => 'warranty',
                'status' => 'new',
                'handled_at' => null,
                'handled_by' => null,
                'source' => 'chat',
                'ip_address' => '192.168.1.103',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'metadata' => json_encode(['chat_session_id' => 'chat_12345']),
                'created_at' => now()->subDays(1),
                'updated_at' => now()
            ]
        ];

        foreach ($contactMessages as $contactMessage) {
            ContactMessage::create($contactMessage);
        }
    }
}
