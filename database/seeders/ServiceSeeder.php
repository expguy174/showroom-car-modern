<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Bảo dưỡng định kỳ',
                'code' => 'BAO_DUONG',
                'description' => 'Gói bảo dưỡng tiêu chuẩn mỗi 5.000 km.',
                'category' => 'maintenance',
                'duration_minutes' => 120,
                'price' => 1500000,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'compatible_car_brands' => json_encode(['Toyota','Hyundai','VinFast']),
                'compatible_car_models' => null,
                'compatible_car_years' => null,
                'requirements' => 'Đặt lịch trước 1 ngày',
                'warranty_months' => 0,
                'service_center_required' => false,
                'parts_included' => true,
                'labor_included' => true,
                'oil_change_included' => true,
                'filter_change_included' => false,
                'inspection_included' => true,
                'notes' => null,
            ],
            [
                'name' => 'Thay dầu động cơ',
                'code' => 'THAY_DAU',
                'description' => 'Thay dầu máy và kiểm tra 10 hạng mục.',
                'category' => 'maintenance',
                'duration_minutes' => 60,
                'price' => 600000,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'compatible_car_brands' => json_encode(['Toyota','Hyundai','VinFast']),
                'compatible_car_models' => null,
                'compatible_car_years' => null,
                'requirements' => null,
                'warranty_months' => 0,
                'service_center_required' => false,
                'parts_included' => true,
                'labor_included' => true,
                'oil_change_included' => true,
                'filter_change_included' => true,
                'inspection_included' => true,
                'notes' => null,
            ],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(['code' => $s['code']], $s);
        }
    }
}


