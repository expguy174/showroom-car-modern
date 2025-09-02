<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CarModel;
use App\Models\CarVariant;
use Illuminate\Support\Str;
use App\Models\CarVariantColor;

class CarVariantSeeder extends Seeder
{
    public function run(): void
    {
        $vios = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','toyota'))
            ->where('name','Vios')->first();
        $santafe = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','hyundai'))
            ->where('name','Santa Fe')->first();
        $vf8 = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','vinfast'))
            ->where('name','VF 8')->first();
        $city = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','honda'))
            ->where('name','City')->first();
        $cx5 = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','mazda'))
            ->where('name','CX-5')->first();
        $seltos = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','kia'))
            ->where('name','Seltos')->first();
        $almera = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','nissan'))
            ->where('name','Almera')->first();
        $navara = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','nissan'))
            ->where('name','Navara')->first();
        $p3008 = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','peugeot'))
            ->where('name','3008')->first();
        $p5008 = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','peugeot'))
            ->where('name','5008')->first();
        $forester = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','subaru'))
            ->where('name','Forester')->first();
        $rx = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','lexus'))
            ->where('name','RX')->first();
        $q5 = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','audi'))
            ->where('name','Q5')->first();
        $tiguan = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','volkswagen'))
            ->where('name','Tiguan')->first();
        $xl7 = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','suzuki'))
            ->where('name','XL7')->first();
        $zs = CarModel::whereHas('carBrand', fn($q)=>$q->where('slug','mg'))
            ->where('name','ZS')->first();

        $variants = [];

        if ($vios) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $vios->id,
                    'name' => 'Vios 1.5E MT',
                    'slug' => null,
                    'sku' => 'TV-' . Str::upper(Str::random(6)),
                    'description' => 'Động cơ 1.5L, số sàn 5 cấp, 5 chỗ.',
                    'short_description' => '1.5E MT',
                    'base_price' => 540000000,
                    'current_price' => 520000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Toyota Vios 1.5E',
                    'meta_description' => 'Toyota Vios 1.5E MT phù hợp đô thị.',
                    'keywords' => 'vios 1.5e, sedan b',
                ],
                [
                    'car_model_id' => $vios->id,
                    'name' => 'Vios 1.5G CVT',
                    'slug' => null,
                    'sku' => 'TV-' . Str::upper(Str::random(6)),
                    'description' => 'Động cơ 1.5L, hộp số CVT, 5 chỗ.',
                    'short_description' => '1.5G CVT',
                    'base_price' => 590000000,
                    'current_price' => 560000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Toyota Vios 1.5G',
                    'meta_description' => 'Toyota Vios 1.5G CVT tiết kiệm nhiên liệu.',
                    'keywords' => 'vios 1.5g, sedan b',
                ],
                [
                    'car_model_id' => $vios->id,
                    'name' => 'Vios 1.5S CVT',
                    'slug' => null,
                    'sku' => 'TV-' . Str::upper(Str::random(6)),
                    'description' => 'Bản thể thao S, bodykit, hộp số CVT.',
                    'short_description' => '1.5S CVT',
                    'base_price' => 620000000,
                    'current_price' => 590000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Toyota Vios 1.5S',
                    'meta_description' => 'Toyota Vios 1.5S CVT phong cách.',
                    'keywords' => 'vios 1.5s, sedan b',
                ],
            ]);
        }

        if ($santafe) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $santafe->id,
                    'name' => 'Santa Fe 2.2 Diesel Standard',
                    'slug' => null,
                    'sku' => 'HSF-' . Str::upper(Str::random(6)),
                    'description' => 'Máy dầu 2.2, 7 chỗ, bản tiêu chuẩn.',
                    'short_description' => '2.2 Diesel Std',
                    'base_price' => 1180000000,
                    'current_price' => 1150000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Hyundai Santa Fe Dầu Tiêu Chuẩn',
                    'meta_description' => 'Santa Fe dầu bản tiêu chuẩn.',
                    'keywords' => 'santafe standard',
                ],
                [
                    'car_model_id' => $santafe->id,
                    'name' => 'Santa Fe 2.2 Diesel Premium',
                    'slug' => null,
                    'sku' => 'HSF-' . Str::upper(Str::random(6)),
                    'description' => 'Máy dầu 2.2, 7 chỗ, nhiều công nghệ an toàn.',
                    'short_description' => '2.2 Diesel Premium',
                    'base_price' => 1280000000,
                    'current_price' => 1250000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Hyundai Santa Fe Dầu',
                    'meta_description' => 'Hyundai Santa Fe 2.2 dầu, 7 chỗ.',
                    'keywords' => 'santafe 2.2, suv 7 cho',
                ],
                [
                    'car_model_id' => $santafe->id,
                    'name' => 'Santa Fe 2.5 Turbo Signature',
                    'slug' => null,
                    'sku' => 'HSF-' . Str::upper(Str::random(6)),
                    'description' => 'Động cơ xăng 2.5 Turbo, bản Signature.',
                    'short_description' => '2.5T Signature',
                    'base_price' => 1380000000,
                    'current_price' => 1350000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Santa Fe 2.5T Signature',
                    'meta_description' => 'Bản Signature mạnh mẽ và sang trọng.',
                    'keywords' => 'santafe 2.5t signature',
                ],
            ]);
        }

        if ($vf8) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $vf8->id,
                    'name' => 'VF 8 Eco',
                    'slug' => null,
                    'sku' => 'VF8-' . Str::upper(Str::random(6)),
                    'description' => 'SUV điện cỡ trung, bản Eco.',
                    'short_description' => 'Eco',
                    'base_price' => 1100000000,
                    'current_price' => 1100000000,
                    'is_on_sale' => false,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'VinFast VF 8 Eco',
                    'meta_description' => 'VF 8 Eco – SUV điện Việt Nam.',
                    'keywords' => 'vf8 eco, xe dien',
                ],
                [
                    'car_model_id' => $vf8->id,
                    'name' => 'VF 8 Plus',
                    'slug' => null,
                    'sku' => 'VF8-' . Str::upper(Str::random(6)),
                    'description' => 'Bản Plus trang bị cao cấp hơn.',
                    'short_description' => 'Plus',
                    'base_price' => 1250000000,
                    'current_price' => 1250000000,
                    'is_on_sale' => false,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => true,
                    'meta_title' => 'VinFast VF 8 Plus',
                    'meta_description' => 'VF 8 Plus – trang bị cao cấp.',
                    'keywords' => 'vf8 plus, xe dien',
                ],
                [
                    'car_model_id' => $vf8->id,
                    'name' => 'VF 8 Plus AWD',
                    'slug' => null,
                    'sku' => 'VF8-' . Str::upper(Str::random(6)),
                    'description' => 'Bản AWD dẫn động 4 bánh.',
                    'short_description' => 'Plus AWD',
                    'base_price' => 1350000000,
                    'current_price' => 1350000000,
                    'is_on_sale' => false,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'VinFast VF 8 AWD',
                    'meta_description' => 'VF 8 AWD – mạnh mẽ, bám đường.',
                    'keywords' => 'vf8 awd, xe dien',
                ],
            ]);
        }

        if ($city) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $city->id,
                    'name' => 'City G CVT',
                    'slug' => null,
                    'sku' => 'HC-' . Str::upper(Str::random(6)),
                    'description' => 'Động cơ 1.5L, hộp số CVT, bản G.',
                    'short_description' => 'G CVT',
                    'base_price' => 580000000,
                    'current_price' => 560000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Honda City G',
                    'meta_description' => 'Honda City G CVT.',
                    'keywords' => 'honda city g',
                ],
                [
                    'car_model_id' => $city->id,
                    'name' => 'City L CVT',
                    'slug' => null,
                    'sku' => 'HC-' . Str::upper(Str::random(6)),
                    'description' => 'Bản L trang bị nhiều tiện nghi.',
                    'short_description' => 'L CVT',
                    'base_price' => 620000000,
                    'current_price' => 595000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Honda City L',
                    'meta_description' => 'Honda City L CVT.',
                    'keywords' => 'honda city l',
                ],
                [
                    'car_model_id' => $city->id,
                    'name' => 'City RS CVT',
                    'slug' => null,
                    'sku' => 'HC-' . Str::upper(Str::random(6)),
                    'description' => 'Bản RS thể thao, hộp số CVT.',
                    'short_description' => 'RS CVT',
                    'base_price' => 650000000,
                    'current_price' => 620000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Honda City RS',
                    'meta_description' => 'Honda City RS CVT.',
                    'keywords' => 'honda city rs',
                ],
            ]);
        }

        if ($cx5) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $cx5->id,
                    'name' => 'CX-5 2.0 Deluxe',
                    'slug' => null,
                    'sku' => 'MC5-' . Str::upper(Str::random(6)),
                    'description' => 'Bản Deluxe 2.0, trang bị cân đối.',
                    'short_description' => '2.0 Deluxe',
                    'base_price' => 900000000,
                    'current_price' => 860000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Mazda CX-5 2.0 Deluxe',
                    'meta_description' => 'Mazda CX-5 2.0 Deluxe.',
                    'keywords' => 'mazda cx-5 deluxe',
                ],
                [
                    'car_model_id' => $cx5->id,
                    'name' => 'CX-5 2.0 Luxury',
                    'slug' => null,
                    'sku' => 'MC5-' . Str::upper(Str::random(6)),
                    'description' => 'Bản Luxury 2.0, nội thất cao cấp.',
                    'short_description' => '2.0 Luxury',
                    'base_price' => 920000000,
                    'current_price' => 880000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Mazda CX-5 2.0 Luxury',
                    'meta_description' => 'Mazda CX-5 2.0 Luxury.',
                    'keywords' => 'mazda cx-5 luxury',
                ],
                [
                    'car_model_id' => $cx5->id,
                    'name' => 'CX-5 2.0 Premium',
                    'slug' => null,
                    'sku' => 'MC5-' . Str::upper(Str::random(6)),
                    'description' => 'Bản Premium 2.0, nhiều trang bị an toàn.',
                    'short_description' => '2.0 Premium',
                    'base_price' => 930000000,
                    'current_price' => 900000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Mazda CX-5 2.0 Premium',
                    'meta_description' => 'Mazda CX-5 2.0 Premium.',
                    'keywords' => 'mazda cx-5 premium',
                ],
            ]);
        }

        if ($seltos) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $seltos->id,
                    'name' => 'Seltos 1.4 Turbo Deluxe',
                    'slug' => null,
                    'sku' => 'KST-' . Str::upper(Str::random(6)),
                    'description' => 'Động cơ 1.4 Turbo, bản Deluxe.',
                    'short_description' => '1.4T Deluxe',
                    'base_price' => 710000000,
                    'current_price' => 690000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Kia Seltos 1.4T Deluxe',
                    'meta_description' => 'Kia Seltos 1.4 Turbo Deluxe.',
                    'keywords' => 'kia seltos deluxe',
                ],
                [
                    'car_model_id' => $seltos->id,
                    'name' => 'Seltos 1.4 Turbo Luxury',
                    'slug' => null,
                    'sku' => 'KST-' . Str::upper(Str::random(6)),
                    'description' => 'Động cơ 1.4 Turbo, bản Luxury.',
                    'short_description' => '1.4T Luxury',
                    'base_price' => 740000000,
                    'current_price' => 720000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Kia Seltos 1.4T Luxury',
                    'meta_description' => 'Kia Seltos 1.4 Turbo Luxury.',
                    'keywords' => 'kia seltos luxury',
                ],
                [
                    'car_model_id' => $seltos->id,
                    'name' => 'Seltos 1.4 Turbo Premium',
                    'slug' => null,
                    'sku' => 'KST-' . Str::upper(Str::random(6)),
                    'description' => 'Động cơ 1.4 Turbo, trang bị phong phú.',
                    'short_description' => '1.4 Turbo Premium',
                    'base_price' => 760000000,
                    'current_price' => 745000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Kia Seltos 1.4T',
                    'meta_description' => 'Kia Seltos 1.4 Turbo.',
                    'keywords' => 'kia seltos turbo',
                ],
            ]);
        }

        if ($almera) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $almera->id,
                    'name' => 'Almera E MT',
                    'slug' => null,
                    'sku' => 'NAL-' . Str::upper(Str::random(6)),
                    'description' => 'Động cơ 1.0 Turbo, số sàn.',
                    'short_description' => 'E MT',
                    'base_price' => 490000000,
                    'current_price' => 470000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Nissan Almera E',
                    'meta_description' => 'Almera 1.0 Turbo số sàn.',
                    'keywords' => 'nissan almera e',
                ],
                [
                    'car_model_id' => $almera->id,
                    'name' => 'Almera VL CVT',
                    'slug' => null,
                    'sku' => 'NAL-' . Str::upper(Str::random(6)),
                    'description' => 'Bản cao, hộp số CVT.',
                    'short_description' => 'VL CVT',
                    'base_price' => 599000000,
                    'current_price' => 579000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => true,
                    'meta_title' => 'Nissan Almera VL',
                    'meta_description' => 'Almera VL CVT trang bị cao.',
                    'keywords' => 'almera vl',
                ],
            ]);
        }

        if ($navara) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $navara->id,
                    'name' => 'Navara EL 2WD AT',
                    'slug' => null,
                    'sku' => 'NVR-' . Str::upper(Str::random(6)),
                    'description' => 'Máy dầu 2.5, 2 cầu sau, AT.',
                    'short_description' => 'EL 2WD AT',
                    'base_price' => 770000000,
                    'current_price' => 750000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Nissan Navara EL',
                    'meta_description' => 'Navara EL 2WD AT.',
                    'keywords' => 'navara el',
                ],
                [
                    'car_model_id' => $navara->id,
                    'name' => 'Navara Pro-4X 4WD AT',
                    'slug' => null,
                    'sku' => 'NVR-' . Str::upper(Str::random(6)),
                    'description' => 'Bản off-road Pro-4X 2.5 diesel.',
                    'short_description' => 'Pro-4X 4WD AT',
                    'base_price' => 980000000,
                    'current_price' => 950000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Nissan Navara Pro-4X',
                    'meta_description' => 'Navara Pro-4X off-road.',
                    'keywords' => 'navara pro-4x',
                ],
            ]);
        }

        if ($p3008) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $p3008->id,
                    'name' => '3008 Active',
                    'slug' => null,
                    'sku' => 'P38-' . Str::upper(Str::random(6)),
                    'description' => 'Bản Active, động cơ 1.6 Turbo.',
                    'short_description' => 'Active',
                    'base_price' => 1050000000,
                    'current_price' => 1029000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Peugeot 3008 Active',
                    'meta_description' => '3008 Active 1.6 Turbo.',
                    'keywords' => 'peugeot 3008 active',
                ],
                [
                    'car_model_id' => $p3008->id,
                    'name' => '3008 GT',
                    'slug' => null,
                    'sku' => 'P38-' . Str::upper(Str::random(6)),
                    'description' => 'Bản GT cao cấp.',
                    'short_description' => 'GT',
                    'base_price' => 1210000000,
                    'current_price' => 1190000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Peugeot 3008 GT',
                    'meta_description' => '3008 GT sang trọng.',
                    'keywords' => '3008 gt',
                ],
            ]);
        }

        if ($p5008) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $p5008->id,
                    'name' => '5008 Active',
                    'slug' => null,
                    'sku' => 'P58-' . Str::upper(Str::random(6)),
                    'description' => 'SUV 5+2 bản Active.',
                    'short_description' => 'Active',
                    'base_price' => 1240000000,
                    'current_price' => 1220000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Peugeot 5008 Active',
                    'meta_description' => '5008 5+2 Active.',
                    'keywords' => 'peugeot 5008 active',
                ],
                [
                    'car_model_id' => $p5008->id,
                    'name' => '5008 GT',
                    'slug' => null,
                    'sku' => 'P58-' . Str::upper(Str::random(6)),
                    'description' => 'Bản GT sang trọng.',
                    'short_description' => 'GT',
                    'base_price' => 1410000000,
                    'current_price' => 1390000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Peugeot 5008 GT',
                    'meta_description' => '5008 GT cao cấp.',
                    'keywords' => '5008 gt',
                ],
            ]);
        }

        if ($forester) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $forester->id,
                    'name' => 'Forester i-L',
                    'slug' => null,
                    'sku' => 'SFR-' . Str::upper(Str::random(6)),
                    'description' => 'AWD, EyeSight an toàn.',
                    'short_description' => 'i-L',
                    'base_price' => 900000000,
                    'current_price' => 880000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Subaru Forester i-L',
                    'meta_description' => 'Forester i-L EyeSight.',
                    'keywords' => 'forester i-l',
                ],
                [
                    'car_model_id' => $forester->id,
                    'name' => 'Forester i-S',
                    'slug' => null,
                    'sku' => 'SFR-' . Str::upper(Str::random(6)),
                    'description' => 'Bản i-S nhiều tiện nghi.',
                    'short_description' => 'i-S',
                    'base_price' => 1010000000,
                    'current_price' => 990000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Subaru Forester i-S',
                    'meta_description' => 'Forester i-S tiện nghi.',
                    'keywords' => 'forester i-s',
                ],
            ]);
        }

        if ($rx) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $rx->id,
                    'name' => 'RX 350h Luxury',
                    'slug' => null,
                    'sku' => 'LXR-' . Str::upper(Str::random(6)),
                    'description' => 'Hybrid 2.5, bản Luxury.',
                    'short_description' => '350h Luxury',
                    'base_price' => 4650000000,
                    'current_price' => 4550000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => true,
                    'meta_title' => 'Lexus RX 350h Luxury',
                    'meta_description' => 'RX 350h sang trọng.',
                    'keywords' => 'lexus rx 350h',
                ],
                [
                    'car_model_id' => $rx->id,
                    'name' => 'RX 500h F Sport',
                    'slug' => null,
                    'sku' => 'LXR-' . Str::upper(Str::random(6)),
                    'description' => 'Hybrid hiệu suất cao, F Sport.',
                    'short_description' => '500h F Sport',
                    'base_price' => 5300000000,
                    'current_price' => 5200000000,
                    'is_on_sale' => false,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Lexus RX 500h F Sport',
                    'meta_description' => 'RX 500h thể thao.',
                    'keywords' => 'lexus rx 500h',
                ],
            ]);
        }

        if ($q5) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $q5->id,
                    'name' => 'Q5 45 TFSI quattro',
                    'slug' => null,
                    'sku' => 'AQ5-' . Str::upper(Str::random(6)),
                    'description' => '2.0 TFSI, quattro AWD.',
                    'short_description' => '45 TFSI',
                    'base_price' => 2250000000,
                    'current_price' => 2200000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Audi Q5 45 TFSI',
                    'meta_description' => 'Q5 quattro 45 TFSI.',
                    'keywords' => 'audi q5 45 tfsi',
                ],
                [
                    'car_model_id' => $q5->id,
                    'name' => 'Q5 Sportback',
                    'slug' => null,
                    'sku' => 'AQ5-' . Str::upper(Str::random(6)),
                    'description' => 'Kiểu dáng coupe thể thao.',
                    'short_description' => 'Sportback',
                    'base_price' => 2450000000,
                    'current_price' => 2400000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Audi Q5 Sportback',
                    'meta_description' => 'Q5 Sportback phong cách.',
                    'keywords' => 'q5 sportback',
                ],
            ]);
        }

        if ($tiguan) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $tiguan->id,
                    'name' => 'Tiguan Elegance',
                    'slug' => null,
                    'sku' => 'VTG-' . Str::upper(Str::random(6)),
                    'description' => 'SUV 5+2 bản Elegance.',
                    'short_description' => 'Elegance',
                    'base_price' => 1830000000,
                    'current_price' => 1800000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'VW Tiguan Elegance',
                    'meta_description' => 'Tiguan Elegance 7 chỗ.',
                    'keywords' => 'vw tiguan elegance',
                ],
                [
                    'car_model_id' => $tiguan->id,
                    'name' => 'Tiguan Luxury S',
                    'slug' => null,
                    'sku' => 'VTG-' . Str::upper(Str::random(6)),
                    'description' => 'Bản Luxury S nhiều option.',
                    'short_description' => 'Luxury S',
                    'base_price' => 2030000000,
                    'current_price' => 2000000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'VW Tiguan Luxury S',
                    'meta_description' => 'Tiguan Luxury S cao cấp.',
                    'keywords' => 'tiguan luxury s',
                ],
            ]);
        }

        if ($xl7) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $xl7->id,
                    'name' => 'XL7 AT',
                    'slug' => null,
                    'sku' => 'SXL-' . Str::upper(Str::random(6)),
                    'description' => 'MPV 7 chỗ hộp số tự động.',
                    'short_description' => 'AT',
                    'base_price' => 660000000,
                    'current_price' => 640000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'Suzuki XL7 AT',
                    'meta_description' => 'XL7 số tự động.',
                    'keywords' => 'suzuki xl7 at',
                ],
                [
                    'car_model_id' => $xl7->id,
                    'name' => 'XL7 Sport Limited',
                    'slug' => null,
                    'sku' => 'SXL-' . Str::upper(Str::random(6)),
                    'description' => 'Bản Sport ngoại thất thể thao.',
                    'short_description' => 'Sport Limited',
                    'base_price' => 700000000,
                    'current_price' => 680000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'Suzuki XL7 Sport',
                    'meta_description' => 'XL7 Sport phong cách.',
                    'keywords' => 'xl7 sport',
                ],
            ]);
        }

        if ($zs) {
            $variants = array_merge($variants, [
                [
                    'car_model_id' => $zs->id,
                    'name' => 'ZS Standard',
                    'slug' => null,
                    'sku' => 'MGZ-' . Str::upper(Str::random(6)),
                    'description' => 'SUV đô thị bản tiêu chuẩn.',
                    'short_description' => 'Standard',
                    'base_price' => 550000000,
                    'current_price' => 530000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => false,
                    'is_available' => true,
                    'is_new_arrival' => false,
                    'is_bestseller' => true,
                    'meta_title' => 'MG ZS Standard',
                    'meta_description' => 'MG ZS bản tiêu chuẩn.',
                    'keywords' => 'mg zs standard',
                ],
                [
                    'car_model_id' => $zs->id,
                    'name' => 'ZS LUX',
                    'slug' => null,
                    'sku' => 'MGZ-' . Str::upper(Str::random(6)),
                    'description' => 'Bản LUX nhiều option.',
                    'short_description' => 'LUX',
                    'base_price' => 610000000,
                    'current_price' => 590000000,
                    'is_on_sale' => true,
                    'color_inventory' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'is_available' => true,
                    'is_new_arrival' => true,
                    'is_bestseller' => false,
                    'meta_title' => 'MG ZS LUX',
                    'meta_description' => 'MG ZS LUX trang bị cao.',
                    'keywords' => 'mg zs lux',
                ],
            ]);
        }

        foreach ($variants as $data) {
            CarVariant::updateOrCreate([
                'car_model_id' => $data['car_model_id'],
                'name' => $data['name'],
            ], $data);
        }

        // Populate color_inventory per variant if colors already exist
        $variantsWithColors = CarVariant::with('colors')->get();
        foreach ($variantsWithColors as $variant) {
            if ($variant->colors && $variant->colors->count() > 0) {
                $inventory = [];
                foreach ($variant->colors as $color) {
                    // Default seed: 5 units available per color
                    $inventory[$color->id] = [
                        'quantity' => 5,
                        'reserved' => 0,
                        'available' => 5,
                    ];
                }
                $variant->color_inventory = $inventory;
                $variant->save();
            }
        }
    }
}


