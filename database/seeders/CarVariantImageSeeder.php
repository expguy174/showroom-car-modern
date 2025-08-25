<?php

namespace Database\Seeders;

use App\Models\CarVariantImage;
use App\Models\CarVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 

class CarVariantImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rely on CarVariantColorSeeder for gallery images; only ensure minimal fallback for featured variants
        $variants = CarVariant::with('carModel.carBrand', 'images')->get();
        // Standardize to Commons search + placeholder fallback

        $searchCommons = function (string $query) {
            return null; // Always use placeholder
        };

        $images = [
            // Toyota Vios G Images
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'image_path' => 'car-variants/toyota-vios-g-front.jpg',
                'alt_text' => 'Toyota Vios G - Góc nhìn phía trước',
                'title' => 'Toyota Vios G - Thiết kế hiện đại',
                'description' => 'Góc nhìn phía trước của Toyota Vios G với thiết kế hiện đại và sang trọng',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G')->first()->id,
                'image_path' => 'car-variants/toyota-vios-g-interior.jpg',
                'alt_text' => 'Toyota Vios G - Nội thất',
                'title' => 'Toyota Vios G - Nội thất sang trọng',
                'description' => 'Nội thất Toyota Vios G với thiết kế sang trọng và tiện nghi',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Toyota Vios E Images
            [
                'car_variant_id' => $variants->where('name', 'Vios E')->first()->id,
                'image_path' => 'car-variants/toyota-vios-e-front.jpg',
                'alt_text' => 'Toyota Vios E - Góc nhìn phía trước',
                'title' => 'Toyota Vios E - Thiết kế cơ bản',
                'description' => 'Góc nhìn phía trước của Toyota Vios E với thiết kế cơ bản và tiết kiệm',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios E')->first()->id,
                'image_path' => 'car-variants/toyota-vios-e-interior.jpg',
                'alt_text' => 'Toyota Vios E - Nội thất',
                'title' => 'Toyota Vios E - Nội thất cơ bản',
                'description' => 'Nội thất Toyota Vios E với thiết kế cơ bản và tiện nghi',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => false,
                'sort_order' => 2
            ],

            // Honda City G Images
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'image_path' => 'car-variants/honda-city-g-front.jpg',
                'alt_text' => 'Honda City G - Góc nhìn phía trước',
                'title' => 'Honda City G - Thiết kế thể thao',
                'description' => 'Góc nhìn phía trước của Honda City G với thiết kế thể thao và năng động',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'City G')->first()->id,
                'image_path' => 'car-variants/honda-city-g-interior.jpg',
                'alt_text' => 'Honda City G - Nội thất',
                'title' => 'Honda City G - Nội thất hiện đại',
                'description' => 'Nội thất Honda City G với thiết kế hiện đại và công nghệ tiên tiến',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Honda City RS Images
            [
                'car_variant_id' => $variants->where('name', 'City RS')->first()->id,
                'image_path' => 'car-variants/honda-city-rs-front.jpg',
                'alt_text' => 'Honda City RS - Góc nhìn phía trước',
                'title' => 'Honda City RS - Thiết kế thể thao cao cấp',
                'description' => 'Góc nhìn phía trước của Honda City RS với thiết kế thể thao cao cấp',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'City RS')->first()->id,
                'image_path' => 'car-variants/honda-city-rs-interior.jpg',
                'alt_text' => 'Honda City RS - Nội thất',
                'title' => 'Honda City RS - Nội thất thể thao',
                'description' => 'Nội thất Honda City RS với thiết kế thể thao và cao cấp',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Toyota Vios G CVT Images
            [
                'car_variant_id' => $variants->where('name', 'Vios G CVT')->first()->id,
                'image_path' => 'car-variants/toyota-vios-g-cvt-front.jpg',
                'alt_text' => 'Toyota Vios G CVT - Góc nhìn phía trước',
                'title' => 'Toyota Vios G CVT - Hộp số CVT',
                'description' => 'Góc nhìn phía trước của Toyota Vios G CVT với thiết kế hiện đại',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Vios G CVT')->first()->id,
                'image_path' => 'car-variants/toyota-vios-g-cvt-interior.jpg',
                'alt_text' => 'Toyota Vios G CVT - Nội thất',
                'title' => 'Toyota Vios G CVT - Nội thất hiện đại',
                'description' => 'Nội thất Toyota Vios G CVT với thiết kế hiện đại và tiện nghi',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Toyota Innova G Images
            [
                'car_variant_id' => $variants->where('name', 'Innova G')->first()->id,
                'image_path' => 'car-variants/toyota-innova-g-front.jpg',
                'alt_text' => 'Toyota Innova G - Góc nhìn phía trước',
                'title' => 'Toyota Innova G - Thiết kế đa dụng',
                'description' => 'Góc nhìn phía trước của Toyota Innova G với thiết kế đa dụng và hiện đại',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Innova G')->first()->id,
                'image_path' => 'car-variants/toyota-innova-g-interior.jpg',
                'alt_text' => 'Toyota Innova G - Nội thất',
                'title' => 'Toyota Innova G - Nội thất đa dụng',
                'description' => 'Nội thất Toyota Innova G với thiết kế đa dụng cho gia đình',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Toyota Fortuner G Images
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'image_path' => 'car-variants/toyota-fortuner-g-front.jpg',
                'alt_text' => 'Toyota Fortuner G - Góc nhìn phía trước',
                'title' => 'Toyota Fortuner G - SUV 7 chỗ',
                'description' => 'Góc nhìn phía trước của Toyota Fortuner G với thiết kế mạnh mẽ',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Fortuner G')->first()->id,
                'image_path' => 'car-variants/toyota-fortuner-g-interior.jpg',
                'alt_text' => 'Toyota Fortuner G - Nội thất',
                'title' => 'Toyota Fortuner G - Nội thất rộng rãi',
                'description' => 'Nội thất Toyota Fortuner G rộng rãi và tiện nghi',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Honda CR-V G Images
            [
                'car_variant_id' => $variants->where('name', 'CR-V G')->first()->id,
                'image_path' => 'car-variants/honda-crv-g-front.jpg',
                'alt_text' => 'Honda CR-V G - Góc nhìn phía trước',
                'title' => 'Honda CR-V G - SUV hiện đại',
                'description' => 'Góc nhìn phía trước của Honda CR-V G với thiết kế hiện đại',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'CR-V G')->first()->id,
                'image_path' => 'car-variants/honda-crv-g-interior.jpg',
                'alt_text' => 'Honda CR-V G - Nội thất',
                'title' => 'Honda CR-V G - Nội thất hiện đại',
                'description' => 'Nội thất Honda CR-V G với thiết kế hiện đại và công nghệ tiên tiến',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Ford Ranger XLT Images
            [
                'car_variant_id' => $variants->where('name', 'Ranger XLT')->first()->id,
                'image_path' => 'car-variants/ford-ranger-xlt-front.jpg',
                'alt_text' => 'Ford Ranger XLT - Góc nhìn phía trước',
                'title' => 'Ford Ranger XLT - Thiết kế mạnh mẽ',
                'description' => 'Góc nhìn phía trước của Ford Ranger XLT với thiết kế mạnh mẽ và nam tính',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Ranger XLT')->first()->id,
                'image_path' => 'car-variants/ford-ranger-xlt-interior.jpg',
                'alt_text' => 'Ford Ranger XLT - Nội thất',
                'title' => 'Ford Ranger XLT - Nội thất hiện đại',
                'description' => 'Nội thất Ford Ranger XLT với thiết kế hiện đại và công nghệ tiên tiến',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Hyundai Accent G Images
            [
                'car_variant_id' => $variants->where('name', 'Accent G')->first()->id,
                'image_path' => 'car-variants/hyundai-accent-g-front.jpg',
                'alt_text' => 'Hyundai Accent G - Góc nhìn phía trước',
                'title' => 'Hyundai Accent G - Thiết kế hiện đại',
                'description' => 'Góc nhìn phía trước của Hyundai Accent G với thiết kế hiện đại',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'Accent G')->first()->id,
                'image_path' => 'car-variants/hyundai-accent-g-interior.jpg',
                'alt_text' => 'Hyundai Accent G - Nội thất',
                'title' => 'Hyundai Accent G - Nội thất hiện đại',
                'description' => 'Nội thất Hyundai Accent G với thiết kế hiện đại và tiện nghi',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Mercedes-Benz C-Class C200 Images
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'image_path' => 'car-variants/mercedes-c-class-c200-front.jpg',
                'alt_text' => 'Mercedes-Benz C-Class C200 - Góc nhìn phía trước',
                'title' => 'Mercedes-Benz C-Class C200 - Sang trọng',
                'description' => 'Góc nhìn phía trước của Mercedes-Benz C-Class C200 với thiết kế sang trọng',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'C-Class C200')->first()->id,
                'image_path' => 'car-variants/mercedes-c-class-c200-interior.jpg',
                'alt_text' => 'Mercedes-Benz C-Class C200 - Nội thất',
                'title' => 'Mercedes-Benz C-Class C200 - Nội thất cao cấp',
                'description' => 'Nội thất Mercedes-Benz C-Class C200 với thiết kế cao cấp và sang trọng',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // BMW 3 Series 320i Images
            [
                'car_variant_id' => $variants->where('name', '3 Series 320i')->first()->id,
                'image_path' => 'car-variants/bmw-3-series-320i-front.jpg',
                'alt_text' => 'BMW 3 Series 320i - Góc nhìn phía trước',
                'title' => 'BMW 3 Series 320i - Thể thao',
                'description' => 'Góc nhìn phía trước của BMW 3 Series 320i với thiết kế thể thao',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', '3 Series 320i')->first()->id,
                'image_path' => 'car-variants/bmw-3-series-320i-interior.jpg',
                'alt_text' => 'BMW 3 Series 320i - Nội thất',
                'title' => 'BMW 3 Series 320i - Nội thất thể thao',
                'description' => 'Nội thất BMW 3 Series 320i với thiết kế thể thao và hiện đại',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // VinFast VF 8 Plus Images
            [
                'car_variant_id' => $variants->where('name', 'VF 8 Plus')->first()->id,
                'image_path' => 'car-variants/vinfast-vf8-plus-front.jpg',
                'alt_text' => 'VinFast VF 8 Plus - Góc nhìn phía trước',
                'title' => 'VinFast VF 8 Plus - SUV điện',
                'description' => 'Góc nhìn phía trước của VinFast VF 8 Plus với thiết kế SUV điện hiện đại',
                'image_type' => 'exterior',
                'angle' => 'front',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_variant_id' => $variants->where('name', 'VF 8 Plus')->first()->id,
                'image_path' => 'car-variants/vinfast-vf8-plus-interior.jpg',
                'alt_text' => 'VinFast VF 8 Plus - Nội thất',
                'title' => 'VinFast VF 8 Plus - Nội thất hiện đại',
                'description' => 'Nội thất VinFast VF 8 Plus với thiết kế hiện đại và công nghệ tiên tiến',
                'image_type' => 'interior',
                'angle' => 'dashboard',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ]
        ];

        // Inline resolution per item like AccessorySeeder
        foreach ($images as &$image) {
            $variant = $variants->firstWhere('id', $image['car_variant_id']);
            $vName = $variant?->name ?? null;
            $modelName = $variant?->carModel?->name ?? null;
            $label = $vName ?: ($modelName ?: 'Sản phẩm');
            $resolved = 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode($label);
            $image['image_path'] = $resolved;
            $image['image_url'] = $resolved;
        }
        unset($image);

        // Skip bulk image creation to avoid duplication with color images

        // Ensure every featured variant has at least one image (Commons first, then placeholder)
        foreach (CarVariant::where('is_featured', 1)->get() as $fv) {
            if (!$fv->images()->exists()) {
                $modelName = $fv->carModel?->name;
                $label = $fv->name ?: ($modelName ?: 'Sản phẩm');
                $resolved = 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode($label);
                CarVariantImage::create([
                    'car_variant_id' => $fv->id,
                    'image_path' => $resolved,
                    'image_url' => $resolved,
                    'image_type' => 'exterior',
                    'angle' => 'front',
                    'is_main' => true,
                    'is_active' => true,
                    'sort_order' => 1,
                ]);
            }
        }

        // Normalize flags: one main image per variant, and set is_active = true for all
        $variantIds = CarVariantImage::query()->distinct()->pluck('car_variant_id');
        foreach ($variantIds as $variantId) {
            CarVariantImage::where('car_variant_id', $variantId)->update(['is_main' => false, 'is_active' => true]);
            $first = CarVariantImage::where('car_variant_id', $variantId)->orderBy('sort_order')->first();
            if ($first) {
                $first->is_main = true;
                $first->is_active = true;
                $first->save();
            }
        }
    }
}
