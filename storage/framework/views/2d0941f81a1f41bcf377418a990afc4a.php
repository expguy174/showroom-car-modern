<?php $__env->startSection('title', 'Hệ thống Showroom'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="text-center mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 mb-2">
            Hệ thống Showroom
        </h1>
        <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
            Khám phá mạng lưới showroom hiện đại trên toàn quốc. Tìm địa điểm gần nhất để trải nghiệm và lái thử xe.
        </p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-6 sm:mb-8">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-search text-indigo-600 mr-1"></i>Tìm kiếm
                    </label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="Tên showroom, địa chỉ..." 
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt text-indigo-600 mr-1"></i>Thành phố
                    </label>
                    <select name="city" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Tất cả thành phố</option>
                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($city); ?>" <?php if(request('city') === $city): echo 'selected'; endif; ?>><?php echo e($city); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i>Tìm kiếm
                    </button>
                </div>
            </div>
            <?php if(request()->hasAny(['search', 'city'])): ?>
                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                    <span class="text-sm text-gray-600">
                        <i class="fas fa-filter mr-1"></i>Đang lọc kết quả
                    </span>
                    <a href="<?php echo e(route('user.showrooms.index')); ?>" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                        <i class="fas fa-times mr-1"></i>Xóa bộ lọc
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <!-- Showrooms Grid -->
    <?php if($showrooms->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-8">
            <?php $__currentLoopData = $showrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $showroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:border-indigo-200 transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Header -->
                    <div class="bg-gradient-to-br from-indigo-500 via-purple-600 to-indigo-700 p-6 text-white relative overflow-hidden">
                        <div class="absolute inset-0 bg-black bg-opacity-10"></div>
                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-100 transition-colors"><?php echo e($showroom->name); ?></h3>
                                    <?php if($showroom->dealership): ?>
                                        <span class="inline-flex items-center px-3 py-1 bg-white bg-opacity-20 backdrop-blur-sm rounded-full text-xs font-semibold">
                                            <i class="fas fa-building mr-1 text-xs"></i>
                                            <?php echo e($showroom->dealership->name); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 backdrop-blur-sm rounded-full flex items-center justify-center group-hover:bg-opacity-30 transition-all">
                                        <i class="fas fa-store text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Decorative elements -->
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white bg-opacity-5 rounded-full"></div>
                        <div class="absolute -bottom-2 -left-2 w-16 h-16 bg-white bg-opacity-5 rounded-full"></div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-5">
                        <!-- Address -->
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-map-marker-alt text-indigo-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-900 font-semibold mb-1"><?php echo e($showroom->address); ?></p>
                                <p class="text-gray-600 text-sm"><?php echo e($showroom->city); ?></p>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-3">
                            <?php if($showroom->phone): ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-phone text-green-600 text-sm"></i>
                                    </div>
                                    <a href="tel:<?php echo e($showroom->phone); ?>" class="text-gray-900 hover:text-indigo-600 font-medium transition-colors"><?php echo e($showroom->phone); ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if($showroom->email): ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-envelope text-blue-600 text-sm"></i>
                                    </div>
                                    <a href="mailto:<?php echo e($showroom->email); ?>" class="text-gray-900 hover:text-indigo-600 font-medium transition-colors truncate"><?php echo e($showroom->email); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Working Hours -->
                        <?php if($showroom->working_hours): ?>
                            <div class="bg-gradient-to-r from-gray-50 to-indigo-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clock text-indigo-600 text-xs"></i>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">Giờ làm việc</span>
                                </div>
                                <p class="text-sm text-gray-600 ml-8"><?php echo e($showroom->working_hours); ?></p>
                            </div>
                        <?php endif; ?>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-2">
                            <?php if($showroom->latitude && $showroom->longitude): ?>
                                <button onclick="getDirections(<?php echo e($showroom->latitude); ?>, <?php echo e($showroom->longitude); ?>, '<?php echo e(addslashes($showroom->name)); ?>')" 
                                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-directions"></i> Chỉ đường
                                </button>
                            <?php endif; ?>
                            <?php if($showroom->phone): ?>
                                <a href="tel:<?php echo e($showroom->phone); ?>" 
                                   class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 font-semibold text-sm transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-phone"></i> Gọi ngay
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            <?php echo e($showrooms->links()); ?>

        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-store text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">Không tìm thấy showroom nào</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">Không có showroom nào phù hợp với tiêu chí tìm kiếm của bạn. Hãy thử điều chỉnh bộ lọc.</p>
            <a href="<?php echo e(route('user.showrooms.index')); ?>" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 font-semibold transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-refresh"></i> Xem tất cả showroom
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Stats -->
<div class="bg-gradient-to-br from-gray-50 to-indigo-50 py-16">
    <div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">Mạng lưới toàn quốc</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Chúng tôi tự hào với hệ thống showroom rộng khắp, mang đến trải nghiệm tốt nhất cho khách hàng.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8">
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-store text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($showrooms->total()); ?></div>
                <div class="text-gray-600 font-medium">Showroom</div>
            </div>
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($cities->count()); ?></div>
                <div class="text-gray-600 font-medium">Thành phố</div>
            </div>
            <div class="bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-2">24/7</div>
                <div class="text-gray-600 font-medium">Hỗ trợ</div>
            </div>
        </div>
    </div>
</div>

<script>
function getDirections(lat, lng, name) {
    // Check if geolocation is supported
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;
            
            // Open Google Maps with directions
            const url = 'https://www.google.com/maps/dir/' + userLat + ',' + userLng + '/' + lat + ',' + lng;
            window.open(url, '_blank');
        }, function() {
            // If geolocation fails, just open the location
            const url = 'https://www.google.com/maps/search/?api=1&query=' + lat + ',' + lng;
            window.open(url, '_blank');
        });
    } else {
        // If geolocation is not supported, just open the location
        const url = 'https://www.google.com/maps/search/?api=1&query=' + lat + ',' + lng;
        window.open(url, '_blank');
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/showrooms/index.blade.php ENDPATH**/ ?>