<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Showroom;
use App\Models\Dealership;

class ShowroomSeeder extends Seeder
{
    public function run(): void
    {
        $toyota = Dealership::where('code', 'DEALER-TOYOTA-VN')->first();
        $honda = Dealership::where('code', 'DEALER-HONDA-VN')->first();
        $ford = Dealership::where('code', 'DEALER-FORD-VN')->first();
        $mazda = Dealership::where('code', 'DEALER-MAZDA-VN')->first();

        $showrooms = array_filter([
            // Toyota Showrooms
            $toyota ? [
                'dealership_id' => $toyota->id,
                'name' => 'Toyota Cầu Giấy',
                'code' => 'SR-TOYOTA-CG',
                'description' => 'Showroom Toyota chính hãng tại Cầu Giấy, Hà Nội. Đầy đủ dòng xe và dịch vụ chăm sóc khách hàng.',
                'phone' => '02438224455',
                'email' => 'caugiay@toyota.com.vn',
                'address' => '458 Phạm Văn Đồng, Cầu Giấy',
                'city' => 'Hà Nội',
                'is_active' => true,
            ] : null,
            $toyota ? [
                'dealership_id' => $toyota->id,
                'name' => 'Toyota Mỹ Đình',
                'code' => 'SR-TOYOTA-MD',
                'description' => 'Showroom Toyota Mỹ Đình - Quy mô lớn, hiện đại, đầy đủ dòng xe.',
                'phone' => '02437895612',
                'email' => 'mydinh@toyota.com.vn',
                'address' => '222 Phạm Hùng, Nam Từ Liêm',
                'city' => 'Hà Nội',
                'is_active' => true,
            ] : null,
            $toyota ? [
                'dealership_id' => $toyota->id,
                'name' => 'Toyota Quận 1',
                'code' => 'SR-TOYOTA-Q1',
                'description' => 'Toyota Quận 1 - Trung tâm thành phố, tiện lợi cho khách hàng.',
                'phone' => '02838223344',
                'email' => 'quan1@toyota.com.vn',
                'address' => '12 Nguyễn Huệ, Quận 1',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
            ] : null,
            $toyota ? [
                'dealership_id' => $toyota->id,
                'name' => 'Toyota Thủ Đức',
                'code' => 'SR-TOYOTA-TD',
                'description' => 'Showroom Toyota khu vực Đông Sài Gòn.',
                'phone' => '02838999999',
                'email' => 'thuduc@toyota.com.vn',
                'address' => 'Xa lộ Hà Nội, TP. Thủ Đức',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
            ] : null,
            
            // Honda Showrooms
            $honda ? [
                'dealership_id' => $honda->id,
                'name' => 'Honda Láng Hạ',
                'code' => 'SR-HONDA-LH',
                'description' => 'Honda Láng Hạ - Showroom chính hãng tại trung tâm Hà Nội.',
                'phone' => '02433778899',
                'email' => 'langha@honda.com.vn',
                'address' => '68 Láng Hạ, Đống Đa',
                'city' => 'Hà Nội',
                'is_active' => true,
            ] : null,
            $honda ? [
                'dealership_id' => $honda->id,
                'name' => 'Honda Long Biên',
                'code' => 'SR-HONDA-LB',
                'description' => 'Honda Long Biên - Dịch vụ chuyên nghiệp, nhiệt tình.',
                'phone' => '02437123456',
                'email' => 'longbien@honda.com.vn',
                'address' => 'Nguyễn Văn Linh, Long Biên',
                'city' => 'Hà Nội',
                'is_active' => true,
            ] : null,
            $honda ? [
                'dealership_id' => $honda->id,
                'name' => 'Honda Bình Thạnh',
                'code' => 'SR-HONDA-BT',
                'description' => 'Honda Bình Thạnh - Showroom xe Honda chính hãng tại TP.HCM.',
                'phone' => '02838777777',
                'email' => 'binhthanh@honda.com.vn',
                'address' => 'Điện Biên Phủ, Bình Thạnh',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
            ] : null,
            
            // Ford Showrooms
            $ford ? [
                'dealership_id' => $ford->id,
                'name' => 'Ford Hà Đông',
                'code' => 'SR-FORD-HD',
                'description' => 'Ford Hà Đông - Đại lý ủy quyền chính hãng Ford.',
                'phone' => '02433456789',
                'email' => 'hadong@ford.com.vn',
                'address' => 'Quang Trung, Hà Đông',
                'city' => 'Hà Nội',
                'is_active' => true,
            ] : null,
            $ford ? [
                'dealership_id' => $ford->id,
                'name' => 'Ford Tân Bình',
                'code' => 'SR-FORD-TB',
                'description' => 'Ford Tân Bình - Gần sân bay Tân Sơn Nhất.',
                'phone' => '02838888888',
                'email' => 'tanbinh@ford.com.vn',
                'address' => '235 Cộng Hòa, Tân Bình',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
            ] : null,
            
            // Mazda Showrooms
            $mazda ? [
                'dealership_id' => $mazda->id,
                'name' => 'Mazda Gò Vấp',
                'code' => 'SR-MAZDA-GV',
                'description' => 'Thaco Mazda Gò Vấp - Showroom hiện đại với đầy đủ dòng xe Mazda.',
                'phone' => '02838666666',
                'email' => 'govap@mazda.com.vn',
                'address' => 'Quang Trung, Gò Vấp',
                'city' => 'TP. Hồ Chí Minh',
                'is_active' => true,
            ] : null,
        ]);

        foreach ($showrooms as $s) {
            Showroom::updateOrCreate(['code' => $s['code']], $s);
        }
    }
}


