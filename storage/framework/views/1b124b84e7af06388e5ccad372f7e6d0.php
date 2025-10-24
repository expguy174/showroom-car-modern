<?php $__env->startSection('title', 'Quản lý showroom'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-building text-blue-600 mr-3"></i>
                    Quản lý showroom
                </h1>
                <p class="text-sm text-gray-600 mt-1">Danh sách tất cả showroom và đại lý</p>
            </div>
            <a href="<?php echo e(route('admin.showrooms.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Thêm showroom
            </a>
        </div>
    </div>

    
    <div class="p-6 border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-blue-100 text-sm">Tổng showroom</p>
                        <p class="text-2xl font-semibold"><?php echo e($totalShowrooms ?? 0); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-green-100 text-sm">Đang hoạt động</p>
                        <p class="text-2xl font-semibold"><?php echo e($activeShowrooms ?? 0); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-map-marker-alt text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-yellow-100 text-sm">Tỉnh/Thành phố</p>
                        <p class="text-2xl font-semibold"><?php echo e($totalCities ?? 0); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-purple-100 text-sm">Lịch hẹn tháng này</p>
                        <p class="text-2xl font-semibold"><?php echo e($monthlyAppointments ?? 0); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                       placeholder="Tìm kiếm theo tên, địa chỉ, số điện thoại..." 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <select name="city" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tất cả tỉnh/thành</option>
                <?php $__currentLoopData = $cities ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($city); ?>" <?php echo e(request('city') == $city ? 'selected' : ''); ?>><?php echo e($city); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Tất cả trạng thái</option>
                <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Hoạt động</option>
                <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Tạm dừng</option>
            </select>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-search mr-2"></i>
                Tìm kiếm
            </button>
            
            <a href="<?php echo e(route('admin.showrooms.index')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i class="fas fa-redo mr-2"></i>
                Đặt lại
            </a>
        </form>
    </div>

    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Showroom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liên hệ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giờ làm việc</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $showrooms ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $showroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        #<?php echo e($showroom->id); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                <?php echo e(strtoupper(substr($showroom->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($showroom->name); ?></div>
                                <?php if($showroom->code): ?>
                                    <div class="text-sm text-gray-500"><?php echo e($showroom->code); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <div><?php echo e($showroom->address); ?></div>
                            <div class="text-gray-500"><?php echo e($showroom->district); ?>, <?php echo e($showroom->city); ?></div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div>
                            <?php if($showroom->phone): ?>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    <?php echo e($showroom->phone); ?>

                                </div>
                            <?php endif; ?>
                            <?php if($showroom->email): ?>
                                <div class="flex items-center text-gray-500">
                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                    <?php echo e($showroom->email); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php if($showroom->opening_hours): ?>
                            <?php echo e($showroom->opening_hours); ?>

                        <?php else: ?>
                            <span class="text-gray-500">Chưa cập nhật</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($showroom->is_active): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Hoạt động
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Tạm dừng
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('admin.showrooms.show', $showroom)); ?>" 
                               class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                               title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('admin.showrooms.edit', $showroom)); ?>" 
                               class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50" 
                               title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <?php if($showroom->latitude && $showroom->longitude): ?>
                                <a href="https://maps.google.com/?q=<?php echo e($showroom->latitude); ?>,<?php echo e($showroom->longitude); ?>" 
                                   target="_blank"
                                   class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50" 
                                   title="Xem trên bản đồ">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                            <?php endif; ?>
                            <form action="<?php echo e(route('admin.showrooms.destroy', $showroom)); ?>" method="POST" class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa showroom này? Thao tác này không thể hoàn tác.')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50" 
                                        title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-building text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có showroom nào</h3>
                            <p class="text-gray-500 mb-4">Hệ thống chưa có showroom nào được tạo.</p>
                            <a href="<?php echo e(route('admin.showrooms.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm showroom đầu tiên
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if(isset($showrooms) && $showrooms->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php echo e($showrooms->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/showrooms/index.blade.php ENDPATH**/ ?>