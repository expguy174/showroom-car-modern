<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceAppointment;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Showroom;

class ServiceAppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all regular users (not just khachhang@example.vn)
        $users = User::where('role', 'user')->get();
        $variants = CarVariant::pluck('id');
        $showrooms = Showroom::pluck('id');
        
        if ($users->isEmpty() || $variants->isEmpty() || $showrooms->isEmpty()) {
            return;
        }

        // Create diverse appointments from different users
        $count = 80;
        $statuses = ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        $serviceDescriptions = [
            'Bảo dưỡng định kỳ 10,000km',
            'Thay dầu và kiểm tra hệ thống phanh',
            'Bảo dưỡng 30,000km - Toàn diện',
            'Kiểm tra và sửa chữa hệ thống điện',
            'Thay lốp và cân chỉnh bánh xe',
            'Bảo dưỡng điều hòa và lọc gió',
            'Kiểm tra tổng quát trước khi đi xa',
            'Sửa chữa hệ thống treo',
            'Thay ắc quy và kiểm tra điện',
            'Bảo dưỡng hệ thống phanh ABS',
        ];

        for ($i = 1; $i <= $count; $i++) {
            // Random user from all users (diverse)
            $user = $users->random();
            $variantId = $variants->random();
            $showroomId = $showrooms->random();
            
            // Mix of past, current, and future dates
            $daysOffset = rand(-30, 30);
            $date = now()->addDays($daysOffset);
            $time = str_pad((string) rand(8, 17), 2, '0', STR_PAD_LEFT) . ':' . ['00', '15', '30', '45'][rand(0, 3)];
            
            // More realistic status distribution
            $status = $statuses[array_rand($statuses)];
            
            ServiceAppointment::create([
                'user_id' => $user->id,
                'showroom_id' => $showroomId,
                'service_id' => rand(1, 6),
                'car_variant_id' => $variantId,
                'vehicle_registration' => $this->generatePlateNumber(),
                'current_mileage' => rand(5000, 120000),
                'appointment_number' => 'SA-' . date('Ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'appointment_date' => $date->toDateString(),
                'appointment_time' => $time,
                'requested_services' => $serviceDescriptions[array_rand($serviceDescriptions)],
                'service_description' => rand(0, 1) ? 'Ghi chú bổ sung từ khách hàng: ' . $serviceDescriptions[array_rand($serviceDescriptions)] : null,
                'status' => $status,
                'is_warranty_work' => rand(0, 100) < 20, // 20% is warranty
                'estimated_cost' => rand(3, 30) * 100000, // 300k - 3M VND
            ]);
        }
    }

    private function generatePlateNumber(): string
    {
        $provinces = ['HN', 'SG', 'DN', 'HP', 'HCM', 'BD', 'NA', 'PT', 'BN', 'VT'];
        $province = $provinces[array_rand($provinces)];
        $series = rand(10, 99);
        $letter = chr(rand(65, 90)); // A-Z
        $number = str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        return $province . '-' . $series . $letter . '-' . $number;
    }
}


