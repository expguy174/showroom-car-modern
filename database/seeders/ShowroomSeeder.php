<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Showroom;
use App\Models\Dealership;

class ShowroomSeeder extends Seeder
{
    public function run(): void
    {
        $south = Dealership::where('code','SR-SOUTH')->first();
        $north = Dealership::where('code','SR-NORTH')->first();

        $showrooms = array_filter([
            $south ? [
                'dealership_id' => $south->id,
                'name' => 'Showroom Quận 1',
                'code' => 'SR-Q1',
                'description' => 'Showroom trung tâm Quận 1.',
                'phone' => '02838223344',
                'email' => 'q1@showroom.vn',
                'address' => '12 Nguyễn Huệ, Quận 1',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
            ] : null,
            $north ? [
                'dealership_id' => $north->id,
                'name' => 'Showroom Hoàn Kiếm',
                'code' => 'SR-HK',
                'description' => 'Showroom trung tâm Hoàn Kiếm.',
                'phone' => '02438224455',
                'email' => 'hk@showroom.vn',
                'address' => '25 Lý Thường Kiệt, Hoàn Kiếm',
                'city' => 'Hà Nội',
                'is_active' => true,
            ] : null,
            $south ? [
                'dealership_id' => $south->id,
                'name' => 'Showroom Thủ Đức',
                'code' => 'SR-TD',
                'description' => 'Showroom khu Đông TP.HCM.',
                'phone' => '02838999999',
                'email' => 'td@showroom.vn',
                'address' => 'Xa lộ Hà Nội, Thủ Đức',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
            ] : null,
            $south ? [
                'dealership_id' => $south->id,
                'name' => 'Showroom Tân Bình',
                'code' => 'SR-TB',
                'description' => 'Gần sân bay Tân Sơn Nhất.',
                'phone' => '02838888888',
                'email' => 'tb@showroom.vn',
                'address' => 'Cộng Hòa, Tân Bình',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
            ] : null,
            $north ? [
                'dealership_id' => $north->id,
                'name' => 'Showroom Cầu Giấy',
                'code' => 'SR-CG',
                'description' => 'Khu vực Cầu Giấy.',
                'phone' => '02433778899',
                'email' => 'cg@showroom.vn',
                'address' => 'Phạm Văn Đồng, Cầu Giấy',
                'city' => 'Hà Nội',
                'is_active' => true,
            ] : null,
            $north ? [
                'dealership_id' => $north->id,
                'name' => 'Showroom Long Biên',
                'code' => 'SR-LB',
                'description' => 'Khu vực Long Biên.',
                'phone' => '02437895612',
                'email' => 'lb@showroom.vn',
                'address' => 'Nguyễn Văn Linh, Long Biên',
                'city' => 'Hà Nội',
                'is_active' => true,
            ] : null,
        ]);

        foreach ($showrooms as $s) {
            Showroom::updateOrCreate(['code' => $s['code']], $s);
        }
    }
}


