<?php

namespace Database\Seeders;

use App\Models\CarModelImage;
use App\Models\CarModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
 

class CarModelImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = CarModel::with('carBrand')->get();

        // Image resolver: Wikimedia Commons first, then placeholder

        $searchCommons = function (string $query) {
            return null; // Always use placeholder
        };

        // Build search query for a model image (brand + model + type + angle)
        $buildModelQuery = function (string $modelName, string $imageType, ?string $angle = null) use ($models) {
            $model = $models->firstWhere('name', $modelName);
            $brand = $model?->carBrand?->name;
            $parts = array_filter([$brand, $modelName, $imageType === 'interior' ? 'interior' : 'exterior', $angle, 'car']);
            return trim(implode(' ', $parts));
        };

        $images = [
            // Toyota Vios Images
            [
                'car_model_id' => $models->where('name', 'Vios')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Vios', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Vios exterior front'),
                'alt_text' => 'Toyota Vios - Góc nhìn phía trước',
                'title' => 'Toyota Vios - Thiết kế hiện đại',
                'description' => 'Góc nhìn phía trước của Toyota Vios với thiết kế hiện đại và sang trọng',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Vios')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Vios', 'exterior', 'side view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Vios exterior side'),
                'alt_text' => 'Toyota Vios - Góc nhìn bên',
                'title' => 'Toyota Vios - Đường nét thể thao',
                'description' => 'Góc nhìn bên của Toyota Vios với đường nét thể thao và hiện đại',
                'image_type' => 'exterior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'car_model_id' => $models->where('name', 'Vios')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Vios', 'exterior', 'rear view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Vios exterior rear'),
                'alt_text' => 'Toyota Vios - Góc nhìn phía sau',
                'title' => 'Toyota Vios - Đuôi xe hiện đại',
                'description' => 'Góc nhìn phía sau của Toyota Vios với thiết kế đuôi xe hiện đại',
                'image_type' => 'exterior',
                'is_main' => false,
                'is_featured' => false,
                'sort_order' => 3
            ],
            [
                'car_model_id' => $models->where('name', 'Vios')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Vios', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Vios interior'),
                'alt_text' => 'Toyota Vios - Nội thất',
                'title' => 'Toyota Vios - Nội thất sang trọng',
                'description' => 'Nội thất Toyota Vios với thiết kế sang trọng và tiện nghi',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 4
            ],

            // Toyota Camry Images
            [
                'car_model_id' => $models->where('name', 'Camry')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Camry', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Camry exterior front'),
                'alt_text' => 'Toyota Camry - Góc nhìn phía trước',
                'title' => 'Toyota Camry - Sang trọng',
                'description' => 'Góc nhìn phía trước của Toyota Camry với thiết kế sang trọng',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Camry')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Camry', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Camry interior'),
                'alt_text' => 'Toyota Camry - Nội thất',
                'title' => 'Toyota Camry - Nội thất cao cấp',
                'description' => 'Nội thất Toyota Camry với thiết kế cao cấp',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Toyota Fortuner Images
            [
                'car_model_id' => $models->where('name', 'Fortuner')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Fortuner', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Fortuner exterior front'),
                'alt_text' => 'Toyota Fortuner - Góc nhìn phía trước',
                'title' => 'Toyota Fortuner - SUV 7 chỗ',
                'description' => 'Góc nhìn phía trước của Toyota Fortuner với thiết kế mạnh mẽ',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Fortuner')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Fortuner', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Fortuner interior'),
                'alt_text' => 'Toyota Fortuner - Nội thất',
                'title' => 'Toyota Fortuner - Nội thất rộng rãi',
                'description' => 'Nội thất Toyota Fortuner rộng rãi và tiện nghi',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Honda Civic Images
            [
                'car_model_id' => $models->where('name', 'Civic')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Civic', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Honda Civic exterior front'),
                'alt_text' => 'Honda Civic - Góc nhìn phía trước',
                'title' => 'Honda Civic - Thể thao',
                'description' => 'Góc nhìn phía trước của Honda Civic với thiết kế thể thao',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Civic')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Civic', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Honda Civic interior'),
                'alt_text' => 'Honda Civic - Nội thất',
                'title' => 'Honda Civic - Nội thất hiện đại',
                'description' => 'Nội thất Honda Civic với thiết kế hiện đại',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Honda City Images
            [
                'car_model_id' => $models->where('name', 'City')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('City', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Honda City exterior front'),
                'alt_text' => 'Honda City - Góc nhìn phía trước',
                'title' => 'Honda City - Thiết kế thể thao',
                'description' => 'Góc nhìn phía trước của Honda City với thiết kế thể thao và năng động',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'City')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('City', 'exterior', 'side view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Honda City exterior side'),
                'alt_text' => 'Honda City - Góc nhìn bên',
                'title' => 'Honda City - Đường nét năng động',
                'description' => 'Góc nhìn bên của Honda City với đường nét năng động và hiện đại',
                'image_type' => 'exterior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'car_model_id' => $models->where('name', 'City')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('City', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Honda City interior'),
                'alt_text' => 'Honda City - Nội thất',
                'title' => 'Honda City - Nội thất hiện đại',
                'description' => 'Nội thất Honda City với thiết kế hiện đại và công nghệ tiên tiến',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 3
            ],

            // Toyota Innova Images
            [
                'car_model_id' => $models->where('name', 'Innova')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Innova', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Innova exterior front'),
                'alt_text' => 'Toyota Innova - Góc nhìn phía trước',
                'title' => 'Toyota Innova - Thiết kế đa dụng',
                'description' => 'Góc nhìn phía trước của Toyota Innova với thiết kế đa dụng và hiện đại',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Innova')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Innova', 'exterior', 'side view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Innova exterior side'),
                'alt_text' => 'Toyota Innova - Góc nhìn bên',
                'title' => 'Toyota Innova - Không gian rộng rãi',
                'description' => 'Góc nhìn bên của Toyota Innova thể hiện không gian rộng rãi',
                'image_type' => 'exterior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'car_model_id' => $models->where('name', 'Innova')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Innova', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Toyota Innova interior'),
                'alt_text' => 'Toyota Innova - Nội thất',
                'title' => 'Toyota Innova - Nội thất đa dụng',
                'description' => 'Nội thất Toyota Innova với thiết kế đa dụng cho gia đình',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 3
            ],

            // Ford Ranger Images
            [
                'car_model_id' => $models->where('name', 'Ranger')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Ranger', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Ford Ranger exterior front'),
                'alt_text' => 'Ford Ranger - Góc nhìn phía trước',
                'title' => 'Ford Ranger - Thiết kế mạnh mẽ',
                'description' => 'Góc nhìn phía trước của Ford Ranger với thiết kế mạnh mẽ và nam tính',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Ranger')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Ranger', 'exterior', 'side view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Ford Ranger exterior side'),
                'alt_text' => 'Ford Ranger - Góc nhìn bên',
                'title' => 'Ford Ranger - Đường nét mạnh mẽ',
                'description' => 'Góc nhìn bên của Ford Ranger với đường nét mạnh mẽ và thể thao',
                'image_type' => 'exterior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'car_model_id' => $models->where('name', 'Ranger')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Ranger', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Ford Ranger interior'),
                'alt_text' => 'Ford Ranger - Nội thất',
                'title' => 'Ford Ranger - Nội thất hiện đại',
                'description' => 'Nội thất Ford Ranger với thiết kế hiện đại và công nghệ tiên tiến',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 3
            ],

            // Ford Everest Images
            [
                'car_model_id' => $models->where('name', 'Everest')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Everest', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Ford Everest exterior front'),
                'alt_text' => 'Ford Everest - Góc nhìn phía trước',
                'title' => 'Ford Everest - SUV mạnh mẽ',
                'description' => 'Góc nhìn phía trước của Ford Everest với thiết kế SUV mạnh mẽ',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Everest')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Everest', 'exterior', 'side view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Ford Everest exterior side'),
                'alt_text' => 'Ford Everest - Góc nhìn bên',
                'title' => 'Ford Everest - Không gian rộng rãi',
                'description' => 'Góc nhìn bên của Ford Everest thể hiện không gian rộng rãi',
                'image_type' => 'exterior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],
            [
                'car_model_id' => $models->where('name', 'Everest')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Everest', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Ford Everest interior'),
                'alt_text' => 'Ford Everest - Nội thất',
                'title' => 'Ford Everest - Nội thất cao cấp',
                'description' => 'Nội thất Ford Everest với thiết kế cao cấp và tiện nghi',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 3
            ],

            // Hyundai Accent Images
            [
                'car_model_id' => $models->where('name', 'Accent')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Accent', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Hyundai Accent exterior front'),
                'alt_text' => 'Hyundai Accent - Góc nhìn phía trước',
                'title' => 'Hyundai Accent - Thiết kế hiện đại',
                'description' => 'Góc nhìn phía trước của Hyundai Accent với thiết kế hiện đại',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Accent')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Accent', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Hyundai Accent interior'),
                'alt_text' => 'Hyundai Accent - Nội thất',
                'title' => 'Hyundai Accent - Nội thất hiện đại',
                'description' => 'Nội thất Hyundai Accent với thiết kế hiện đại và tiện nghi',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Hyundai Tucson Images
            [
                'car_model_id' => $models->where('name', 'Tucson')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Tucson', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Hyundai Tucson exterior front'),
                'alt_text' => 'Hyundai Tucson - Góc nhìn phía trước',
                'title' => 'Hyundai Tucson - Thiết kế bold',
                'description' => 'Góc nhìn phía trước của Hyundai Tucson với thiết kế bold và hiện đại',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Tucson')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Tucson', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Hyundai Tucson interior'),
                'alt_text' => 'Hyundai Tucson - Nội thất',
                'title' => 'Hyundai Tucson - Nội thất hiện đại',
                'description' => 'Nội thất Hyundai Tucson với thiết kế hiện đại và công nghệ tiên tiến',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Kia Morning Images
            [
                'car_model_id' => $models->where('name', 'Morning')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Morning', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Kia Morning exterior front'),
                'alt_text' => 'Kia Morning - Góc nhìn phía trước',
                'title' => 'Kia Morning - Thiết kế nhỏ gọn',
                'description' => 'Góc nhìn phía trước của Kia Morning với thiết kế nhỏ gọn và dễ thương',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Morning')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Morning', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Kia Morning interior'),
                'alt_text' => 'Kia Morning - Nội thất',
                'title' => 'Kia Morning - Nội thất tiện nghi',
                'description' => 'Nội thất Kia Morning với thiết kế tiện nghi và thông minh',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Kia Sorento Images
            [
                'car_model_id' => $models->where('name', 'Sorento')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Sorento', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Kia Sorento exterior front'),
                'alt_text' => 'Kia Sorento - Góc nhìn phía trước',
                'title' => 'Kia Sorento - SUV cao cấp',
                'description' => 'Góc nhìn phía trước của Kia Sorento với thiết kế SUV cao cấp',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'Sorento')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('Sorento', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Kia Sorento interior'),
                'alt_text' => 'Kia Sorento - Nội thất',
                'title' => 'Kia Sorento - Nội thất sang trọng',
                'description' => 'Nội thất Kia Sorento với thiết kế sang trọng và cao cấp',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Mercedes-Benz C-Class Images
            [
                'car_model_id' => $models->where('name', 'C-Class')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('C-Class', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Mercedes-Benz C-Class exterior front'),
                'alt_text' => 'Mercedes-Benz C-Class - Góc nhìn phía trước',
                'title' => 'Mercedes-Benz C-Class - Sang trọng',
                'description' => 'Góc nhìn phía trước của Mercedes-Benz C-Class với thiết kế sang trọng',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'C-Class')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('C-Class', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Mercedes-Benz C-Class interior'),
                'alt_text' => 'Mercedes-Benz C-Class - Nội thất',
                'title' => 'Mercedes-Benz C-Class - Nội thất cao cấp',
                'description' => 'Nội thất Mercedes-Benz C-Class với thiết kế cao cấp và sang trọng',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // Mercedes-Benz GLC Images
            [
                'car_model_id' => $models->where('name', 'GLC')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('GLC', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Mercedes-Benz GLC exterior front'),
                'alt_text' => 'Mercedes-Benz GLC - Góc nhìn phía trước',
                'title' => 'Mercedes-Benz GLC - SUV sang trọng',
                'description' => 'Góc nhìn phía trước của Mercedes-Benz GLC với thiết kế SUV sang trọng',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'GLC')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('GLC', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Mercedes-Benz GLC interior'),
                'alt_text' => 'Mercedes-Benz GLC - Nội thất',
                'title' => 'Mercedes-Benz GLC - Nội thất cao cấp',
                'description' => 'Nội thất Mercedes-Benz GLC với thiết kế cao cấp và tiện nghi',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // BMW 3 Series Images
            [
                'car_model_id' => $models->where('name', '3 Series')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('3 Series', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('BMW 3 Series exterior front'),
                'alt_text' => 'BMW 3 Series - Góc nhìn phía trước',
                'title' => 'BMW 3 Series - Thể thao',
                'description' => 'Góc nhìn phía trước của BMW 3 Series với thiết kế thể thao',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', '3 Series')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('3 Series', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('BMW 3 Series interior'),
                'alt_text' => 'BMW 3 Series - Nội thất',
                'title' => 'BMW 3 Series - Nội thất thể thao',
                'description' => 'Nội thất BMW 3 Series với thiết kế thể thao và hiện đại',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // BMW X3 Images
            [
                'car_model_id' => $models->where('name', 'X3')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('X3', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('BMW X3 exterior front'),
                'alt_text' => 'BMW X3 - Góc nhìn phía trước',
                'title' => 'BMW X3 - SUV thể thao',
                'description' => 'Góc nhìn phía trước của BMW X3 với thiết kế SUV thể thao',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'X3')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('X3', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('BMW X3 interior'),
                'alt_text' => 'BMW X3 - Nội thất',
                'title' => 'BMW X3 - Nội thất thể thao',
                'description' => 'Nội thất BMW X3 với thiết kế thể thao và hiện đại',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // VinFast VF 8 Images
            [
                'car_model_id' => $models->where('name', 'VF 8')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('VF 8', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('VinFast VF 8 exterior front'),
                'alt_text' => 'VinFast VF 8 - Góc nhìn phía trước',
                'title' => 'VinFast VF 8 - SUV điện',
                'description' => 'Góc nhìn phía trước của VinFast VF 8 với thiết kế SUV điện hiện đại',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'VF 8')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('VF 8', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('VinFast VF 8 interior'),
                'alt_text' => 'VinFast VF 8 - Nội thất',
                'title' => 'VinFast VF 8 - Nội thất hiện đại',
                'description' => 'Nội thất VinFast VF 8 với thiết kế hiện đại và công nghệ tiên tiến',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ],

            // VinFast VF 9 Images
            [
                'car_model_id' => $models->where('name', 'VF 9')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('VF 9', 'exterior', 'front view')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('VinFast VF 9 exterior front'),
                'alt_text' => 'VinFast VF 9 - Góc nhìn phía trước',
                'title' => 'VinFast VF 9 - SUV điện cao cấp',
                'description' => 'Góc nhìn phía trước của VinFast VF 9 với thiết kế SUV điện cao cấp',
                'image_type' => 'exterior',
                'is_main' => true,
                'is_featured' => true,
                'sort_order' => 1
            ],
            [
                'car_model_id' => $models->where('name', 'VF 9')->first()->id,
                'image_path' => $searchCommons($buildModelQuery('VF 9', 'interior')) ?: 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('VinFast VF 9 interior'),
                'alt_text' => 'VinFast VF 9 - Nội thất',
                'title' => 'VinFast VF 9 - Nội thất cao cấp',
                'description' => 'Nội thất VinFast VF 9 với thiết kế cao cấp và không gian rộng rãi',
                'image_type' => 'interior',
                'is_main' => false,
                'is_featured' => true,
                'sort_order' => 2
            ]
        ];

        // Try Commons API first; if not found, fallback to placeholder with descriptive label
        foreach ($images as &$image) {
            $model = $models->firstWhere('id', $image['car_model_id']);
            $name = $model?->name ?? 'Sản phẩm';
            $label = $name;
            $resolved = 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode($label);
            $image['image_path'] = $resolved;
            $image['image_url'] = $resolved;
        }
        unset($image);

        foreach ($images as $image) {
            CarModelImage::create($image);
        }

        // Normalize flags: one main image per model, and set is_active = true for all
        $modelIds = CarModelImage::query()->distinct()->pluck('car_model_id');
        foreach ($modelIds as $modelId) {
            CarModelImage::where('car_model_id', $modelId)->update(['is_main' => false, 'is_active' => true]);
            $first = CarModelImage::where('car_model_id', $modelId)->orderBy('sort_order')->first();
            if ($first) {
                $first->is_main = true;
                $first->is_active = true;
                $first->save();
            }
        }
    }
}
