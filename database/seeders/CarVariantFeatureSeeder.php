<?php

namespace Database\Seeders;

use App\Models\CarVariantFeature;
use App\Models\CarVariant;
use Illuminate\Database\Seeder;

class CarVariantFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = CarVariant::all();

        $features = [
            // Toyota Vios G Features
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS giúp tăng độ an toàn khi phanh gấp',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Túi khí đôi',
                'description' => 'Hệ thống túi khí đôi bảo vệ người lái và hành khách phía trước',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'category' => 'comfort',
                'feature_name' => 'Điều hòa tự động',
                'description' => 'Hệ thống điều hòa tự động với điều khiển nhiệt độ chính xác',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3
            ],

            // Toyota Vios E Features
            [
                'car_variant_id' => $variants->where('name', 'Vios E')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS tiêu chuẩn',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios E')->first()->id,
                'category' => 'comfort',
                'feature_name' => 'Điều hòa thủ công',
                'description' => 'Hệ thống điều hòa thủ công cơ bản',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2
            ],

            // Honda City G Features
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS với phân phối lực phanh điện tử',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'category' => 'technology',
                'feature_name' => 'Màn hình cảm ứng 8 inch',
                'description' => 'Màn hình cảm ứng 8 inch với Apple CarPlay và Android Auto',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Honda City RS Features
            [
                'car_variant_id' => $variants->where('name', 'City RS')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS nâng cao',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'City RS')->first()->id,
                'category' => 'performance',
                'feature_name' => 'Chế độ thể thao',
                'description' => 'Chế độ lái thể thao với phản ứng động cơ nhanh hơn',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Toyota Innova G Features
            [
                'car_variant_id' => $variants->where('name', 'Innova G')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS cho xe đa dụng',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Innova G')->first()->id,
                'category' => 'comfort',
                'feature_name' => 'Ghế 7 chỗ',
                'description' => 'Ghế 7 chỗ với không gian rộng rãi cho gia đình',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Ford Ranger XLT Features
            [
                'car_variant_id' => $variants->where('name', 'Ranger XLT')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS cho xe tải',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Ranger XLT')->first()->id,
                'category' => 'performance',
                'feature_name' => 'Động cơ 2.0L Turbo',
                'description' => 'Động cơ 2.0L Turbo mạnh mẽ với khả năng tải trọng cao',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Hyundai Accent G Features
            [
                'car_variant_id' => $variants->where('name', 'Accent G')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS cơ bản',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Accent G')->first()->id,
                'category' => 'comfort',
                'feature_name' => 'Điều hòa thủ công',
                'description' => 'Hệ thống điều hòa thủ công tiết kiệm nhiên liệu',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2
            ],

            // Mercedes-Benz C-Class C200 Features
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS cao cấp với nhiều tính năng an toàn',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'category' => 'interior',
                'feature_name' => 'Nội thất da cao cấp',
                'description' => 'Nội thất da cao cấp với thiết kế sang trọng',
                'availability' => 'standard',
                'importance' => 'luxury',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // BMW 3 Series 320i Features
            [
                'car_variant_id' => $variants->where('name', '3 Series 320i')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS thể thao với hiệu suất cao',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', '3 Series 320i')->first()->id,
                'category' => 'performance',
                'feature_name' => 'Chế độ lái thể thao',
                'description' => 'Chế độ lái thể thao với phản ứng động cơ và hệ thống treo tối ưu',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // VinFast VF 8 Plus Features
            [
                'car_variant_id' => $variants->where('name', 'VF 8 Plus')->first()->id,
                'category' => 'safety',
                'feature_name' => 'Hệ thống phanh ABS',
                'description' => 'Hệ thống chống bó phanh ABS điện tử hiện đại',
                'availability' => 'standard',
                'importance' => 'essential',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'VF 8 Plus')->first()->id,
                'category' => 'technology',
                'feature_name' => 'Động cơ điện',
                'description' => 'Động cơ điện hiện đại với hiệu suất cao và thân thiện môi trường',
                'availability' => 'standard',
                'importance' => 'important',
                'price' => 0,
                'is_included' => true,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2
            ]
        ];

        foreach ($features as $feature) {
            CarVariantFeature::create($feature);
        }
    }
}
