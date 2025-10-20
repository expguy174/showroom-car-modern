<?php $__env->startSection('title', 'Báo cáo bán hàng'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Báo cáo bán hàng</h1>
        <p class="text-gray-600 mt-1">Phân tích chi tiết doanh thu và hiệu quả bán hàng</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="<?php echo e(route('admin.analytics.dashboard')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        <a href="<?php echo e(route('admin.analytics.export-report', ['type' => 'sales'])); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Xuất Excel
        </a>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng đơn hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($salesSummary->total_orders ?? 0); ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng doanh thu</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(number_format(($salesSummary->total_revenue ?? 0) / 1000000, 1)); ?>M</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Giá trị trung bình</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(number_format(($salesSummary->average_order_value ?? 0) / 1000000, 1)); ?>M</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Khách hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($salesSummary->unique_customers ?? 0); ?></p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Xu hướng theo ngày</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Ngày</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Doanh thu</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Đơn hàng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $dailySales ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-900"><?php echo e(\Carbon\Carbon::parse($sale->date)->format('d/m/Y')); ?></td>
                        <td class="py-3 px-4 text-right font-medium text-gray-900"><?php echo e(number_format($sale->daily_revenue)); ?> VNĐ</td>
                        <td class="py-3 px-4 text-right text-gray-600"><?php echo e($sale->daily_orders); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="py-8 text-center text-gray-500">
                            <i class="fas fa-chart-line text-gray-300 text-3xl mb-3 block"></i>
                            Không có dữ liệu bán hàng
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Hiệu quả sản phẩm</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Sản phẩm</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">SL bán</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Doanh thu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $productPerformance ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e($product->brand); ?> <?php echo e($product->model); ?></p>
                                <p class="text-sm text-gray-500"><?php echo e($product->variant); ?></p>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-gray-900"><?php echo e($product->units_sold); ?></td>
                        <td class="py-3 px-4 text-right text-gray-600"><?php echo e(number_format($product->revenue)); ?> VNĐ</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" class="py-8 text-center text-gray-500">
                            <i class="fas fa-car text-gray-300 text-3xl mb-3 block"></i>
                            Chưa có dữ liệu sản phẩm
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/analytics/sales_report.blade.php ENDPATH**/ ?>