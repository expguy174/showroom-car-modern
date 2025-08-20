<?php

namespace Database\Seeders;

use App\Models\CarVariantColor;
use App\Models\CarVariant;
use Illuminate\Database\Seeder;
 

class CarVariantColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = CarVariant::with('carModel.carBrand')->get();

        $searchCommons = function (string $query) {
            return null; // Always use placeholder
        };

        $colors = [
            // Toyota Vios G Colors
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai sang trọng và hiện đại',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'color_name' => 'Đen bóng',
                'color_code' => 'BLACK',
                'hex_code' => '#000000',
                'color_type' => 'solid',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu đen bóng thể thao và nam tính',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'color_name' => 'Bạc kim loại',
                'color_code' => 'SILVER',
                'hex_code' => '#C0C0C0',
                'color_type' => 'metallic',
                'availability' => 'standard',
                'price_adjustment' => 5000000,
                'is_free' => false,
                'description' => 'Màu bạc kim loại hiện đại và dễ bảo dưỡng',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3
            ],

            // Toyota Vios E Colors
            [
                'car_variant_id' => $variants->where('name', 'Vios E')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai sang trọng',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios E')->first()->id,
                'color_name' => 'Xanh dương',
                'color_code' => 'BLUE',
                'hex_code' => '#0066CC',
                'color_type' => 'solid',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu xanh dương năng động',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 2
            ],

            // Honda City G Colors
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai thể thao',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'color_name' => 'Đỏ thể thao',
                'color_code' => 'SPORT_RED',
                'hex_code' => '#FF0000',
                'color_type' => 'solid',
                'availability' => 'standard',
                'price_adjustment' => 3000000,
                'is_free' => false,
                'description' => 'Màu đỏ thể thao năng động',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2
            ],

            // Honda City RS Colors
            [
                'car_variant_id' => $variants->where('name', 'City RS')->first()->id,
                'color_name' => 'Đen bóng',
                'color_code' => 'BLACK',
                'hex_code' => '#000000',
                'color_type' => 'solid',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu đen bóng thể thao cao cấp',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'City RS')->first()->id,
                'color_name' => 'Xanh đen',
                'color_code' => 'DARK_BLUE',
                'hex_code' => '#003366',
                'color_type' => 'metallic',
                'availability' => 'standard',
                'price_adjustment' => 5000000,
                'is_free' => false,
                'description' => 'Màu xanh đen kim loại cao cấp',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 2
            ],

            // Toyota Innova G Colors
            [
                'car_variant_id' => $variants->where('name', 'Innova G')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai đa dụng',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Innova G')->first()->id,
                'color_name' => 'Bạc kim loại',
                'color_code' => 'SILVER',
                'hex_code' => '#C0C0C0',
                'color_type' => 'metallic',
                'availability' => 'standard',
                'price_adjustment' => 5000000,
                'is_free' => false,
                'description' => 'Màu bạc kim loại thực dụng',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2
            ],

            // Ford Ranger XLT Colors
            [
                'car_variant_id' => $variants->where('name', 'Ranger XLT')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai mạnh mẽ',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Ranger XLT')->first()->id,
                'color_name' => 'Xanh rừng',
                'color_code' => 'FOREST_GREEN',
                'hex_code' => '#228B22',
                'color_type' => 'metallic',
                'availability' => 'standard',
                'price_adjustment' => 5000000,
                'is_free' => false,
                'description' => 'Màu xanh rừng phù hợp với địa hình',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 2
            ],

            // Hyundai Accent G Colors
            [
                'car_variant_id' => $variants->where('name', 'Accent G')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai hiện đại',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Accent G')->first()->id,
                'color_name' => 'Xanh dương',
                'color_code' => 'BLUE',
                'hex_code' => '#0066CC',
                'color_type' => 'solid',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu xanh dương năng động',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 2
            ],

            // Mercedes-Benz C-Class C200 Colors
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai sang trọng',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'color_name' => 'Đen bóng',
                'color_code' => 'BLACK',
                'hex_code' => '#000000',
                'color_type' => 'solid',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu đen bóng đẳng cấp',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2
            ],

            // BMW 3 Series 320i Colors
            [
                'car_variant_id' => $variants->where('name', '3 Series 320i')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai thể thao',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', '3 Series 320i')->first()->id,
                'color_name' => 'Xanh BMW',
                'color_code' => 'BMW_BLUE',
                'hex_code' => '#0066CC',
                'color_type' => 'metallic',
                'availability' => 'standard',
                'price_adjustment' => 8000000,
                'is_free' => false,
                'description' => 'Màu xanh BMW truyền thống',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2
            ],

            // VinFast VF 8 Plus Colors
            [
                'car_variant_id' => $variants->where('name', 'VF 8 Plus')->first()->id,
                'color_name' => 'Trắng ngọc trai',
                'color_code' => 'PEARL_WHITE',
                'hex_code' => '#FFFFFF',
                'color_type' => 'pearlescent',
                'availability' => 'standard',
                'price_adjustment' => 0,
                'is_free' => true,
                'description' => 'Màu trắng ngọc trai hiện đại',
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'VF 8 Plus')->first()->id,
                'color_name' => 'Xanh VinFast',
                'color_code' => 'VINFAST_BLUE',
                'hex_code' => '#0066CC',
                'color_type' => 'metallic',
                'availability' => 'standard',
                'price_adjustment' => 5000000,
                'is_free' => false,
                'description' => 'Màu xanh VinFast độc đáo',
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 2
            ]
        ];

        foreach ($colors as $color) {
            $variant = $variants->firstWhere('id', $color['car_variant_id']);
            $name = $variant?->name ?: 'Sản phẩm';
            $label = $name;
            $resolved = 'https://placehold.co/800x600/111827/ffffff?text=' . urlencode($label);
            
            // Tạo màu sắc
            $colorModel = CarVariantColor::create($color);
            
            // Tạo ảnh màu trong bảng car_variant_images
            $this->createColorImages($colorModel, $resolved);
        }
    }
    
    /**
     * Tạo ảnh màu trong bảng car_variant_images
     */
    private function createColorImages(CarVariantColor $color, string $baseImageUrl): void
    {
        $images = [
            [
                'image_type' => 'color_main',
                'alt_text' => "Màu {$color->color_name}",
                'caption' => "Màu {$color->color_name} - {$color->carVariant->name}",
                'title' => "Màu {$color->color_name}",
                'is_main' => true,
                'sort_order' => 1
            ],
            [
                'image_type' => 'color_swatch',
                'alt_text' => "Mẫu màu {$color->color_name}",
                'caption' => "Mẫu màu {$color->color_name}",
                'title' => "Mẫu màu {$color->color_name}",
                'is_main' => false,
                'sort_order' => 2
            ],
            [
                'image_type' => 'color_exterior',
                'alt_text' => "Ngoại thất màu {$color->color_name}",
                'caption' => "Ngoại thất màu {$color->color_name}",
                'title' => "Ngoại thất màu {$color->color_name}",
                'is_main' => false,
                'sort_order' => 3
            ],
            [
                'image_type' => 'color_interior',
                'alt_text' => "Nội thất màu {$color->color_name}",
                'caption' => "Nội thất màu {$color->color_name}",
                'title' => "Nội thất màu {$color->color_name}",
                'is_main' => false,
                'sort_order' => 4
            ]
        ];
        
        foreach ($images as $imageData) {
            $color->carVariant->images()->create([
                'car_variant_color_id' => $color->id,
                'image_path' => $baseImageUrl,
                'image_url' => $baseImageUrl,
                'alt_text' => $imageData['alt_text'],
                'caption' => $imageData['caption'],
                'title' => $imageData['title'],
                'image_type' => $imageData['image_type'],
                'is_main' => $imageData['is_main'],
                'is_active' => true,
                'sort_order' => $imageData['sort_order'],
                'color_variant' => $color->color_name
            ]);
        }
    }
}
