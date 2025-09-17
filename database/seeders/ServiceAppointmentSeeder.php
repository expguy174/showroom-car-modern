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
        $users = User::where('role','user')->get();
        $targetUser = User::where('email', 'khachhang@example.vn')->first();
        $variants = CarVariant::pluck('id');
        $showrooms = Showroom::pluck('id');
        if ($users->isEmpty() || $variants->isEmpty() || $showrooms->isEmpty()) return;

        $count = 120;
        for ($i = 1; $i <= $count; $i++) {
            $user = $targetUser ?: $users->random();
            $variantId = $variants->random();
            $showroomId = $showrooms->random();
            $date = now()->addDays(rand(1,30));
            $time = str_pad((string) rand(8,16), 2, '0', STR_PAD_LEFT) . ':' . (rand(0,1) ? '00' : '30');
            ServiceAppointment::create([
                'user_id' => $user->id,
                'showroom_id' => $showroomId,
                'service_id' => rand(1,6), // Random service from seeded services
                'car_variant_id' => $variantId,
                'vehicle_registration' => 'XX-' . rand(10,99) . rand(100,999) . '.' . rand(10,99),
                'current_mileage' => rand(5000,90000),
                'appointment_number' => 'SA-' . date('Ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'appointment_date' => $date->toDateString(),
                'appointment_time' => $time,
                'requested_services' => 'Dịch vụ #' . $i,
                'service_description' => null,
                'status' => ['scheduled','in_progress','completed','cancelled'][array_rand(['scheduled','in_progress','completed','cancelled'])],
                'priority' => ['low','medium','high'][array_rand(['low','medium','high'])],
                'is_warranty_work' => (bool) rand(0,1),
                'estimated_cost' => rand(300000,3000000),
            ]);
        }

        // If specific target user exists, add extra dense sample for them
        if ($targetUser && !$variants->isEmpty() && !$showrooms->isEmpty()) {
            for ($i = 1; $i <= 40; $i++) {
                $variantId = $variants->random();
                $showroomId = $showrooms->random();
                $date = now()->subDays(rand(0,30));
                $time = str_pad((string) rand(8,16), 2, '0', STR_PAD_LEFT) . ':' . (rand(0,1) ? '00' : '30');
                ServiceAppointment::create([
                    'user_id' => $targetUser->id,
                    'showroom_id' => $showroomId,
                    'service_id' => rand(1,6), // Random service from seeded services
                    'car_variant_id' => $variantId,
                    'vehicle_registration' => 'KH-' . rand(10,99) . rand(100,999) . '.' . rand(10,99),
                    'current_mileage' => rand(5000,90000),
                    'appointment_number' => 'SA-' . date('Ymd') . '-KH' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                    'appointment_date' => $date->toDateString(),
                    'appointment_time' => $time,
                    'requested_services' => 'Dịch vụ KH ' . $i,
                    'service_description' => 'Bảo dưỡng định kỳ và kiểm tra tổng quát #'.$i,
                    'status' => ['scheduled','confirmed','in_progress','completed'][array_rand(['scheduled','confirmed','in_progress','completed'])],
                    'priority' => ['low','medium','high','urgent'][array_rand(['low','medium','high','urgent'])],
                    'is_warranty_work' => (bool) rand(0,1),
                    'estimated_cost' => rand(300000,3000000),
                ]);
            }
        }
    }
}


