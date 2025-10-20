<?php $__env->startSection('title', 'Báo cáo & Phân tích'); ?>

<?php $__env->startSection('content'); ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Báo cáo & Phân tích</h1>
        <p class="text-gray-600 mt-1">Tổng quan về hiệu suất kinh doanh và phân tích dữ liệu</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="<?php echo e(route('admin.analytics.sales-report')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-chart-line mr-2"></i>
            Báo cáo bán hàng
        </a>
        <a href="<?php echo e(route('admin.analytics.export-report')); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Xuất báo cáo
        </a>
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-600 text-sm font-medium">Doanh thu tháng này</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(number_format($salesData['current_month_sales'] / 1000000, 1)); ?>M</p>
                <div class="flex items-center mt-2">
                    <?php if($salesData['sales_growth'] > 0): ?>
                        <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                        <span class="text-green-600 text-xs font-medium">+<?php echo e(number_format($salesData['sales_growth'], 1)); ?>%</span>
                    <?php elseif($salesData['sales_growth'] < 0): ?>
                        <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                        <span class="text-red-600 text-xs font-medium"><?php echo e(number_format($salesData['sales_growth'], 1)); ?>%</span>
                    <?php else: ?>
                        <i class="fas fa-minus text-gray-400 text-xs mr-1"></i>
                        <span class="text-gray-500 text-xs">Không đổi</span>
                    <?php endif; ?>
                    <span class="text-gray-400 text-xs ml-1">so với tháng trước</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-600 text-sm font-medium">Khách hàng mới</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($customerData['new_customers'] ?? 0); ?></p>
                <div class="flex items-center mt-2">
                    <?php if(($customerData['customer_growth'] ?? 0) > 0): ?>
                        <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                        <span class="text-green-600 text-xs font-medium">+<?php echo e(number_format($customerData['customer_growth'], 1)); ?>%</span>
                    <?php elseif(($customerData['customer_growth'] ?? 0) < 0): ?>
                        <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                        <span class="text-red-600 text-xs font-medium"><?php echo e(number_format($customerData['customer_growth'], 1)); ?>%</span>
                    <?php else: ?>
                        <i class="fas fa-minus text-gray-400 text-xs mr-1"></i>
                        <span class="text-gray-500 text-xs">Không đổi</span>
                    <?php endif; ?>
                    <span class="text-gray-400 text-xs ml-1">so với tháng trước</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-600 text-sm font-medium">Tỷ lệ chuyển đổi</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(number_format($performanceData['conversion_rate'] ?? 0, 1)); ?>%</p>
                <div class="flex items-center mt-2">
                    <span class="text-gray-500 text-xs">Từ lái thử thành mua hàng</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <p class="text-gray-600 text-sm font-medium">Đơn hàng TB</p>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?php echo e(number_format(($performanceData['average_order_value'] ?? 0) / 1000000, 1)); ?>M</p>
                <div class="flex items-center mt-2">
                    <span class="text-gray-500 text-xs">Giá trị đơn hàng trung bình</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-receipt text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Sản phẩm bán chạy</h3>
            <a href="<?php echo e(route('admin.analytics.sales-report')); ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $salesData['top_products'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm mr-3">
                        <?php echo e($index + 1); ?>

                    </div>
                    <div>
                        <p class="font-medium text-gray-900"><?php echo e($product->brand); ?> <?php echo e($product->model); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($product->variant); ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900"><?php echo e($product->sales_count); ?></p>
                    <p class="text-xs text-gray-500">đã bán</p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-8">
                <i class="fas fa-chart-bar text-gray-300 text-3xl mb-3"></i>
                <p class="text-gray-500">Chưa có dữ liệu bán hàng</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Phân khúc khách hàng</h3>
            <a href="<?php echo e(route('admin.analytics.customer-analytics')); ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                Chi tiết <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-gray-700">Khách hàng mới</span>
                </div>
                <span class="font-semibold text-gray-900"><?php echo e($customerData['customer_segments']['new'] ?? 0); ?></span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                    <span class="text-gray-700">Khách hàng quay lại</span>
                </div>
                <span class="font-semibold text-gray-900"><?php echo e($customerData['customer_segments']['returning'] ?? 0); ?></span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-gray-400 rounded-full mr-3"></div>
                    <span class="text-gray-700">Khách hàng không hoạt động</span>
                </div>
                <span class="font-semibold text-gray-900"><?php echo e($customerData['customer_segments']['inactive'] ?? 0); ?></span>
            </div>
        </div>
    </div>
</div>


<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Báo cáo chi tiết</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="<?php echo e(route('admin.analytics.sales-report')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-chart-line text-blue-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-900">Báo cáo bán hàng</p>
                <p class="text-sm text-gray-500">Phân tích doanh thu và xu hướng</p>
            </div>
        </a>
        
        <a href="<?php echo e(route('admin.analytics.customer-analytics')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-users text-green-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-900">Phân tích khách hàng</p>
                <p class="text-sm text-gray-500">Hành vi và giá trị khách hàng</p>
            </div>
        </a>
        
        <a href="<?php echo e(route('admin.analytics.staff-performance')); ?>" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                <i class="fas fa-user-tie text-purple-600"></i>
            </div>
            <div>
                <p class="font-medium text-gray-900">Hiệu suất nhân viên</p>
                <p class="text-sm text-gray-500">Đánh giá KPI và hiệu quả</p>
            </div>
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/analytics/dashboard.blade.php ENDPATH**/ ?>