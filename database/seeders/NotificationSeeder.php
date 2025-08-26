<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $notifications = [
            // Thông báo khuyến mãi
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'type' => 'promotion',
                'title' => 'Khuyến mãi đặc biệt cho khách hàng VIP',
                'message' => 'Chương trình khuyến mãi mùa hè với mức giảm giá lên đến 20% đã bắt đầu. Hãy đến showroom để được tư vấn!',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2)
            ],
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'type' => 'order_status',
                'title' => 'Đơn hàng của bạn đã được xác nhận',
                'message' => 'Đơn hàng #ORD-2024-001 của bạn đã được xác nhận và đang được xử lý. Chúng tôi sẽ liên hệ sớm nhất.',
                'is_read' => true,
                'read_at' => now()->subHours(1),
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(1)
            ],
            [
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'type' => 'test_drive',
                'title' => 'Lịch test drive đã được sắp xếp',
                'message' => 'Lịch test drive xe Honda City G của bạn đã được sắp xếp vào ngày mai lúc 14:00. Vui lòng đến showroom đúng giờ.',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4)
            ],
            [
                'user_id' => $users->where('email', 'customer3@example.com')->first()->id,
                'type' => 'service',
                'title' => 'Nhắc nhở bảo dưỡng xe',
                'message' => 'Xe của bạn đã đến lịch bảo dưỡng định kỳ. Vui lòng liên hệ để đặt lịch bảo dưỡng.',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6)
            ],
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'type' => 'finance',
                'title' => 'Gói tài chính mới dành cho bạn',
                'message' => 'Chúng tôi có gói tài chính đặc biệt với lãi suất ưu đãi dành cho khách hàng VIP. Liên hệ ngay để được tư vấn.',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subHours(8)
            ],
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'type' => 'promotion',
                'title' => 'Khuyến mãi xe gia đình',
                'message' => 'Giảm giá 15% cho các dòng xe gia đình. Chương trình chỉ diễn ra trong tháng này. Đừng bỏ lỡ cơ hội!',
                'is_read' => true,
                'read_at' => now()->subDays(1),
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1)
            ],
            [
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'type' => 'order_status',
                'title' => 'Đơn hàng đã được giao',
                'message' => 'Đơn hàng #ORD-2024-002 của bạn đã được giao thành công. Cảm ơn bạn đã tin tưởng chúng tôi!',
                'is_read' => true,
                'read_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2)
            ],
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'type' => 'service',
                'title' => 'Dịch vụ bảo dưỡng hoàn thành',
                'message' => 'Dịch vụ bảo dưỡng xe Mercedes-Benz C-Class của bạn đã hoàn thành. Bạn có thể đến lấy xe.',
                'is_read' => false,
                'read_at' => null,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3)
            ]
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}
