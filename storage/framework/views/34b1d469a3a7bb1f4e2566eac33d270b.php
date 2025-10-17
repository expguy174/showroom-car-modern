<?php $__env->startSection('title', 'Chi tiết phiên bản xe: ' . $carvariant->name); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 mr-4">
                    <?php if($carvariant->images->where('is_main', true)->first()): ?>
                        <img class="h-16 w-16 rounded-xl object-cover border border-gray-200 bg-white p-1" 
                             src="<?php echo e($carvariant->images->where('is_main', true)->first()->image_url); ?>" 
                             alt="<?php echo e($carvariant->name); ?>">
                    <?php else: ?>
                        <div class="h-16 w-16 rounded-xl bg-gray-100 border border-gray-200 flex items-center justify-center">
                            <i class="fas fa-car text-gray-400 text-xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo e($carvariant->name); ?></h1>
                    <p class="text-gray-600 mt-1"><?php echo e($carvariant->carModel->carBrand->name); ?> <?php echo e($carvariant->carModel->name); ?> • <?php echo e($carvariant->sku); ?></p>
                    <div class="flex items-center mt-2 space-x-2">
                        <?php if($carvariant->is_active): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Hoạt động
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Ngừng hoạt động
                            </span>
                        <?php endif; ?>

                        <?php if($carvariant->is_featured): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-star mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Nổi bật</span>
                            </span>
                        <?php endif; ?>

                        <?php if($carvariant->is_on_sale): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-orange-100 text-orange-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-percentage mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Khuyến mãi</span>
                            </span>
                        <?php endif; ?>

                        <?php if($carvariant->is_new_arrival): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-blue-100 text-blue-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-plus-circle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Mới</span>
                            </span>
                        <?php endif; ?>

                        <?php if($carvariant->is_bestseller): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-purple-100 text-purple-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-fire mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Bán chạy</span>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="<?php echo e(route('admin.carvariants.edit', $carvariant)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="<?php echo e(route('admin.carvariants.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                    Thông tin giá
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm text-gray-600">Giá gốc</div>
                        <div class="text-2xl font-bold text-gray-900"><?php echo e(number_format($carvariant->base_price, 0, ',', '.')); ?> VNĐ</div>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-sm text-green-600">Giá hiện tại</div>
                        <div class="text-2xl font-bold text-green-700"><?php echo e(number_format($carvariant->current_price, 0, ',', '.')); ?> VNĐ</div>
                        <?php if($carvariant->base_price > $carvariant->current_price): ?>
                            <div class="text-sm text-green-600 mt-1">
                                Tiết kiệm: <?php echo e(number_format($carvariant->base_price - $carvariant->current_price, 0, ',', '.')); ?> VNĐ
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <?php if($carvariant->colors && $carvariant->colors->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-warehouse text-blue-600 mr-2"></i>
                    Tồn kho theo màu
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $carvariant->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $stockInfo = \App\Helpers\StockHelper::getCarColorStock($carvariant->color_inventory ?? [], $color->id);
                            $colorHex = \App\Helpers\ColorHelper::getColorHex($color->color_name);
                        ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-full border-2 border-gray-300" style="background-color: <?php echo e($colorHex); ?>"></div>
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900"><?php echo e($color->color_name); ?></div>
                                    <?php if($color->price_adjustment && $color->price_adjustment != 0): ?>
                                        <div class="text-xs text-gray-500">
                                            <?php echo e($color->price_adjustment > 0 ? '+' : ''); ?><?php echo e(number_format($color->price_adjustment, 0, ',', '.')); ?> VNĐ
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tổng số lượng:</span>
                                    <span class="font-semibold text-gray-900"><?php echo e($stockInfo['quantity'] ?? 0); ?> xe</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Đang xử lý:</span>
                                    <span class="font-semibold text-orange-600"><?php echo e($stockInfo['reserved'] ?? 0); ?> xe</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                    <span class="text-gray-700 font-medium">Khả dụng:</span>
                                    <span class="font-bold text-lg <?php echo e($stockInfo['available'] > 0 ? 'text-green-600' : 'text-red-600'); ?>">
                                        <?php echo e($stockInfo['available']); ?> xe
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($carvariant->images->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-images text-purple-600 mr-2"></i>
                    Thư viện ảnh (<?php echo e($carvariant->images->count()); ?>)
                </h3>
                
                <?php
                    $mainImage = $carvariant->images->where('is_main', true)->first() ?? $carvariant->images->first();
                    $imageTypes = $carvariant->images->groupBy('image_type');
                    $allImages = $carvariant->images->sortBy('sort_order');
                    // Default to desktop size (4), JavaScript will handle responsive
                    $perPage = 4;
                    
                    // Initial load - first page of all images
                    $initialImages = $allImages->take($perPage);
                ?>
                
                
                <div class="mb-4 max-w-2xl mx-auto">
                    <div class="relative group">
                        <img id="mainImage" 
                             src="<?php echo e($mainImage->image_url); ?>" 
                             alt="<?php echo e($mainImage->alt_text ?? $carvariant->name); ?>"
                             class="w-full h-64 md:h-80 object-cover rounded-lg border border-gray-200 shadow-sm cursor-pointer"
                             onclick="viewImage('<?php echo e($mainImage->image_url); ?>', '<?php echo e($mainImage->title ?? $carvariant->name); ?>')">
                    
                    
                    <?php if($carvariant->images->count() > 1): ?>
                    <button id="prevBtn" onclick="previousImage()" 
                            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="nextBtn" onclick="nextImage()" 
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <?php endif; ?>
                    
                        
                        <?php if($carvariant->images->count() > 1): ?>
                        <div class="absolute bottom-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm">
                            <span id="currentImageIndex">1</span> / <?php echo e($carvariant->images->count()); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                
                <?php if($imageTypes->count() > 1): ?>
                <div class="flex flex-wrap gap-2 mb-4 border-b border-gray-200 pb-3">
                    <button onclick="filterImages('all')" class="filter-btn active px-3 py-1.5 text-sm font-medium rounded-lg bg-blue-100 text-blue-700 border border-blue-200" data-type="all">
                        <i class="fas fa-th mr-1"></i>Tất cả (<?php echo e($allImages->count()); ?>)
                    </button>
                    <?php $__currentLoopData = $imageTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $images): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $typeConfig = [
                                'gallery' => ['icon' => 'fas fa-images', 'text' => 'Thư viện'],
                                'exterior' => ['icon' => 'fas fa-car', 'text' => 'Ngoại thất'],
                                'interior' => ['icon' => 'fas fa-couch', 'text' => 'Nội thất'],
                                'engine' => ['icon' => 'fas fa-cog', 'text' => 'Động cơ'],
                                'wheel' => ['icon' => 'fas fa-circle', 'text' => 'Bánh xe'],
                                'detail' => ['icon' => 'fas fa-search-plus', 'text' => 'Chi tiết']
                            ];
                            $config = $typeConfig[$type] ?? ['icon' => 'fas fa-images', 'text' => ucfirst($type)];
                        ?>
                        <button onclick="filterImages('<?php echo e($type); ?>')" class="filter-btn px-3 py-1.5 text-sm font-medium rounded-lg bg-gray-100 text-gray-700 border border-gray-200 hover:bg-gray-200" data-type="<?php echo e($type); ?>">
                            <i class="<?php echo e($config['icon']); ?> mr-1"></i><?php echo e($config['text']); ?> (<?php echo e($images->count()); ?>)
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                
                <div class="space-y-4">
                    <div id="imageGrid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php $__currentLoopData = $initialImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="image-item relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-colors thumbnail-item <?php echo e($loop->first ? 'active' : ''); ?>" 
                                 data-type="<?php echo e($image->image_type); ?>" data-index="<?php echo e($index); ?>">
                                
                                
                                <div class="relative cursor-pointer" onclick="changeMainImageByIndex(<?php echo e($index); ?>)">
                                    <img src="<?php echo e($image->image_url); ?>" alt="<?php echo e($image->alt_text ?? $carvariant->name); ?>"
                                         class="w-full h-32 object-cover hover:opacity-90 transition-opacity">
                                </div>
                                
                                
                                <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                    <?php if($image->is_main): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-star mr-1"></i>Chính
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                
                                <div class="p-2">
                                    <?php if($image->title): ?>
                                        <p class="text-xs font-medium text-gray-900 truncate mb-1"><?php echo e($image->title); ?></p>
                                    <?php endif; ?>
                                    <?php if($image->alt_text): ?>
                                        <p class="text-xs text-gray-600 truncate"><?php echo e($image->alt_text); ?></p>
                                    <?php endif; ?>
                                    <div class="flex items-center justify-between mt-1">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            <?php switch($image->image_type):
                                                case ('gallery'): ?> Thư viện <?php break; ?>
                                                <?php case ('interior'): ?> Nội thất <?php break; ?>
                                                <?php case ('exterior'): ?> Ngoại thất <?php break; ?>
                                                <?php case ('engine'): ?> Động cơ <?php break; ?>
                                                <?php case ('wheel'): ?> Bánh xe <?php break; ?>
                                                <?php default: ?> <?php echo e(ucfirst($image->image_type)); ?>

                                            <?php endswitch; ?>
                                        </span>
                                    </div>
                                    <?php if($image->description): ?>
                                        <p class="text-xs text-gray-500 mt-1 truncate" title="<?php echo e($image->description); ?>"><?php echo e($image->description); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    
                    <div id="imagePagination" class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div id="imageInfo" class="text-sm text-gray-600">
                            Hiển thị 1-<?php echo e(min(4, $allImages->count())); ?> trong <?php echo e($allImages->count()); ?> ảnh
                        </div>
                        <div id="paginationControls" class="flex items-center space-x-1">
                            
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>


            
            <?php if($carvariant->specifications->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cogs text-gray-600 mr-2"></i>
                    Thông số kỹ thuật (<?php echo e($carvariant->specifications->count()); ?>)
                </h3>
                <?php
                    $specsByCategory = $carvariant->specifications->groupBy('category');
                    $categoryNames = [
                        'engine' => 'Động cơ',
                        'performance' => 'Hiệu suất',
                        'dimensions' => 'Kích thước',
                        'weight' => 'Trọng lượng',
                        'fuel' => 'Nhiên liệu',
                        'transmission' => 'Hộp số',
                        'suspension' => 'Hệ thống treo',
                        'brakes' => 'Phanh',
                        'brake' => 'Phanh',
                        'wheels' => 'Bánh xe',
                        'safety' => 'An toàn',
                        'comfort' => 'Tiện nghi',
                        'technology' => 'Công nghệ',
                        'chassis' => 'Khung gầm',
                        'seating' => 'Ghế ngồi',
                        'warranty' => 'Bảo hành',
                        'other' => 'Khác'
                    ];
                    
                    // Vietnamese translation for spec names
                    $specNameTranslations = [
                        // Engine specs
                        'Engine Type' => 'Loại động cơ',
                        'Displacement' => 'Dung tích xi-lanh',
                        'Max Power' => 'Công suất tối đa',
                        'Max Torque' => 'Mô-men xoắn tối đa',
                        'Cylinders' => 'Số xi-lanh',
                        'Valves' => 'Số van',
                        'Compression Ratio' => 'Tỷ số nén',
                        'Fuel System' => 'Hệ thống nhiên liệu',
                        
                        // Performance specs
                        'Top Speed' => 'Tốc độ tối đa',
                        'Acceleration 0-100' => 'Tăng tốc 0-100km/h',
                        'Fuel Consumption City' => 'Tiêu hao nhiên liệu trong thành phố',
                        'Fuel Consumption Highway' => 'Tiêu hao nhiên liệu trên cao tốc',
                        'Fuel Consumption Combined' => 'Tiêu hao nhiên liệu kết hợp',
                        
                        // Dimensions specs
                        'Length' => 'Chiều dài',
                        'Width' => 'Chiều rộng',
                        'Height' => 'Chiều cao',
                        'Wheelbase' => 'Chiều dài cơ sở',
                        'Ground Clearance' => 'Khoảng sáng gầm xe',
                        'Turning Radius' => 'Bán kính quay vòng',
                        
                        // Weight specs
                        'Curb Weight' => 'Trọng lượng không tải',
                        'Gross Weight' => 'Trọng lượng toàn tải',
                        'Payload' => 'Tải trọng',
                        
                        // Transmission specs
                        'Transmission Type' => 'Loại hộp số',
                        'Gears' => 'Số cấp',
                        'Drive Type' => 'Hệ dẫn động',
                        
                        // Suspension specs
                        'Front Suspension' => 'Hệ thống treo trước',
                        'Rear Suspension' => 'Hệ thống treo sau',
                        
                        // Brakes specs
                        'Front Brakes' => 'Phanh trước',
                        'Rear Brakes' => 'Phanh sau',
                        'ABS' => 'Hệ thống chống bó cứng phanh',
                        'EBD' => 'Phân phối lực phanh điện tử',
                        'Brake Assist' => 'Hỗ trợ phanh khẩn cấp',
                        
                        // Wheels specs
                        'Wheel Size' => 'Kích thước bánh xe',
                        'Tire Size' => 'Kích thước lốp',
                        'Spare Tire' => 'Lốp dự phòng',
                        
                        // Safety specs
                        'Airbags' => 'Túi khí',
                        'Seatbelts' => 'Dây an toàn',
                        'Child Safety Locks' => 'Khóa an toàn trẻ em',
                        'Immobilizer' => 'Chống trộm động cơ',
                        'Security System' => 'Hệ thống chống trộm',
                        
                        // Technology specs
                        'Infotainment System' => 'Hệ thống giải trí',
                        'Screen Size' => 'Kích thước màn hình',
                        'Connectivity' => 'Kết nối',
                        'USB Ports' => 'Cổng USB',
                        'Bluetooth' => 'Bluetooth',
                        'WiFi' => 'WiFi',
                        'Navigation' => 'Dẫn đường',
                        'Sound System' => 'Hệ thống âm thanh',
                        'Speakers' => 'Loa',
                        
                        // Chassis specs
                        'Body Type' => 'Kiểu dáng thân xe',
                        'Doors' => 'Số cửa',
                        'Seating Capacity' => 'Số chỗ ngồi',
                        'Trunk Capacity' => 'Dung tích cốp xe',
                        'Fuel Tank Capacity' => 'Dung tích bình nhiên liệu',
                        
                        // Seating specs
                        'Seat Material' => 'Chất liệu ghế',
                        'Driver Seat Adjustment' => 'Chỉnh ghế lái',
                        'Passenger Seat Adjustment' => 'Chỉnh ghế phụ',
                        'Seat Heating' => 'Sưởi ghế',
                        'Seat Ventilation' => 'Thông gió ghế',
                        'Memory Seats' => 'Ghế nhớ vị trí',
                        
                        // Comfort specs
                        'Air Conditioning' => 'Điều hòa không khí',
                        'Climate Control' => 'Điều hòa tự động',
                        'Power Windows' => 'Cửa sổ điện',
                        'Power Steering' => 'Trợ lực lái',
                        'Cruise Control' => 'Ga tự động',
                        'Keyless Entry' => 'Khóa thông minh',
                        'Push Start' => 'Khởi động bằng nút bấm',
                        
                        // Warranty specs
                        'Basic Warranty' => 'Bảo hành cơ bản',
                        'Engine Warranty' => 'Bảo hành động cơ',
                        'Paint Warranty' => 'Bảo hành sơn',
                        'Roadside Assistance' => 'Hỗ trợ khẩn cấp',
                        
                        // Fuel specs
                        'Fuel Type' => 'Loại nhiên liệu',
                        'Fuel Grade' => 'Chỉ số octane',
                        'Emission Standard' => 'Tiêu chuẩn khí thải'
                    ];
                ?>
                
                <div class="space-y-6">
                    <?php $__currentLoopData = $specsByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $specs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                            <?php switch($category):
                                case ('engine'): ?>
                                    <i class="fas fa-cog text-red-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('performance'): ?>
                                    <i class="fas fa-tachometer-alt text-orange-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('dimensions'): ?>
                                    <i class="fas fa-ruler-combined text-blue-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('weight'): ?>
                                    <i class="fas fa-weight text-purple-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('fuel'): ?>
                                    <i class="fas fa-gas-pump text-green-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('transmission'): ?>
                                    <i class="fas fa-cogs text-indigo-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('suspension'): ?>
                                    <i class="fas fa-car-side text-yellow-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('brakes'): ?>
                                <?php case ('brake'): ?>
                                    <i class="fas fa-stop-circle text-red-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('wheels'): ?>
                                    <i class="fas fa-circle text-gray-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('safety'): ?>
                                    <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('comfort'): ?>
                                    <i class="fas fa-couch text-pink-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('technology'): ?>
                                    <i class="fas fa-microchip text-blue-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('chassis'): ?>
                                    <i class="fas fa-car-crash text-gray-700 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('seating'): ?>
                                    <i class="fas fa-chair text-brown-600 mr-2"></i>
                                    <?php break; ?>
                                <?php case ('warranty'): ?>
                                    <i class="fas fa-certificate text-gold-600 mr-2"></i>
                                    <?php break; ?>
                                <?php default: ?>
                                    <i class="fas fa-info-circle text-gray-600 mr-2"></i>
                            <?php endswitch; ?>
                            <?php echo e($categoryNames[$category] ?? ucfirst($category)); ?>

                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <?php $__currentLoopData = $specs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="font-medium text-gray-700"><?php echo e($spec->spec_name); ?></span>
                                <span class="text-gray-900"><?php echo e($spec->spec_value); ?><?php if($spec->unit): ?> <?php echo e($spec->unit); ?><?php endif; ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>


            
            <?php if($relatedVariants->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-car text-blue-600 mr-2"></i>
                    Phiên bản khác cùng dòng xe (<?php echo e($relatedVariants->count()); ?>)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php $__currentLoopData = $relatedVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900"><?php echo e($variant->name); ?></h4>
                                <p class="text-sm text-gray-500"><?php echo e($variant->sku); ?></p>
                                <p class="text-lg font-bold text-green-600 mt-1">
                                    <?php echo e(number_format($variant->current_price, 0, ',', '.')); ?> VNĐ
                                </p>
                            </div>
                            <div class="ml-4">
                                <a href="<?php echo e(route('admin.carvariants.show', $variant)); ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Xem chi tiết <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info text-blue-600 mr-2"></i>
                    Thông tin cơ bản
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Hãng xe:</span>
                        <span class="font-medium"><?php echo e($carvariant->carModel->carBrand->name); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Dòng xe:</span>
                        <span class="font-medium"><?php echo e($carvariant->carModel->name); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">SKU:</span>
                        <span class="font-medium font-mono text-sm"><?php echo e($carvariant->sku); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Slug:</span>
                        <span class="font-medium font-mono text-sm"><?php echo e($carvariant->slug); ?></span>
                    </div>
                    
                    <?php if($carvariant->short_description): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Mô tả ngắn:</span>
                        <span class="font-medium text-sm"><?php echo e($carvariant->short_description); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Ngày tạo:</span>
                        <span class="font-medium"><?php echo e($carvariant->created_at->format('d/m/Y H:i')); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Cập nhật cuối:</span>
                        <span class="font-medium"><?php echo e($carvariant->updated_at->format('d/m/Y H:i')); ?></span>
                    </div>
                </div>
            </div>

            
            <?php if($carvariant->meta_title || $carvariant->meta_description || $carvariant->keywords): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-search text-blue-600 mr-2"></i>
                    Thông tin SEO
                </h3>
                <div class="space-y-3">
                    <?php if($carvariant->meta_title): ?>
                    <div>
                        <span class="text-gray-600 block mb-1">Meta Title:</span>
                        <p class="font-medium text-sm"><?php echo e($carvariant->meta_title); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($carvariant->meta_description): ?>
                    <div>
                        <span class="text-gray-600 block mb-1">Meta Description:</span>
                        <p class="font-medium text-sm"><?php echo e($carvariant->meta_description); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($carvariant->keywords): ?>
                    <div>
                        <span class="text-gray-600 block mb-1">Keywords:</span>
                        <div class="flex flex-wrap gap-1">
                            <?php $__currentLoopData = explode(',', $carvariant->keywords); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keyword): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    <?php echo e(trim($keyword)); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>


            
            <?php if($carvariant->description): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Mô tả chi tiết
                </h3>
                <div class="prose prose-sm max-w-none text-gray-700">
                    <?php echo nl2br(e($carvariant->description)); ?>

                </div>
            </div>
            <?php endif; ?>

            
            <?php if($carvariant->colors->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-palette text-pink-600 mr-2"></i>
                    Màu sắc có sẵn (<?php echo e($carvariant->colors->count()); ?>)
                </h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $carvariant->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                        <div class="w-8 h-8 rounded-full border border-gray-300 mr-3" 
                             style="background-color: <?php echo e($color->hex_code); ?>"></div>
                        <div class="flex-1">
                            <div class="font-medium text-gray-900"><?php echo e($color->color_name); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($color->color_code ?? $color->hex_code); ?></div>
                        </div>
                        <?php if($color->stock_quantity !== null): ?>
                        <div class="text-sm text-gray-600">
                            Tồn kho: <?php echo e($color->stock_quantity); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($carvariant->featuresRelation->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list-check text-green-600 mr-2"></i>
                    Tính năng nổi bật (<?php echo e($carvariant->featuresRelation->count()); ?>)
                </h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $carvariant->featuresRelation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900"><?php echo e($feature->feature_name); ?></div>
                            <?php if($feature->description): ?>
                                <div class="text-sm text-gray-600"><?php echo e($feature->description); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                    Thống kê
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Số màu:</span>
                        <span class="font-medium"><?php echo e($carvariant->colors->count()); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Số ảnh:</span>
                        <span class="font-medium"><?php echo e($carvariant->images->count()); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thông số kỹ thuật:</span>
                        <span class="font-medium"><?php echo e($carvariant->specifications->count()); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tính năng:</span>
                        <span class="font-medium"><?php echo e($carvariant->featuresRelation->count()); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Đánh giá:</span>
                        <span class="font-medium"><?php echo e($carvariant->reviews->count()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 id="imageTitle" class="text-lg font-semibold text-gray-900"></h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-4">
            <img id="modalImage" src="" alt="" class="max-w-full h-auto">
        </div>
    </div>
</div>

<script>
// Images data array
const images = [
    <?php $__currentLoopData = $carvariant->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    {
        url: "<?php echo e($image->image_url); ?>",
        alt: "<?php echo e(addslashes($image->alt_text ?? $carvariant->name)); ?>",
        title: "<?php echo e(addslashes($image->title ?? $carvariant->name)); ?>",
        image_type: "<?php echo e($image->image_type ?? 'gallery'); ?>"
    }<?php if(!$loop->last): ?>,<?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
];

let currentImageIndex = 0;

// Pagination variables
let currentPage = 1;
let perPage = 4;
let totalImages = 0;
let totalPages = 0;
let currentFilter = 'all';
let filteredImages = [];
let allImagesData = [];

// Change main image by index
function changeMainImageByIndex(index) {
    if (index >= 0 && index < images.length) {
        currentImageIndex = index;
        const image = images[index];
        changeMainImage(image.url, image.alt, image.title);
        updateImageCounter();
        updateThumbnailActive();
    }
}

// Change main image when clicking thumbnail
function changeMainImage(url, alt, title) {
    const mainImage = document.getElementById('mainImage');
    if (mainImage) {
        mainImage.src = url;
        mainImage.alt = alt || 'Hình ảnh';
        // Update onclick for modal
        mainImage.onclick = function() {
            viewImage(url, title || alt || 'Hình ảnh');
        };
    }
}

// Navigate to previous image
function previousImage() {
    const prevIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
    changeMainImageByIndex(prevIndex);
    // Sync with thumbnail pagination
    syncThumbnailPagination(prevIndex);
}

// Navigate to next image
function nextImage() {
    const nextIndex = currentImageIndex < images.length - 1 ? currentImageIndex + 1 : 0;
    changeMainImageByIndex(nextIndex);
    // Sync with thumbnail pagination
    syncThumbnailPagination(nextIndex);
}

// Sync thumbnail pagination when using main image arrows
function syncThumbnailPagination(imageIndex) {
    // Find which page contains this image
    const targetPage = Math.ceil((imageIndex + 1) / perPage);
    if (targetPage !== currentPage) {
        currentPage = targetPage;
        renderImages();
        updatePagination();
    }
    // Update active thumbnail
    updateThumbnailActive();
}

// Update image counter
function updateImageCounter() {
    const counter = document.getElementById('currentImageIndex');
    if (counter) {
        counter.textContent = currentImageIndex + 1;
    }
}

// Update thumbnail active state
function updateThumbnailActive() {
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    thumbnails.forEach((thumb) => {
        const thumbIndex = parseInt(thumb.getAttribute('data-index'));
        if (thumbIndex === currentImageIndex) {
            // Active entire card like Accessory with shadow
            thumb.classList.add('active', 'shadow-lg');
            thumb.style.borderColor = '#3B82F6'; // Blue-500
            thumb.style.borderWidth = '2px';
        } else {
            // Inactive state
            thumb.classList.remove('active', 'shadow-lg');
            thumb.style.borderColor = '#E5E7EB'; // Gray-200
            thumb.style.borderWidth = '1px';
        }
    });
}

function viewImage(url, title) {
    document.getElementById('modalImage').src = url;
    document.getElementById('imageTitle').textContent = title || 'Hình ảnh';
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (document.getElementById('imageModal').classList.contains('hidden')) {
        // Only work when modal is closed
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            previousImage();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextImage();
        }
    }
});

// Pagination functions
function updatePagination() {
    const imageInfo = document.getElementById('imageInfo');
    const paginationControls = document.getElementById('paginationControls');
    
    // Update info text
    const startIndex = (currentPage - 1) * perPage + 1;
    const endIndex = Math.min(currentPage * perPage, totalImages);
    imageInfo.textContent = `Hiển thị ${startIndex}-${endIndex} trong ${totalImages} ảnh`;
    
    // Generate pagination controls
    paginationControls.innerHTML = '';
    
    if (totalPages > 1) {
        // Previous button
        if (currentPage > 1) {
            const prevBtn = document.createElement('button');
            prevBtn.className = 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            prevBtn.innerHTML = '<i class="fas fa-chevron-left mr-1"></i>Trước';
            prevBtn.onclick = () => goToPage(currentPage - 1);
            paginationControls.appendChild(prevBtn);
        }
        
        // Page numbers (show max 5 pages)
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = i === currentPage 
                ? 'inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-300 rounded-lg'
                : 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            pageBtn.textContent = i;
            if (i !== currentPage) {
                pageBtn.onclick = () => goToPage(i);
            }
            paginationControls.appendChild(pageBtn);
        }
        
        // Next button
        if (currentPage < totalPages) {
            const nextBtn = document.createElement('button');
            nextBtn.className = 'inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors';
            nextBtn.innerHTML = 'Sau<i class="fas fa-chevron-right ml-1"></i>';
            nextBtn.onclick = () => goToPage(currentPage + 1);
            paginationControls.appendChild(nextBtn);
        }
    }
}

function goToPage(page) {
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        renderImages();
        updatePagination();
    }
}

function renderImages() {
    const imageGrid = document.getElementById('imageGrid');
    const startIndex = (currentPage - 1) * perPage;
    const endIndex = startIndex + perPage;
    const imagesToShow = filteredImages.slice(startIndex, endIndex);
    
    // Clear current images
    imageGrid.innerHTML = '';
    
    // Render new images
    imagesToShow.forEach((imageData, index) => {
        const actualIndex = startIndex + index;
        const card = createImageCard(imageData, actualIndex);
        imageGrid.appendChild(card);
    });
}

function createImageCard(imageData, index) {
    const card = document.createElement('div');
    card.className = 'image-item relative bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:border-blue-300 transition-colors thumbnail-item';
    card.setAttribute('data-type', imageData.image_type);
    card.setAttribute('data-index', imageData.index); // Use original image index
    
    card.innerHTML = `
        <div class="relative cursor-pointer" onclick="changeMainImageByIndex(${imageData.index})">
            <img src="${imageData.url}" alt="${imageData.alt}" class="w-full h-32 object-cover hover:opacity-90 transition-opacity">
        </div>
        <div class="p-2">
            <p class="text-xs font-medium text-gray-900 truncate mb-1">${imageData.title}</p>
            <p class="text-xs text-gray-600 truncate">${imageData.alt}</p>
            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                ${getTypeText(imageData.image_type)}
            </span>
        </div>
    `;
    
    return card;
}

// Filter images by type
function filterImages(type) {
    currentFilter = type;
    currentPage = 1; // Reset to first page
    
    // Update filter button states
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-100', 'text-blue-700');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    // Set active filter button
    const activeBtn = document.querySelector(`[data-type="${type}"], [onclick="filterImages('${type}')"]`);
    if (activeBtn) {
        activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        activeBtn.classList.add('active', 'bg-blue-100', 'text-blue-700');
    }
    
    // Filter images
    if (type === 'all') {
        filteredImages = [...allImagesData];
    } else {
        filteredImages = allImagesData.filter(img => img.image_type === type);
    }
    
    // Update pagination
    totalImages = filteredImages.length;
    totalPages = Math.ceil(totalImages / perPage);
    
    // Render filtered images
    renderImages();
    updatePagination();
}

// Get Vietnamese text for image type
function getTypeText(type) {
    const typeConfig = {
        'gallery': 'Thư viện',
        'exterior': 'Ngoại thất', 
        'interior': 'Nội thất',
        'engine': 'Động cơ',
        'wheel': 'Bánh xe',
        'detail': 'Chi tiết'
    };
    return typeConfig[type] || type;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial active thumbnail
    updateThumbnailActive();
    
    // Initialize pagination with actual image types from PHP
    allImagesData = images.map((img, index) => ({
        ...img,
        index: index
    }));
    filteredImages = [...allImagesData];
    totalImages = filteredImages.length;
    totalPages = Math.ceil(totalImages / perPage);
    updatePagination();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/carvariants/show.blade.php ENDPATH**/ ?>