<?php $__env->startSection('title', 'Hiệu suất Showroom'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Hiệu suất Showroom</h1>
        <p class="text-gray-600 mt-1">Theo dõi và đánh giá hiệu quả hoạt động của các showroom</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="<?php echo e(route('admin.analytics.dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng Showroom</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($summary['total_showrooms']); ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-building text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng lái thử</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(format_number($summary['total_test_drives'])); ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-car text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng dịch vụ</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(format_number($summary['total_services'])); ?></p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-tools text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tỷ lệ chuyển đổi TB</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(format_percentage($summary['avg_conversion'] ?? 0)); ?></p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>


<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Chi tiết hiệu suất theo Showroom</h3>
        <div class="text-sm text-gray-500">
            <i class="fas fa-info-circle mr-1"></i>
            Sắp xếp theo điểm tương tác
        </div>
    </div>
    
    <?php if($showroomPerformance->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Showroom</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-900">Lái thử</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-900">Dịch vụ</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-900">Điểm tương tác</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Tỷ lệ chuyển đổi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $showroomPerformance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $performance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-4 px-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center text-white font-semibold mr-3">
                                    <i class="fas fa-building text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-gray-900 font-medium"><?php echo e($performance->showroom_name); ?></p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-gray-500"><?php echo e($performance->showroom_code); ?></span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs text-gray-500">
                                            <i class="fas fa-map-marker-alt mr-1"></i><?php echo e($performance->city); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-center">
                                <p class="text-gray-900 font-semibold"><?php echo e($performance->test_drives_total); ?></p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    <?php echo e($performance->test_drives_completed); ?> hoàn thành
                                </p>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="text-center">
                                <p class="text-gray-900 font-semibold"><?php echo e($performance->service_appointments_total); ?></p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    <?php echo e($performance->service_appointments_completed); ?> hoàn thành
                                </p>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <?php
                                $score = $performance->engagement_score;
                                $color = $score >= 50 ? 'text-green-600' : ($score >= 20 ? 'text-blue-600' : 'text-gray-600');
                                $bgColor = $score >= 50 ? 'bg-green-100' : ($score >= 20 ? 'bg-blue-100' : 'bg-gray-100');
                                $icon = $score >= 50 ? 'fa-star' : ($score >= 20 ? 'fa-thumbs-up' : 'fa-minus');
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?php echo e($bgColor); ?> <?php echo e($color); ?>">
                                <i class="fas <?php echo e($icon); ?> mr-1.5"></i>
                                <?php echo e($score); ?>

                            </span>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <?php
                                $rate = $performance->test_drive_conversion;
                                $rateColor = $rate >= 70 ? 'text-green-600' : ($rate >= 50 ? 'text-yellow-600' : 'text-red-600');
                                $rateBg = $rate >= 70 ? 'bg-green-100' : ($rate >= 50 ? 'bg-yellow-100' : 'bg-red-100');
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($rateBg); ?> <?php echo e($rateColor); ?>">
                                <?php echo e(format_percentage($rate)); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-16">
            <i class="fas fa-building text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500 text-lg mb-2">Chưa có showroom nào hoạt động</p>
            <p class="text-gray-400 text-sm">Vui lòng thêm showroom để xem hiệu suất</p>
        </div>
    <?php endif; ?>
</div>


<?php if($showroomPerformance->count() > 0): ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-trophy text-yellow-500 mr-2"></i>
            Top Performers
        </h3>
        <div class="space-y-3">
            <?php $__currentLoopData = $showroomPerformance->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $performance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm mr-3
                        <?php echo e($index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-700' : 'bg-orange-100 text-orange-700')); ?>">
                        #<?php echo e($index + 1); ?>

                    </div>
                    <div>
                        <p class="font-medium text-gray-900"><?php echo e($performance->showroom_name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($performance->city); ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-900"><?php echo e($performance->engagement_score); ?> điểm</p>
                    <p class="text-xs text-gray-500"><?php echo e(format_percentage($performance->test_drive_conversion)); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
            Thống kê nhanh
        </h3>
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Showroom tích cực nhất:</span>
                <span class="font-semibold text-gray-900"><?php echo e($showroomPerformance->first()->showroom_name ?? 'N/A'); ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Tỷ lệ chuyển đổi cao nhất:</span>
                <span class="font-semibold text-gray-900"><?php echo e(format_percentage($showroomPerformance->max('test_drive_conversion') ?? 0)); ?></span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Tổng hoạt động:</span>
                <span class="font-semibold text-gray-900"><?php echo e(format_number($summary['total_test_drives'] + $summary['total_services'])); ?></span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/analytics/showroom_performance.blade.php ENDPATH**/ ?>