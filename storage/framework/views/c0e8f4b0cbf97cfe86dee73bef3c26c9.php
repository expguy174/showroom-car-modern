<?php $__env->startSection('title', 'Chi tiết dịch vụ: ' . $service->name); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-gray-900"><?php echo e($service->name); ?></h1>
                    <?php if($service->code): ?>
                        <span class="text-sm text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded"><?php echo e($service->code); ?></span>
                    <?php endif; ?>
                </div>
                <div class="flex items-center mt-3 space-x-2">
                    <?php if($service->is_active): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>
                            Hoạt động
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>
                            Tạm dừng
                        </span>
                    <?php endif; ?>
                    <?php if($service->is_featured): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i>
                            Nổi bật
                        </span>
                    <?php endif; ?>
                    <?php switch($service->category):
                        case ('maintenance'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-wrench mr-1"></i>Bảo dưỡng
                            </span>
                            <?php break; ?>
                        <?php case ('repair'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-tools mr-1"></i>Sửa chữa
                            </span>
                            <?php break; ?>
                        <?php case ('diagnostic'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-stethoscope mr-1"></i>Chẩn đoán
                            </span>
                            <?php break; ?>
                        <?php case ('cosmetic'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-spray-can mr-1"></i>Làm đẹp
                            </span>
                            <?php break; ?>
                        <?php case ('emergency'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Khẩn cấp
                            </span>
                            <?php break; ?>
                    <?php endswitch; ?>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('admin.services.edit', $service)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="<?php echo e(route('admin.services.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            
            <?php if($service->description): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-align-left text-blue-600 mr-2"></i>
                    Mô tả dịch vụ
                </h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line"><?php echo e($service->description); ?></p>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($service->requirements): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-list-check text-blue-600 mr-2"></i>
                    Yêu cầu thực hiện
                </h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line"><?php echo e($service->requirements); ?></p>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($service->notes): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-sticky-note text-blue-600 mr-2"></i>
                    Ghi chú
                </h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line"><?php echo e($service->notes); ?></p>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-calendar-check text-blue-600 mr-2"></i>
                    Thống kê đặt lịch
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-600">Tổng lượt đặt</p>
                                <p class="text-2xl font-bold text-blue-900"><?php echo e($service->service_appointments_count ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-600">Hoàn thành</p>
                                <p class="text-2xl font-bold text-green-900">
                                    <?php echo e($service->serviceAppointments()->where('status', 'completed')->count()); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-600">Đang xử lý</p>
                                <p class="text-2xl font-bold text-yellow-900">
                                    <?php echo e($service->serviceAppointments()->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])->count()); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-1 space-y-6">
            
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Thông tin cơ bản
                </h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Mã dịch vụ</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-50 px-2 py-1 rounded"><?php echo e($service->code); ?></dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Giá dịch vụ</dt>
                        <dd class="mt-1">
                            <?php if($service->price): ?>
                                <span class="text-lg font-bold text-blue-600"><?php echo e(number_format($service->price, 0, ',', '.')); ?>đ</span>
                            <?php else: ?>
                                <span class="text-gray-500">Liên hệ</span>
                            <?php endif; ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Thời gian thực hiện</dt>
                        <dd class="mt-1">
                            <?php if($service->duration_minutes): ?>
                                <span class="text-sm text-gray-900 font-medium"><?php echo e($service->duration_minutes); ?> phút</span>
                            <?php else: ?>
                                <span class="text-gray-500">Chưa xác định</span>
                            <?php endif; ?>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Thứ tự sắp xếp</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <?php echo e($service->sort_order); ?>

                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ngày tạo</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo e($service->created_at->format('d/m/Y H:i')); ?></dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Cập nhật lần cuối</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo e($service->updated_at->format('d/m/Y H:i')); ?></dd>
                    </div>
                </dl>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/services/show.blade.php ENDPATH**/ ?>