<?php

namespace Database\Seeders;

use App\Models\Dealership;
use Illuminate\Database\Seeder;

class DealershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dealerships = [
            [
                'name' => 'Công ty TNHH Ô tô Việt Nam',
                'code' => 'VNAUTO',
                'description' => 'Đại lý chính thức của các hãng xe hàng đầu thế giới tại Việt Nam',
                'phone' => '1900-1234',
                'email' => 'info@vnauto.vn',
                'address' => '123 Đường Lê Lợi',
                'city' => 'Hà Nội',
                'country' => 'Việt Nam',
                'is_active' => true,
                'created_at' => now()->subYears(4),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'name' => 'Công ty TNHH Ô tô Miền Nam',
                'code' => 'MNAUTO',
                'description' => 'Đại lý xe hơi hàng đầu khu vực miền Nam với dịch vụ toàn diện',
                'phone' => '1900-5678',
                'email' => 'info@mnauto.vn',
                'address' => '456 Đường Nguyễn Huệ',
                'city' => 'TP. Hồ Chí Minh',
                'country' => 'Việt Nam',
                'is_active' => true,
                'created_at' => now()->subYears(4),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'name' => 'Công ty TNHH Ô tô Miền Trung',
                'code' => 'MTAUTO',
                'description' => 'Đại lý xe hơi uy tín tại khu vực miền Trung với đội ngũ chuyên nghiệp',
                'phone' => '1900-9012',
                'email' => 'info@mtauto.vn',
                'address' => '789 Đường Trần Phú',
                'city' => 'Đà Nẵng',
                'country' => 'Việt Nam',
                'is_active' => true,
                'created_at' => now()->subYears(4),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'name' => 'Công ty TNHH Ô tô Đông Nam Bộ',
                'code' => 'DNAUTO',
                'description' => 'Đại lý xe hơi chất lượng cao tại khu vực Đông Nam Bộ',
                'phone' => '1900-3456',
                'email' => 'info@dnauto.vn',
                'address' => '321 Đường Võ Văn Tần',
                'city' => 'Biên Hòa',
                'country' => 'Việt Nam',
                'is_active' => true,
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'name' => 'Công ty TNHH Ô tô Tây Nam Bộ',
                'code' => 'TNAUTO',
                'description' => 'Đại lý xe hơi hàng đầu khu vực Tây Nam Bộ với dịch vụ chuyên nghiệp',
                'phone' => '1900-7890',
                'email' => 'info@tnauto.vn',
                'address' => '654 Đường Nguyễn Trãi',
                'city' => 'Cần Thơ',
                'country' => 'Việt Nam',
                'is_active' => true,
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subMonths(2)
            ]
        ];

        foreach ($dealerships as $dealership) {
            Dealership::create($dealership);
        }
    }
}
