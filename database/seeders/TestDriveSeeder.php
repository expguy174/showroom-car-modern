<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestDrive;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Showroom;
use Illuminate\Support\Str;

class TestDriveSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role','user')->get();
        $variants = CarVariant::pluck('id');
        $showrooms = Showroom::all();
        if ($variants->isEmpty() || $showrooms->isEmpty() || $users->isEmpty()) return;

        $count = 120;
        for ($i = 1; $i <= $count; $i++) {
            $userId = optional($users->random())->id;
            $variantId = $variants->random();
            $showroom = $showrooms->random();
            $isFuture = (bool) rand(0,1);
            $dateBase = $isFuture ? now()->addDays(rand(1,20)) : now()->subDays(rand(1,20));
            $date = $dateBase;
            $time = str_pad((string) rand(9,16), 2, '0', STR_PAD_LEFT) . ':' . (rand(0,1) ? '00' : '30');
            $statusOptions = ['pending','confirmed','completed','cancelled'];
            $status = $statusOptions[array_rand($statusOptions)];
            $confirmedAt = in_array($status, ['confirmed','completed']) ? now()->subDays(rand(1,3)) : null;
            $completedAt = $status === 'completed' ? now()->subDays(rand(0,2)) : null;
            TestDrive::create([
                'test_drive_number' => 'TD-' . date('Ymd') . '-' . str_pad((string) $i, 4, '0', STR_PAD_LEFT) . '-' . Str::upper(Str::random(4)),
                'user_id' => $userId,
                'car_variant_id' => $variantId,
                'showroom_id' => $showroom->id,
                'preferred_date' => $date->toDateString(),
                'preferred_time' => $time,
                'duration_minutes' => [20,30,45,60][array_rand([20,30,45,60])],
                'location' => $showroom->address,
                'notes' => null,
                'special_requirements' => null,
                'has_experience' => (bool) rand(0,1),
                'experience_level' => ['beginner','intermediate','advanced'][array_rand(['beginner','intermediate','advanced'])],
                'status' => $status,
                'test_drive_type' => ['individual','group','virtual'][array_rand(['individual','group','virtual'])],
                'confirmed_at' => $confirmedAt,
                'completed_at' => $completedAt,
                'feedback' => null,
                'satisfaction_rating' => null,
            ]);
        }

        // Ensure at least one completed test drive (no rating) for testing the rating form
        $user = $users->first();
        $variantId = $variants->first();
        $showroom = $showrooms->first();
        TestDrive::create([
            'test_drive_number' => 'TD-' . date('Ymd') . '-TEST-' . Str::upper(Str::random(5)),
            'user_id' => $user->id,
            'car_variant_id' => $variantId,
            'showroom_id' => $showroom->id,
            'preferred_date' => now()->subDays(3)->toDateString(),
            'preferred_time' => '10:00:00',
            'duration_minutes' => 30,
            'location' => $showroom->address,
            'notes' => 'Buổi thử tiêu chuẩn để test chức năng đánh giá',
            'special_requirements' => null,
            'has_experience' => true,
            'experience_level' => 'intermediate',
            'status' => 'completed',
            'test_drive_type' => 'individual',
            'confirmed_at' => now()->subDays(2),
            'completed_at' => now()->subDay(),
            'feedback' => null,
            'satisfaction_rating' => null,
        ]);
    }
}


