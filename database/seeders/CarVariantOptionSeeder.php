<?php

namespace Database\Seeders;

use App\Models\CarVariantOption;
use App\Models\CarVariant;
use Illuminate\Database\Seeder;

class CarVariantOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = CarVariant::all();

        $options = [
            // Toyota Vios G Options
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'exterior',
                'option_name' => 'Gói trang trí ngoại thất',
                'description' => 'Gói trang trí ngoại thất bao gồm viền chrome và đèn LED',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 5000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => true,
                'is_recommended' => false,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'interior',
                'option_name' => 'Gói nội thất cao cấp',
                'description' => 'Gói nội thất cao cấp với ghế da và trang trí nội thất',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 8000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => false,
                'is_recommended' => true,
                'sort_order' => 2
            ],

            // Toyota Vios E Options
            [
                'car_variant_id' => $variants->where('name', 'Vios E')->first()->id,
                'category' => 'exterior',
                'option_name' => 'Gói trang trí cơ bản',
                'description' => 'Gói trang trí cơ bản với viền chrome',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 3000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => false,
                'is_recommended' => false,
                'sort_order' => 1
            ],

            // Honda City G Options
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'exterior',
                'option_name' => 'Gói thể thao',
                'description' => 'Gói thể thao với cản trước/sau và viền thể thao',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 6000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => true,
                'is_recommended' => false,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'audio',
                'option_name' => 'Hệ thống âm thanh cao cấp',
                'description' => 'Hệ thống âm thanh cao cấp với loa subwoofer',
                'availability' => 'optional',
                'type' => 'standalone',
                'price' => 4000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => false,
                'is_recommended' => true,
                'sort_order' => 2
            ],

            // Honda City RS Options
            [
                'car_variant_id' => $variants->where('name', 'City RS')->first()->id,
                'category' => 'exterior',
                'option_name' => 'Gói RS đặc biệt',
                'description' => 'Gói RS đặc biệt với thiết kế độc quyền',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 10000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => true,
                'is_recommended' => true,
                'sort_order' => 1
            ],

            // Toyota Innova G Options
            [
                'car_variant_id' => $variants->where('name', 'Innova G')->first()->id,
                'category' => 'interior',
                'option_name' => 'Gói nội thất gia đình',
                'description' => 'Gói nội thất gia đình với ghế 7 chỗ cao cấp',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 12000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => true,
                'is_recommended' => true,
                'sort_order' => 1
            ],

            // Ford Ranger XLT Options
            [
                'car_variant_id' => $variants->where('name', 'Ranger XLT')->first()->id,
                'category' => 'exterior',
                'option_name' => 'Gói off-road',
                'description' => 'Gói off-road với lốp địa hình và bảo vệ gầm',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 15000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => false,
                'is_recommended' => true,
                'sort_order' => 1
            ],

            // Hyundai Accent G Options
            [
                'car_variant_id' => $variants->where('name', 'Accent G')->first()->id,
                'category' => 'exterior',
                'option_name' => 'Gói trang trí cơ bản',
                'description' => 'Gói trang trí cơ bản với viền chrome',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 2000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => false,
                'is_recommended' => false,
                'sort_order' => 1
            ],

            // Mercedes-Benz C-Class C200 Options
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'interior',
                'option_name' => 'Gói nội thất AMG',
                'description' => 'Gói nội thất AMG với ghế thể thao và trang trí cao cấp',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 25000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => true,
                'is_recommended' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'performance',
                'option_name' => 'Gói động cơ tăng áp',
                'description' => 'Gói tăng hiệu suất động cơ với chip tuning',
                'availability' => 'optional',
                'type' => 'standalone',
                'price' => 30000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => false,
                'is_recommended' => false,
                'sort_order' => 2
            ],

            // BMW 3 Series 320i Options
            [
                'car_variant_id' => $variants->where('name', '3 Series 320i')->first()->id,
                'category' => 'exterior',
                'option_name' => 'Gói M Sport',
                'description' => 'Gói M Sport với thiết kế thể thao và bánh xe M',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 35000000,
                'is_included' => false,
                'is_active' => true,
                'is_popular' => true,
                'is_recommended' => true,
                'sort_order' => 1
            ],

            // VinFast VF 8 Plus Options
            [
                'car_variant_id' => $variants->where('name', 'VF 8 Plus')->first()->id,
                'category' => 'navigation',
                'option_name' => 'Gói công nghệ cao cấp',
                'description' => 'Gói công nghệ cao cấp với hệ thống thông minh',
                'availability' => 'optional',
                'type' => 'package',
                'price' => 20000000,
                'is_included' => false,
                    'is_active' => true,
                'is_popular' => true,
                'is_recommended' => true,
                'sort_order' => 1
            ]
        ];

        foreach ($options as $option) {
            CarVariantOption::create($option);
        }
    }
}
