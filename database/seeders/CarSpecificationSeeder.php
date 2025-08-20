<?php

namespace Database\Seeders;

use App\Models\CarSpecification;
use App\Models\CarVariant;
use Illuminate\Database\Seeder;

class CarSpecificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = CarVariant::all();

        $specifications = [
            // Toyota Vios G Specifications
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Loại động cơ',
                'spec_value' => '1.5L 4-cylinder DOHC',
                'unit' => null,
                'description' => 'Động cơ xăng 4 xi-lanh thẳng hàng',
                'is_important' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Công suất tối đa',
                'spec_value' => '107',
                'unit' => 'hp',
                'description' => 'Công suất tối đa tại 6,000 rpm',
                'is_important' => true,
                'sort_order' => 2
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Mô-men xoắn tối đa',
                'spec_value' => '140',
                'unit' => 'Nm',
                'description' => 'Mô-men xoắn tối đa tại 4,200 rpm',
                'is_important' => true,
                'sort_order' => 3
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'transmission',
                'spec_name' => 'Hộp số',
                'spec_value' => 'Số tự động',
                'unit' => null,
                'description' => 'Hộp số tự động 6 cấp',
                'is_important' => true,
                'sort_order' => 4
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'performance',
                'spec_name' => 'Tốc độ tối đa',
                'spec_value' => '180',
                'unit' => 'km/h',
                'description' => 'Tốc độ tối đa',
                'is_important' => true,
                'sort_order' => 5
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'performance',
                'spec_name' => 'Tăng tốc 0-100 km/h',
                'spec_value' => '12.5',
                'unit' => 'giây',
                'description' => 'Thời gian tăng tốc từ 0-100 km/h',
                'is_important' => true,
                'sort_order' => 6
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'fuel',
                'spec_name' => 'Tiêu thụ nhiên liệu',
                'spec_value' => '5.8',
                'unit' => 'L/100km',
                'description' => 'Tiêu thụ nhiên liệu trung bình',
                'is_important' => true,
                'sort_order' => 7
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'dimensions',
                'spec_name' => 'Chiều dài',
                'spec_value' => '4425',
                'unit' => 'mm',
                'description' => 'Chiều dài tổng thể',
                'is_important' => false,
                'sort_order' => 8
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'dimensions',
                'spec_name' => 'Chiều rộng',
                'spec_value' => '1730',
                'unit' => 'mm',
                'description' => 'Chiều rộng tổng thể',
                'is_important' => false,
                'sort_order' => 9
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'dimensions',
                'spec_name' => 'Chiều cao',
                'spec_value' => '1475',
                'unit' => 'mm',
                'description' => 'Chiều cao tổng thể',
                'is_important' => false,
                'sort_order' => 10
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'dimensions',
                'spec_name' => 'Chiều dài cơ sở',
                'spec_value' => '2550',
                'unit' => 'mm',
                'description' => 'Khoảng cách giữa hai trục',
                'is_important' => false,
                'sort_order' => 11
            ],

            // Honda City G Specifications
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Loại động cơ',
                'spec_value' => '1.5L 4-cylinder DOHC i-VTEC',
                'unit' => null,
                'description' => 'Động cơ xăng 4 xi-lanh với công nghệ i-VTEC',
                'is_important' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Công suất tối đa',
                'spec_value' => '119',
                'unit' => 'hp',
                'description' => 'Công suất tối đa tại 6,600 rpm',
                'is_important' => true,
                'sort_order' => 2
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Mô-men xoắn tối đa',
                'spec_value' => '145',
                'unit' => 'Nm',
                'description' => 'Mô-men xoắn tối đa tại 4,300 rpm',
                'is_important' => true,
                'sort_order' => 3
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'transmission',
                'spec_name' => 'Hộp số',
                'spec_value' => 'Số tự động',
                'unit' => null,
                'description' => 'Hộp số tự động CVT',
                'is_important' => true,
                'sort_order' => 4
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'performance',
                'spec_name' => 'Tốc độ tối đa',
                'spec_value' => '185',
                'unit' => 'km/h',
                'description' => 'Tốc độ tối đa',
                'is_important' => true,
                'sort_order' => 5
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'performance',
                'spec_name' => 'Tăng tốc 0-100 km/h',
                'spec_value' => '11.8',
                'unit' => 'giây',
                'description' => 'Thời gian tăng tốc từ 0-100 km/h',
                'is_important' => true,
                'sort_order' => 6
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'fuel',
                'spec_name' => 'Tiêu thụ nhiên liệu',
                'spec_value' => '6.0',
                'unit' => 'L/100km',
                'description' => 'Tiêu thụ nhiên liệu trung bình',
                'is_important' => true,
                'sort_order' => 7
            ],

            // Toyota Fortuner G Specifications
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Loại động cơ',
                'spec_value' => '2.8L 4-cylinder DOHC Turbo Diesel',
                'unit' => null,
                'description' => 'Động cơ diesel 4 xi-lanh với turbo',
                'is_important' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Công suất tối đa',
                'spec_value' => '204',
                'unit' => 'hp',
                'description' => 'Công suất tối đa tại 3,400 rpm',
                'is_important' => true,
                'sort_order' => 2
            ],
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Mô-men xoắn tối đa',
                'spec_value' => '500',
                'unit' => 'Nm',
                'description' => 'Mô-men xoắn tối đa tại 1,600-2,400 rpm',
                'is_important' => true,
                'sort_order' => 3
            ],
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'category' => 'transmission',
                'spec_name' => 'Hộp số',
                'spec_value' => 'Số tự động',
                'unit' => null,
                'description' => 'Hộp số tự động 6 cấp',
                'is_important' => true,
                'sort_order' => 4
            ],
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'category' => 'performance',
                'spec_name' => 'Tốc độ tối đa',
                'spec_value' => '180',
                'unit' => 'km/h',
                'description' => 'Tốc độ tối đa',
                'is_important' => true,
                'sort_order' => 5
            ],
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'category' => 'performance',
                'spec_name' => 'Tăng tốc 0-100 km/h',
                'spec_value' => '10.8',
                'unit' => 'giây',
                'description' => 'Thời gian tăng tốc từ 0-100 km/h',
                'is_important' => true,
                'sort_order' => 6
            ],
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'category' => 'fuel',
                'spec_name' => 'Tiêu thụ nhiên liệu',
                'spec_value' => '7.2',
                'unit' => 'L/100km',
                'description' => 'Tiêu thụ nhiên liệu trung bình',
                'is_important' => true,
                'sort_order' => 7
            ],

            // Mercedes-Benz C-Class C200 Specifications
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Loại động cơ',
                'spec_value' => '2.0L 4-cylinder Turbo',
                'unit' => null,
                'description' => 'Động cơ xăng 4 xi-lanh với turbo',
                'is_important' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Công suất tối đa',
                'spec_value' => '197',
                'unit' => 'hp',
                'description' => 'Công suất tối đa tại 5,500 rpm',
                'is_important' => true,
                'sort_order' => 2
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'engine',
                'spec_name' => 'Mô-men xoắn tối đa',
                'spec_value' => '300',
                'unit' => 'Nm',
                'description' => 'Mô-men xoắn tối đa tại 1,800-4,000 rpm',
                'is_important' => true,
                'sort_order' => 3
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'transmission',
                'spec_name' => 'Hộp số',
                'spec_value' => 'Số tự động',
                'unit' => null,
                'description' => 'Hộp số tự động 9 cấp 9G-TRONIC',
                'is_important' => true,
                'sort_order' => 4
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'performance',
                'spec_name' => 'Tốc độ tối đa',
                'spec_value' => '246',
                'unit' => 'km/h',
                'description' => 'Tốc độ tối đa',
                'is_important' => true,
                'sort_order' => 5
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'performance',
                'spec_name' => 'Tăng tốc 0-100 km/h',
                'spec_value' => '7.3',
                'unit' => 'giây',
                'description' => 'Thời gian tăng tốc từ 0-100 km/h',
                'is_important' => true,
                'sort_order' => 6
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'fuel',
                'spec_name' => 'Tiêu thụ nhiên liệu',
                'spec_value' => '6.5',
                'unit' => 'L/100km',
                'description' => 'Tiêu thụ nhiên liệu trung bình',
                'is_important' => true,
                'sort_order' => 7
            ]
        ];

        foreach ($specifications as $spec) {
            CarSpecification::create($spec);
        }
    }
}
