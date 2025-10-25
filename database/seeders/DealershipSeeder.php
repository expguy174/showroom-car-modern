<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dealership;

class DealershipSeeder extends Seeder
{
    public function run(): void
    {
        $dealerships = [
            [
                'name' => 'Toyota Việt Nam',
                'code' => 'DEALER-TOYOTA-VN',
                'description' => 'Đại lý phân phối chính thức xe Toyota tại Việt Nam. Mạng lưới rộng khắp cả nước với nhiều showroom và trung tâm dịch vụ.',
                'phone' => '1900545591',
                'email' => 'info@toyota.com.vn',
                'address' => 'Số 315 Trường Chinh, Thanh Xuân, Hà Nội',
                'city' => 'Hà Nội',
                'country' => 'Vietnam',
                'is_active' => true,
            ],
            [
                'name' => 'Honda Việt Nam',
                'code' => 'DEALER-HONDA-VN',
                'description' => 'Công ty Honda Việt Nam - Nhà phân phối chính hãng xe ô tô Honda. Cam kết chất lượng và dịch vụ tốt nhất.',
                'phone' => '1900545592',
                'email' => 'contact@honda.com.vn',
                'address' => '1A Trường Chinh, Thanh Xuân, Hà Nội',
                'city' => 'Hà Nội',
                'country' => 'Vietnam',
                'is_active' => true,
            ],
            [
                'name' => 'Ford Việt Nam',
                'code' => 'DEALER-FORD-VN',
                'description' => 'Công ty Ford Sales & Service Việt Nam. Đại lý ủy quyền chính thức của Ford Motor Company tại Việt Nam.',
                'phone' => '1900545593',
                'email' => 'customercare@ford.com.vn',
                'address' => 'Số 18 Phạm Hùng, Nam Từ Liêm, Hà Nội',
                'city' => 'Hà Nội',
                'country' => 'Vietnam',
                'is_active' => true,
            ],
            [
                'name' => 'Mazda Việt Nam',
                'code' => 'DEALER-MAZDA-VN',
                'description' => 'Thaco Mazda - Đại lý phân phối độc quyền xe Mazda tại Việt Nam. Hệ thống showroom và dịch vụ hiện đại.',
                'phone' => '1900545594',
                'email' => 'info@mazda.com.vn',
                'address' => 'Km7 Quốc lộ 1A, Bình Thạnh, TP.HCM',
                'city' => 'TP. Hồ Chí Minh',
                'country' => 'Vietnam',
                'is_active' => true,
            ],
        ];

        foreach ($dealerships as $d) {
            Dealership::updateOrCreate(['code' => $d['code']], $d);
        }
    }
}


