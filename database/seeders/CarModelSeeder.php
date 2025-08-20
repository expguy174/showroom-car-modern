<?php

namespace Database\Seeders;

use App\Models\CarModel;
use App\Models\CarBrand;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = CarBrand::all();

        $carModels = [
            // Toyota Models
            [
                'car_brand_id' => $brands->where('name', 'Toyota')->first()->id,
                'name' => 'Vios',
                'slug' => 'toyota-vios',
                'description' => 'Sedan hạng B phổ biến nhất tại Việt Nam với thiết kế hiện đại và tiết kiệm nhiên liệu',
                'segment' => 'compact',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Toyota Vios - Sedan hạng B phổ biến',
                'meta_description' => 'Toyota Vios - Sedan hạng B phổ biến nhất tại Việt Nam với thiết kế hiện đại và tiết kiệm nhiên liệu',
                'keywords' => 'toyota vios, sedan hạng b, xe gia đình'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Toyota')->first()->id,
                'name' => 'Corolla Altis',
                'slug' => 'toyota-corolla-altis',
                'description' => 'Sedan hạng C bền bỉ, kinh tế và phổ biến',
                'segment' => 'mid-size',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 5,
                'meta_title' => 'Toyota Corolla Altis - Sedan hạng C bền bỉ',
                'meta_description' => 'Toyota Corolla Altis - Sedan hạng C bền bỉ, kinh tế và phổ biến',
                'keywords' => 'toyota corolla altis, sedan hạng c'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Toyota')->first()->id,
                'name' => 'Innova',
                'slug' => 'toyota-innova',
                'description' => 'MPV đa dụng với không gian rộng rãi, phù hợp cho gia đình',
                'segment' => 'mid-size',
                'body_type' => 'minivan',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'Toyota Innova - MPV đa dụng',
                'meta_description' => 'Toyota Innova - MPV đa dụng với không gian rộng rãi, phù hợp cho gia đình',
                'keywords' => 'toyota innova, mpv, xe gia đình'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Honda')->first()->id,
                'name' => 'HR-V',
                'slug' => 'honda-hr-v',
                'description' => 'Crossover đô thị linh hoạt, tiết kiệm',
                'segment' => 'compact',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'meta_title' => 'Honda HR-V - Crossover đô thị',
                'meta_description' => 'Honda HR-V - Crossover đô thị linh hoạt, tiết kiệm',
                'keywords' => 'honda hr-v, crossover'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Toyota')->first()->id,
                'name' => 'Fortuner',
                'slug' => 'toyota-fortuner',
                'description' => 'SUV 7 chỗ cao cấp với khả năng off-road tốt',
                'segment' => 'full-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'meta_title' => 'Toyota Fortuner - SUV 7 chỗ cao cấp',
                'meta_description' => 'Toyota Fortuner - SUV 7 chỗ cao cấp với khả năng off-road tốt',
                'keywords' => 'toyota fortuner, suv, xe off-road'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Ford')->first()->id,
                'name' => 'Territory',
                'slug' => 'ford-territory',
                'description' => 'SUV đô thị nhiều công nghệ, giá tốt',
                'segment' => 'mid-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'meta_title' => 'Ford Territory - SUV đô thị',
                'meta_description' => 'Ford Territory - SUV đô thị nhiều công nghệ, giá tốt',
                'keywords' => 'ford territory, suv đô thị'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Toyota')->first()->id,
                'name' => 'Camry',
                'slug' => 'toyota-camry',
                'description' => 'Sedan hạng D sang trọng với công nghệ tiên tiến',
                'segment' => 'full-size',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'meta_title' => 'Toyota Camry - Sedan hạng D sang trọng',
                'meta_description' => 'Toyota Camry - Sedan hạng D sang trọng với công nghệ tiên tiến',
                'keywords' => 'toyota camry, sedan hạng d, xe sang trọng'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Hyundai')->first()->id,
                'name' => 'Santa Fe',
                'slug' => 'hyundai-santa-fe',
                'description' => 'SUV 7 chỗ hiện đại, rộng rãi',
                'segment' => 'full-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'meta_title' => 'Hyundai Santa Fe - SUV 7 chỗ',
                'meta_description' => 'Hyundai Santa Fe - SUV 7 chỗ hiện đại, rộng rãi',
                'keywords' => 'hyundai santa fe, suv 7 chỗ'
            ],

            // Honda Models
            [
                'car_brand_id' => $brands->where('name', 'Honda')->first()->id,
                'name' => 'City',
                'slug' => 'honda-city',
                'description' => 'Sedan hạng B với thiết kế thể thao và động cơ mạnh mẽ',
                'segment' => 'compact',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Honda City - Sedan hạng B thể thao',
                'meta_description' => 'Honda City - Sedan hạng B với thiết kế thể thao và động cơ mạnh mẽ',
                'keywords' => 'honda city, sedan hạng b, xe thể thao'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Kia')->first()->id,
                'name' => 'Seltos',
                'slug' => 'kia-seltos',
                'description' => 'SUV cỡ nhỏ thiết kế trẻ trung',
                'segment' => 'compact',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'meta_title' => 'Kia Seltos - SUV cỡ nhỏ',
                'meta_description' => 'Kia Seltos - SUV cỡ nhỏ thiết kế trẻ trung',
                'keywords' => 'kia seltos, suv cỡ nhỏ'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Honda')->first()->id,
                'name' => 'CR-V',
                'slug' => 'honda-cr-v',
                'description' => 'SUV 5 chỗ với thiết kế hiện đại và công nghệ tiên tiến',
                'segment' => 'mid-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'Honda CR-V - SUV 5 chỗ hiện đại',
                'meta_description' => 'Honda CR-V - SUV 5 chỗ với thiết kế hiện đại và công nghệ tiên tiến',
                'keywords' => 'honda cr-v, suv, xe hiện đại'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Mercedes-Benz')->first()->id,
                'name' => 'E-Class',
                'slug' => 'mercedes-benz-e-class',
                'description' => 'Sedan hạng sang cỡ trung nhiều tiện nghi',
                'segment' => 'luxury',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'meta_title' => 'Mercedes-Benz E-Class - Sedan hạng sang',
                'meta_description' => 'Mercedes-Benz E-Class - Sedan hạng sang cỡ trung nhiều tiện nghi',
                'keywords' => 'mercedes e-class, sedan hạng sang'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Honda')->first()->id,
                'name' => 'Civic',
                'slug' => 'honda-civic',
                'description' => 'Sedan hạng C với thiết kế thể thao và hiệu suất cao',
                'segment' => 'mid-size',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'meta_title' => 'Honda Civic - Sedan hạng C thể thao',
                'meta_description' => 'Honda Civic - Sedan hạng C với thiết kế thể thao và hiệu suất cao',
                'keywords' => 'honda civic, sedan hạng c, xe thể thao'
            ],
            [
                'car_brand_id' => $brands->where('name', 'BMW')->first()->id,
                'name' => '5 Series',
                'slug' => 'bmw-5-series',
                'description' => 'Sedan hạng sang hiệu suất cao',
                'segment' => 'luxury',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'meta_title' => 'BMW 5 Series - Sedan hạng sang',
                'meta_description' => 'BMW 5 Series - Sedan hạng sang hiệu suất cao',
                'keywords' => 'bmw 5 series, sedan hạng sang'
            ],

            // Ford Models
            [
                'car_brand_id' => $brands->where('name', 'Ford')->first()->id,
                'name' => 'Ranger',
                'slug' => 'ford-ranger',
                'description' => 'Pickup truck mạnh mẽ với khả năng tải trọng cao',
                'segment' => 'mid-size',
                'body_type' => 'pickup',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Ford Ranger - Pickup truck mạnh mẽ',
                'meta_description' => 'Ford Ranger - Pickup truck mạnh mẽ với khả năng tải trọng cao',
                'keywords' => 'ford ranger, pickup truck, xe tải'
            ],
            [
                'car_brand_id' => $brands->where('name', 'VinFast')->first()->id,
                'name' => 'VF 6',
                'slug' => 'vinfast-vf-6',
                'description' => 'Crossover điện đô thị',
                'segment' => 'compact',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'meta_title' => 'VinFast VF 6 - Crossover điện',
                'meta_description' => 'VinFast VF 6 - Crossover điện đô thị',
                'keywords' => 'vinfast vf 6, xe điện'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Ford')->first()->id,
                'name' => 'Everest',
                'slug' => 'ford-everest',
                'description' => 'SUV 7 chỗ với khả năng off-road xuất sắc',
                'segment' => 'full-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'Ford Everest - SUV 7 chỗ off-road',
                'meta_description' => 'Ford Everest - SUV 7 chỗ với khả năng off-road xuất sắc',
                'keywords' => 'ford everest, suv, xe off-road'
            ],

            // Hyundai Models
            [
                'car_brand_id' => $brands->where('name', 'Hyundai')->first()->id,
                'name' => 'Accent',
                'slug' => 'hyundai-accent',
                'description' => 'Sedan hạng B với thiết kế hiện đại và giá cả cạnh tranh',
                'segment' => 'compact',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Hyundai Accent - Sedan hạng B hiện đại',
                'meta_description' => 'Hyundai Accent - Sedan hạng B với thiết kế hiện đại và giá cả cạnh tranh',
                'keywords' => 'hyundai accent, sedan hạng b, xe hiện đại'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Hyundai')->first()->id,
                'name' => 'Tucson',
                'slug' => 'hyundai-tucson',
                'description' => 'SUV 5 chỗ với thiết kế bold và công nghệ tiên tiến',
                'segment' => 'mid-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'Hyundai Tucson - SUV 5 chỗ bold',
                'meta_description' => 'Hyundai Tucson - SUV 5 chỗ với thiết kế bold và công nghệ tiên tiến',
                'keywords' => 'hyundai tucson, suv, xe bold'
            ],

            // Kia Models
            [
                'car_brand_id' => $brands->where('name', 'Kia')->first()->id,
                'name' => 'Morning',
                'slug' => 'kia-morning',
                'description' => 'Hatchback hạng A nhỏ gọn, tiết kiệm nhiên liệu',
                'segment' => 'economy',
                'body_type' => 'hatchback',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Kia Morning - Hatchback hạng A nhỏ gọn',
                'meta_description' => 'Kia Morning - Hatchback hạng A nhỏ gọn, tiết kiệm nhiên liệu',
                'keywords' => 'kia morning, hatchback hạng a, xe nhỏ gọn'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Kia')->first()->id,
                'name' => 'Sorento',
                'slug' => 'kia-sorento',
                'description' => 'SUV 7 chỗ cao cấp với thiết kế hiện đại',
                'segment' => 'full-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'meta_title' => 'Kia Sorento - SUV 7 chỗ cao cấp',
                'meta_description' => 'Kia Sorento - SUV 7 chỗ cao cấp với thiết kế hiện đại',
                'keywords' => 'kia sorento, suv, xe cao cấp'
            ],

            // Mercedes-Benz Models
            [
                'car_brand_id' => $brands->where('name', 'Mercedes-Benz')->first()->id,
                'name' => 'C-Class',
                'slug' => 'mercedes-benz-c-class',
                'description' => 'Sedan hạng D cao cấp với sự sang trọng và công nghệ tiên tiến',
                'segment' => 'luxury',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Mercedes-Benz C-Class - Sedan hạng D cao cấp',
                'meta_description' => 'Mercedes-Benz C-Class - Sedan hạng D cao cấp với sự sang trọng và công nghệ tiên tiến',
                'keywords' => 'mercedes-benz c-class, sedan hạng d, xe cao cấp'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Mercedes-Benz')->first()->id,
                'name' => 'GLC',
                'slug' => 'mercedes-benz-glc',
                'description' => 'SUV 5 chỗ cao cấp với thiết kế sang trọng',
                'segment' => 'luxury',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'Mercedes-Benz GLC - SUV 5 chỗ cao cấp',
                'meta_description' => 'Mercedes-Benz GLC - SUV 5 chỗ cao cấp với thiết kế sang trọng',
                'keywords' => 'mercedes-benz glc, suv, xe cao cấp'
            ],

            // BMW Models
            [
                'car_brand_id' => $brands->where('name', 'BMW')->first()->id,
                'name' => '3 Series',
                'slug' => 'bmw-3-series',
                'description' => 'Sedan hạng D thể thao với hiệu suất cao',
                'segment' => 'luxury',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'BMW 3 Series - Sedan hạng D thể thao',
                'meta_description' => 'BMW 3 Series - Sedan hạng D thể thao với hiệu suất cao',
                'keywords' => 'bmw 3 series, sedan hạng d, xe thể thao'
            ],
            [
                'car_brand_id' => $brands->where('name', 'BMW')->first()->id,
                'name' => 'X3',
                'slug' => 'bmw-x3',
                'description' => 'SUV 5 chỗ thể thao với khả năng vận hành xuất sắc',
                'segment' => 'luxury',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'BMW X3 - SUV 5 chỗ thể thao',
                'meta_description' => 'BMW X3 - SUV 5 chỗ thể thao với khả năng vận hành xuất sắc',
                'keywords' => 'bmw x3, suv, xe thể thao'
            ],

            // VinFast Models
            [
                'car_brand_id' => $brands->where('name', 'VinFast')->first()->id,
                'name' => 'VF 8',
                'slug' => 'vinfast-vf-8',
                'description' => 'SUV điện 5 chỗ với công nghệ hiện đại và thiết kế độc đáo',
                'segment' => 'premium',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'VinFast VF 8 - SUV điện 5 chỗ',
                'meta_description' => 'VinFast VF 8 - SUV điện 5 chỗ với công nghệ hiện đại và thiết kế độc đáo',
                'keywords' => 'vinfast vf 8, suv điện, xe điện'
            ],
            [
                'car_brand_id' => $brands->where('name', 'VinFast')->first()->id,
                'name' => 'VF 9',
                'slug' => 'vinfast-vf-9',
                'description' => 'SUV điện 7 chỗ cao cấp với không gian rộng rãi',
                'segment' => 'luxury',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'VinFast VF 9 - SUV điện 7 chỗ cao cấp',
                'meta_description' => 'VinFast VF 9 - SUV điện 7 chỗ cao cấp với không gian rộng rãi',
                'keywords' => 'vinfast vf 9, suv điện, xe điện cao cấp'
            ]
            ,
            // More Toyota Models
            [
                'car_brand_id' => $brands->where('name', 'Toyota')->first()->id,
                'name' => 'Corolla Cross',
                'slug' => 'toyota-corolla-cross',
                'description' => 'Crossover đô thị tiết kiệm, nhiều công nghệ an toàn',
                'segment' => 'compact',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 6,
                'meta_title' => 'Toyota Corolla Cross - Crossover đô thị',
                'meta_description' => 'Toyota Corolla Cross - Crossover đô thị tiết kiệm, an toàn',
                'keywords' => 'toyota corolla cross, crossover'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Toyota')->first()->id,
                'name' => 'Yaris',
                'slug' => 'toyota-yaris',
                'description' => 'Hatchback hạng B nhỏ gọn, tiết kiệm',
                'segment' => 'compact',
                'body_type' => 'hatchback',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 7,
                'meta_title' => 'Toyota Yaris - Hatchback hạng B',
                'meta_description' => 'Toyota Yaris - Hatchback nhỏ gọn, tiết kiệm',
                'keywords' => 'toyota yaris, hatchback'
            ],
            // Honda
            [
                'car_brand_id' => $brands->where('name', 'Honda')->first()->id,
                'name' => 'Accord',
                'slug' => 'honda-accord',
                'description' => 'Sedan hạng D cao cấp, vận hành êm ái',
                'segment' => 'full-size',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'meta_title' => 'Honda Accord - Sedan hạng D',
                'meta_description' => 'Honda Accord - Sedan hạng D cao cấp',
                'keywords' => 'honda accord'
            ],
            // Ford
            [
                'car_brand_id' => $brands->where('name', 'Ford')->first()->id,
                'name' => 'Explorer',
                'slug' => 'ford-explorer',
                'description' => 'SUV 7 chỗ cỡ lớn mạnh mẽ',
                'segment' => 'full-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
                'meta_title' => 'Ford Explorer - SUV 7 chỗ',
                'meta_description' => 'Ford Explorer - SUV 7 chỗ cỡ lớn mạnh mẽ',
                'keywords' => 'ford explorer'
            ],
            // Hyundai
            [
                'car_brand_id' => $brands->where('name', 'Hyundai')->first()->id,
                'name' => 'Elantra',
                'slug' => 'hyundai-elantra',
                'description' => 'Sedan hạng C thiết kế sắc nét',
                'segment' => 'mid-size',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'meta_title' => 'Hyundai Elantra - Sedan hạng C',
                'meta_description' => 'Hyundai Elantra - Sedan hạng C',
                'keywords' => 'hyundai elantra'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Hyundai')->first()->id,
                'name' => 'Creta',
                'slug' => 'hyundai-creta',
                'description' => 'SUV đô thị 5 chỗ hiện đại',
                'segment' => 'compact',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 5,
                'meta_title' => 'Hyundai Creta - SUV đô thị',
                'meta_description' => 'Hyundai Creta - SUV đô thị 5 chỗ',
                'keywords' => 'hyundai creta'
            ],
            // Kia
            [
                'car_brand_id' => $brands->where('name', 'Kia')->first()->id,
                'name' => 'Carnival',
                'slug' => 'kia-carnival',
                'description' => 'MPV 7-8 chỗ cao cấp',
                'segment' => 'full-size',
                'body_type' => 'minivan',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 4,
                'meta_title' => 'Kia Carnival - MPV cao cấp',
                'meta_description' => 'Kia Carnival - MPV 7-8 chỗ cao cấp',
                'keywords' => 'kia carnival'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Kia')->first()->id,
                'name' => 'K3',
                'slug' => 'kia-k3',
                'description' => 'Sedan hạng C trẻ trung',
                'segment' => 'mid-size',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'meta_title' => 'Kia K3 - Sedan hạng C',
                'meta_description' => 'Kia K3 - Sedan hạng C trẻ trung',
                'keywords' => 'kia k3'
            ],
            // Mazda
            [
                'car_brand_id' => $brands->where('name', 'Mazda')->first()->id,
                'name' => 'CX-5',
                'slug' => 'mazda-cx-5',
                'description' => 'SUV 5 chỗ thiết kế KODO',
                'segment' => 'mid-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Mazda CX-5 - SUV 5 chỗ',
                'meta_description' => 'Mazda CX-5 - SUV 5 chỗ thiết kế KODO',
                'keywords' => 'mazda cx-5'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Mazda')->first()->id,
                'name' => 'Mazda3',
                'slug' => 'mazda-3',
                'description' => 'Sedan/Hatchback hạng C thanh lịch',
                'segment' => 'mid-size',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'meta_title' => 'Mazda3 - Sedan hạng C',
                'meta_description' => 'Mazda3 - Thanh lịch, công nghệ i-Activsense',
                'keywords' => 'mazda3'
            ],
            // Nissan
            [
                'car_brand_id' => $brands->where('name', 'Nissan')->first()->id,
                'name' => 'Almera',
                'slug' => 'nissan-almera',
                'description' => 'Sedan hạng B tiết kiệm',
                'segment' => 'compact',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                'meta_title' => 'Nissan Almera - Sedan hạng B',
                'meta_description' => 'Nissan Almera - Tiết kiệm',
                'keywords' => 'nissan almera'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Nissan')->first()->id,
                'name' => 'X-Trail',
                'slug' => 'nissan-x-trail',
                'description' => 'SUV 5+2 linh hoạt',
                'segment' => 'mid-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'meta_title' => 'Nissan X-Trail - SUV 5+2',
                'meta_description' => 'Nissan X-Trail - SUV linh hoạt',
                'keywords' => 'nissan x-trail'
            ],
            // Mercedes-Benz
            [
                'car_brand_id' => $brands->where('name', 'Mercedes-Benz')->first()->id,
                'name' => 'GLE',
                'slug' => 'mercedes-benz-gle',
                'description' => 'SUV hạng sang 5/7 chỗ',
                'segment' => 'luxury',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 4,
                'meta_title' => 'Mercedes-Benz GLE - SUV hạng sang',
                'meta_description' => 'Mercedes GLE - SUV hạng sang 5/7 chỗ',
                'keywords' => 'mercedes gle'
            ],
            // BMW
            [
                'car_brand_id' => $brands->where('name', 'BMW')->first()->id,
                'name' => 'X5',
                'slug' => 'bmw-x5',
                'description' => 'SUV hạng sang hiệu suất cao',
                'segment' => 'luxury',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 4,
                'meta_title' => 'BMW X5 - SUV hạng sang',
                'meta_description' => 'BMW X5 - SUV hạng sang hiệu suất cao',
                'keywords' => 'bmw x5'
            ],
            // Audi
            [
                'car_brand_id' => $brands->where('name', 'Audi')->first()->id,
                'name' => 'A4',
                'slug' => 'audi-a4',
                'description' => 'Sedan hạng sang cỡ nhỏ',
                'segment' => 'luxury',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                'meta_title' => 'Audi A4 - Sedan hạng sang',
                'meta_description' => 'Audi A4 - Sedan hạng sang cỡ nhỏ',
                'keywords' => 'audi a4'
            ],
            [
                'car_brand_id' => $brands->where('name', 'Audi')->first()->id,
                'name' => 'Q5',
                'slug' => 'audi-q5',
                'description' => 'SUV hạng sang 5 chỗ',
                'segment' => 'luxury',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'Audi Q5 - SUV hạng sang',
                'meta_description' => 'Audi Q5 - SUV hạng sang 5 chỗ',
                'keywords' => 'audi q5'
            ],
            // Volkswagen
            [
                'car_brand_id' => $brands->where('name', 'Volkswagen')->first()->id,
                'name' => 'Tiguan',
                'slug' => 'volkswagen-tiguan',
                'description' => 'SUV Đức bền bỉ',
                'segment' => 'mid-size',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                'meta_title' => 'Volkswagen Tiguan - SUV Đức',
                'meta_description' => 'Volkswagen Tiguan - Chất lượng Đức',
                'keywords' => 'vw tiguan'
            ],
            // Lexus
            [
                'car_brand_id' => $brands->where('name', 'Lexus')->first()->id,
                'name' => 'ES',
                'slug' => 'lexus-es',
                'description' => 'Sedan hạng sang êm ái',
                'segment' => 'luxury',
                'body_type' => 'sedan',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Lexus ES - Sedan hạng sang',
                'meta_description' => 'Lexus ES - êm ái, sang trọng',
                'keywords' => 'lexus es'
            ],
            // Porsche
            [
                'car_brand_id' => $brands->where('name', 'Porsche')->first()->id,
                'name' => 'Macan',
                'slug' => 'porsche-macan',
                'description' => 'SUV thể thao cỡ nhỏ',
                'segment' => 'luxury',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                'meta_title' => 'Porsche Macan - SUV thể thao',
                'meta_description' => 'Porsche Macan - SUV thể thao cỡ nhỏ',
                'keywords' => 'porsche macan'
            ],
            // Ferrari
            [
                'car_brand_id' => $brands->where('name', 'Ferrari')->first()->id,
                'name' => 'Roma',
                'slug' => 'ferrari-roma',
                'description' => 'Coupe GT sang trọng',
                'segment' => 'luxury',
                'body_type' => 'coupe',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
                'meta_title' => 'Ferrari Roma - Coupe GT',
                'meta_description' => 'Ferrari Roma - Coupe GT sang trọng',
                'keywords' => 'ferrari roma'
            ],
            // VinFast more
            [
                'car_brand_id' => $brands->where('name', 'VinFast')->first()->id,
                'name' => 'VF 5',
                'slug' => 'vinfast-vf-5',
                'description' => 'Crossover điện cỡ nhỏ',
                'segment' => 'compact',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'meta_title' => 'VinFast VF 5 - Crossover điện',
                'meta_description' => 'VinFast VF 5 - Crossover điện cỡ nhỏ',
                'keywords' => 'vinfast vf 5'
            ],
            [
                'car_brand_id' => $brands->where('name', 'VinFast')->first()->id,
                'name' => 'VF 3',
                'slug' => 'vinfast-vf-3',
                'description' => 'Mini-SUV điện đô thị',
                'segment' => 'compact',
                'body_type' => 'suv',
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 5,
                'meta_title' => 'VinFast VF 3 - Mini-SUV điện',
                'meta_description' => 'VinFast VF 3 - Mini-SUV điện đô thị',
                'keywords' => 'vinfast vf 3'
            ],
        ];

        foreach ($carModels as $model) {
            CarModel::create($model);
        }
    }
}
