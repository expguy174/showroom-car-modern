<?php $__env->startSection('title', 'Phân tích khách hàng'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Phân tích khách hàng</h1>
        <p class="text-gray-600 mt-1">Hiểu rõ hành vi và giá trị của khách hàng</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="<?php echo e(route('admin.analytics.dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        <a href="<?php echo e(route('admin.analytics.export-report', ['type' => 'customers'])); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Xuất Excel
        </a>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng khách hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($customerDemographics->total_customers ?? 0); ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Khách hàng mới (tháng)</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($customerDemographics->new_customers_this_month ?? 0); ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-plus text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tỷ lệ giữ chân</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(format_percentage($retentionRate ?? 0)); ?></p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-heart text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Giá trị vòng đời khách hàng (Top 20)</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Khách hàng</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-900">Đơn hàng</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Tổng chi tiêu</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Giá trị TB</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $customerLifetimeValue ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                
                                <div class="flex-shrink-0">
                                    <?php if($customer->user && $customer->user->userProfile && $customer->user->userProfile->avatar_path): ?>
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                             src="<?php echo e(Storage::url($customer->user->userProfile->avatar_path)); ?>" 
                                             alt="<?php echo e($customer->user->userProfile->name ?? $customer->user->email); ?>">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                <?php echo e(strtoupper(mb_substr(optional($customer->user->userProfile)->name ?? optional($customer->user)->email ?? 'KH', 0, 2, 'UTF-8'))); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">
                                        <?php echo e(optional($customer->user->userProfile)->name ?? 'Khách vãng lai'); ?>

                                    </div>
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                        <?php echo e(optional($customer->user)->email ?? '-'); ?>

                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo e($customer->total_orders); ?>

                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-gray-900"><?php echo e(format_currency($customer->total_spent)); ?></td>
                        <td class="py-3 px-4 text-right text-gray-600"><?php echo e(format_currency($customer->average_order_value)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">
                            <i class="fas fa-users text-gray-300 text-3xl mb-3 block"></i>
                            Chưa có dữ liệu khách hàng
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Mức độ tương tác</h3>
        </div>
        
        <div class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-car-side text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Lái thử</p>
                        <p class="text-sm text-gray-500">Tổng số lượt lái thử</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600"><?php echo e($engagementMetrics['test_drives'] ?? 0); ?></p>
                    <p class="text-xs text-gray-500">lượt</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Lịch hẹn dịch vụ</p>
                        <p class="text-sm text-gray-500">Bảo dưỡng và sửa chữa</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-green-600"><?php echo e($engagementMetrics['service_appointments'] ?? 0); ?></p>
                    <p class="text-xs text-gray-500">lịch hẹn</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-star text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Đánh giá</p>
                        <p class="text-sm text-gray-500">Phản hồi và đánh giá</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-purple-600"><?php echo e($engagementMetrics['reviews'] ?? 0); ?></p>
                    <p class="text-xs text-gray-500">đánh giá</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/analytics/customer_analytics.blade.php ENDPATH**/ ?>