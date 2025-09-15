<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Prefer specific user email; fallback to first role=user
        $user = User::where('email', 'khachhang@example.vn')->first()
            ?: User::where('role', 'user')->first();
        if (!$user) return;

        // Wipe existing for this user if you want a clean slate (optional)
        // Notification::where('user_id', $user->id)->delete();

        $now = now();
        $seed = [];

        // Order-related
        $seed[] = ['type'=>'order_status','title'=>'Đơn hàng đã tạo','message'=>'Đơn #SEED1001 đã được tạo thành công.','is_read'=>false,'created_at'=>$now->copy()->subMinutes(5)];
        $seed[] = ['type'=>'payment','title'=>'Thanh toán thành công','message'=>'Giao dịch VNPAY #TX1001 đã hoàn tất.','is_read'=>false,'created_at'=>$now->copy()->subMinutes(8)];
        $seed[] = ['type'=>'order_status','title'=>'Đơn hàng đang giao','message'=>'Đơn #SEED1001 đang được vận chuyển.','is_read'=>true,'created_at'=>$now->copy()->subHours(2),'read_at'=>$now->copy()->subHour()];
        $seed[] = ['type'=>'order_status','title'=>'Đơn hàng đã hủy','message'=>'Đơn #SEED0999 đã được hủy theo yêu cầu.','is_read'=>true,'created_at'=>$now->copy()->subDays(1),'read_at'=>$now->copy()->subDays(1)->addHour()];

        // Test drive
        $seed[] = ['type'=>'test_drive','title'=>'Đặt lịch lái thử thành công','message'=>'Bạn đã đặt lịch lái thử #TD-2024-0001.','is_read'=>false,'created_at'=>$now->copy()->subMinutes(12)];
        $seed[] = ['type'=>'test_drive','title'=>'Lịch lái thử đã xác nhận','message'=>'Lịch #TD-2024-0001 đã được xác nhận.','is_read'=>true,'created_at'=>$now->copy()->subHours(3),'read_at'=>$now->copy()->subHours(2)];
        $seed[] = ['type'=>'test_drive','title'=>'Đã hủy lịch lái thử','message'=>'Bạn đã hủy lịch #TD-2024-0002.','is_read'=>true,'created_at'=>$now->copy()->subDays(2),'read_at'=>$now->copy()->subDays(2)->addMinutes(30)];

        // Service appointment
        $seed[] = ['type'=>'service_appointment','title'=>'Đặt lịch bảo dưỡng thành công','message'=>'Lịch bảo dưỡng #SV-3001 đã được tạo.','is_read'=>false,'created_at'=>$now->copy()->subMinutes(20)];
        $seed[] = ['type'=>'service_appointment','title'=>'Đổi lịch bảo dưỡng','message'=>'Lịch #SV-3001 đã được dời sang 10:30.','is_read'=>true,'created_at'=>$now->copy()->subHours(5),'read_at'=>$now->copy()->subHours(4)];

        // Promotions and misc
        $seed[] = ['type'=>'promotion','title'=>'Ưu đãi tháng này','message'=>'Giảm 10 triệu cho sedan hạng B đến cuối tháng.','is_read'=>true,'created_at'=>$now->copy()->subDays(3),'read_at'=>$now->copy()->subDays(3)->addMinutes(5)];
        $seed[] = ['type'=>'promotion','title'=>'Quà tặng phụ kiện','message'=>'Tặng bộ phụ kiện trị giá 2 triệu cho đơn trên 200 triệu.','is_read'=>false,'created_at'=>$now->copy()->subMinutes(30)];

        // Bulk generate more to test pagination
        for ($i = 0; $i < 20; $i++) {
            $seed[] = [
                'type' => collect(['order_status','payment','test_drive','service_appointment','promotion'])->random(),
                'title' => 'Thông báo thử nghiệm #' . str_pad((string)($i+1), 2, '0', STR_PAD_LEFT),
                'message' => 'Nội dung thông báo mẫu để kiểm tra phân trang và thao tác.',
                'is_read' => (bool)random_int(0, 1),
                'created_at' => $now->copy()->subMinutes(35 + $i),
                'read_at' => null,
            ];
        }

        foreach ($seed as $n) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $n['type'],
                'title' => $n['title'],
                'message' => $n['message'],
                'is_read' => $n['is_read'] ?? false,
                'read_at' => $n['read_at'] ?? null,
                'created_at' => $n['created_at'] ?? now(),
                'updated_at' => $n['created_at'] ?? now(),
            ]);
        }
    }
}


