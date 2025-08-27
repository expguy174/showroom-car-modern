<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dealership;

class DealershipSeeder extends Seeder
{
    public function run(): void
    {
        $dealers = [
            [
                'name' => 'Showroom Miền Nam',
                'code' => 'SR-SOUTH',
                'description' => 'Hệ thống phân phối khu vực phía Nam.',
                'phone' => '02838123456',
                'email' => 'south@showroom.vn',
                'address' => '12 Nguyễn Huệ, Quận 1, TP. HCM',
                'city' => 'TP. Hồ Chí Minh',
                'country' => 'Vietnam',
                'is_active' => true,
            ],
            [
                'name' => 'Showroom Miền Bắc',
                'code' => 'SR-NORTH',
                'description' => 'Hệ thống phân phối khu vực phía Bắc.',
                'phone' => '02438123456',
                'email' => 'north@showroom.vn',
                'address' => '25 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội',
                'city' => 'Hà Nội',
                'country' => 'Vietnam',
                'is_active' => true,
            ],
        ];

        foreach ($dealers as $d) {
            Dealership::updateOrCreate(['code' => $d['code']], $d);
        }
    }
}


