<?php

namespace Database\Seeders;

use App\Models\TestDrive;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Showroom;
use Illuminate\Database\Seeder;

class TestDriveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $carVariants = CarVariant::all();
        $showrooms = Showroom::all();

        $testDrives = [
                               // VIP Customer Test Drive
                   [
                       'test_drive_number' => 'TD-2024-001',
                       'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                       'car_variant_id' => $carVariants->where('name', 'C-Class C200')->first()->id,
                       'showroom_id' => $showrooms->where('code', 'HN_CENTER')->first()->id,
                       'preferred_date' => now()->addDays(2)->toDateString(),
                       'preferred_time' => '14:00:00',
                       'duration_minutes' => 60,
                       'status' => 'confirmed',
                       'notes' => 'Khách hàng VIP, cần xe cao cấp; cung cấp lộ trình chạy thử nội đô',
                       'special_requirements' => 'Cần xe Mercedes-Benz C-Class',
                       'has_experience' => true,
                       'experience_level' => 'expert',
                       'test_drive_type' => 'individual',
                       'confirmed_at' => now(),
                       'completed_at' => null,
                       'feedback' => 'Chưa có',
                       'satisfaction_rating' => 0.0
                   ],

                   // Regular Customer 1 Test Drive
                   [
                       'test_drive_number' => 'TD-2024-002',
                       'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                       'car_variant_id' => $carVariants->where('name', 'Vios G')->first()->id,
                       'showroom_id' => $showrooms->where('code', 'HN_TAYHO')->first()->id,
                       'preferred_date' => now()->subDays(5)->toDateString(),
                       'preferred_time' => '10:00:00',
                       'duration_minutes' => 45,
                       'status' => 'completed',
                       'notes' => 'Cần xe gia đình, tiết kiệm nhiên liệu; chạy thử cung đường cầu Nhật Tân',
                       'special_requirements' => 'Xe gia đình tiết kiệm nhiên liệu',
                       'has_experience' => false,
                       'experience_level' => 'beginner',
                       'test_drive_type' => 'individual',
                       'confirmed_at' => now()->subDays(5),
                       'completed_at' => now()->subDays(5),
                       'feedback' => 'Xe rất tốt, phù hợp với gia đình',
                       'satisfaction_rating' => 4.5
                   ],

                   // Regular Customer 2 Test Drive
                   [
                       'test_drive_number' => 'TD-2024-003',
                       'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                       'car_variant_id' => $carVariants->where('name', 'Ranger XLT')->first()->id,
                       'showroom_id' => $showrooms->where('code', 'HCM_Q1')->first()->id,
                       'preferred_date' => now()->addDays(7)->toDateString(),
                       'preferred_time' => '16:00:00',
                       'duration_minutes' => 90,
                       'status' => 'pending',
                       'notes' => 'Cần xe đa dụng cho công việc; cần kiểm tra khả năng chở hàng nhẹ',
                       'special_requirements' => 'Xe đa dụng cho công việc',
                       'has_experience' => true,
                       'experience_level' => 'intermediate',
                       'test_drive_type' => 'individual',
                       'confirmed_at' => null,
                       'completed_at' => null,
                       'feedback' => 'Chưa có',
                       'satisfaction_rating' => 0.0
                   ],

                   // Cancelled Test Drive
                   [
                       'test_drive_number' => 'TD-2024-004',
                       'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                       'car_variant_id' => $carVariants->where('name', 'City G')->first()->id,
                       'showroom_id' => $showrooms->where('code', 'HCM_Q7')->first()->id,
                       'preferred_date' => now()->subDays(10)->toDateString(),
                       'preferred_time' => '15:00:00',
                       'duration_minutes' => 60,
                       'status' => 'cancelled',
                       'notes' => 'Cần xe gia đình; dời lịch do bận công tác',
                       'special_requirements' => 'Xe gia đình',
                       'has_experience' => false,
                       'experience_level' => 'beginner',
                       'test_drive_type' => 'individual',
                       'confirmed_at' => null,
                       'completed_at' => null,
                       'feedback' => 'Chưa có',
                       'satisfaction_rating' => 0.0
                   ]
        ];

        foreach ($testDrives as $testDrive) {
            TestDrive::create($testDrive);
        }
    }
}
