<?php

namespace Database\Seeders;

use App\Models\Showroom;
use App\Models\Dealership;
use Illuminate\Database\Seeder;

class ShowroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dealerships = Dealership::all();

        $showrooms = [
            [
                'dealership_id' => $dealerships->where('code', 'VNAUTO')->first()->id,
                'name' => 'Showroom Hà Nội - Trung tâm',
                'code' => 'HN_CENTER',
                'description' => 'Showroom chính tại trung tâm Hà Nội với đầy đủ các dòng xe',
                'phone' => '024-1234-5678',
                'email' => 'hanoi@vnauto.vn',
                'address' => '123 Đường Lê Lợi, Quận Hoàn Kiếm',
                'city' => 'Hà Nội',
                'is_active' => true,
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'dealership_id' => $dealerships->where('code', 'VNAUTO')->first()->id,
                'name' => 'Showroom Hà Nội - Tây Hồ',
                'code' => 'HN_TAYHO',
                'description' => 'Showroom cao cấp tại khu vực Tây Hồ với view hồ đẹp',
                'phone' => '024-1234-5679',
                'email' => 'tayho@vnauto.vn',
                'address' => '456 Đường Lạc Long Quân, Quận Tây Hồ',
                'city' => 'Hà Nội',
                'is_active' => true,
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'dealership_id' => $dealerships->where('code', 'MNAUTO')->first()->id,
                'name' => 'Showroom TP.HCM - Quận 1',
                'code' => 'HCM_Q1',
                'description' => 'Showroom chính tại trung tâm TP.HCM với đa thương hiệu',
                'phone' => '028-5678-1234',
                'email' => 'q1@mnauto.vn',
                'address' => '456 Đường Nguyễn Huệ, Quận 1',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'dealership_id' => $dealerships->where('code', 'MNAUTO')->first()->id,
                'name' => 'Showroom TP.HCM - Quận 7',
                'code' => 'HCM_Q7',
                'description' => 'Showroom hiện đại tại khu vực Phú Mỹ Hưng',
                'phone' => '028-5678-1235',
                'email' => 'q7@mnauto.vn',
                'address' => '789 Đường Nguyễn Thị Thập, Quận 7',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'dealership_id' => $dealerships->where('code', 'MTAUTO')->first()->id,
                'name' => 'Showroom Đà Nẵng - Trung tâm',
                'code' => 'DN_CENTER',
                'description' => 'Showroom chính tại trung tâm Đà Nẵng',
                'phone' => '0236-4444-5555',
                'email' => 'danang@mtauto.vn',
                'address' => '321 Đường Trần Phú, Quận Hải Châu',
                'city' => 'Đà Nẵng',
                'is_active' => true,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subMonths(2)
            ],
            [
                'dealership_id' => $dealerships->where('code', 'DNAUTO')->first()->id,
                'name' => 'Showroom Biên Hòa - Trung tâm',
                'code' => 'BH_CENTER',
                'description' => 'Showroom chính tại trung tâm Biên Hòa',
                'phone' => '0251-3456-7890',
                'email' => 'bienhoa@dnauto.vn',
                'address' => '321 Đường Võ Văn Tần, TP. Biên Hòa',
                'city' => 'Biên Hòa',
                'is_active' => true,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subMonths(2)
            ],
            [
                'dealership_id' => $dealerships->where('code', 'TNAUTO')->first()->id,
                'name' => 'Showroom Cần Thơ - Trung tâm',
                'code' => 'CT_CENTER',
                'description' => 'Showroom chính tại trung tâm Cần Thơ',
                'phone' => '0292-7890-1234',
                'email' => 'cantho@tnauto.vn',
                'address' => '654 Đường Nguyễn Trãi, Quận Ninh Kiều',
                'city' => 'Cần Thơ',
                'is_active' => true,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subMonths(2)
            ]
        ];

        foreach ($showrooms as $showroom) {
            Showroom::create($showroom);
        }
    }
}
