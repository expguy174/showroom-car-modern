<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] whitespace-nowrap">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] whitespace-nowrap">Xe lái thử</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Thời gian hẹn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Showroom</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $testDrives ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testDrive): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50" data-test-drive-id="<?php echo e($testDrive->id); ?>">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            
                            <div class="flex-shrink-0">
                                <?php if($testDrive->user && $testDrive->user->userProfile && $testDrive->user->userProfile->avatar_path): ?>
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                         src="<?php echo e(Storage::url($testDrive->user->userProfile->avatar_path)); ?>" 
                                         alt="<?php echo e($testDrive->user->userProfile->name ?? $testDrive->user->email); ?>">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            <?php echo e(strtoupper(mb_substr($testDrive->user && $testDrive->user->userProfile ? $testDrive->user->userProfile->name : ($testDrive->user ? $testDrive->user->email : $testDrive->customer_name), 0, 2, 'UTF-8'))); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    <?php echo e($testDrive->user && $testDrive->user->userProfile ? $testDrive->user->userProfile->name : ($testDrive->user ? $testDrive->user->email : $testDrive->customer_name)); ?>

                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    <?php echo e($testDrive->user ? $testDrive->user->email : $testDrive->customer_email); ?>

                                </div>
                                <?php if($testDrive->user && $testDrive->user->userProfile && $testDrive->user->userProfile->phone): ?>
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i>
                                        <?php echo e($testDrive->user->userProfile->phone); ?>

                                    </div>
                                <?php elseif($testDrive->customer_phone): ?>
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i>
                                        <?php echo e($testDrive->customer_phone); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            <div class="font-medium text-gray-900"><?php echo e($testDrive->carVariant->name ?? 'N/A'); ?></div>
                            <div class="text-gray-700 mt-1 text-xs">
                                <?php echo e(optional($testDrive->carVariant->carModel->carBrand)->name); ?> 
                                <?php echo e(optional($testDrive->carVariant->carModel)->name); ?>

                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="text-sm"><?php echo e($testDrive->preferred_date ? $testDrive->preferred_date->format('d/m/Y') : '-'); ?></div>
                        <div class="text-gray-500 text-xs"><?php echo e($testDrive->preferred_time ? substr($testDrive->preferred_time, 0, 5) : '-'); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo e($testDrive->showroom->name ?? 'N/A'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center status-cell">
                        <?php switch($testDrive->status):
                            case ('scheduled'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Đã đặt lịch
                                </span>
                                <?php break; ?>
                            <?php case ('confirmed'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Đã xác nhận
                                </span>
                                <?php break; ?>
                            <?php case ('completed'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-flag-checkered mr-1"></i>
                                    Hoàn thành
                                </span>
                                <?php break; ?>
                            <?php case ('cancelled'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Đã hủy
                                </span>
                                <?php break; ?>
                            <?php default: ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-question mr-1"></i>
                                    <?php echo e(ucfirst($testDrive->status)); ?>

                                </span>
                        <?php endswitch; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium actions-cell">
                        <div class="flex items-center justify-center gap-2">
                            
                            <a href="<?php echo e(route('admin.test-drives.show', $testDrive)); ?>" 
                               class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                               title="Xem chi tiết">
                                <i class="fas fa-eye w-4 h-4"></i>
                            </a>
                            
                            
                            <?php if($testDrive->status === 'scheduled'): ?>
                                <button type="button"
                                        class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 confirm-test-drive-btn"
                                        data-test-drive-id="<?php echo e($testDrive->id); ?>"
                                        data-customer-name="<?php echo e($testDrive->customer_name); ?>"
                                        title="Xác nhận">
                                    <i class="fas fa-check-circle w-4 h-4"></i>
                                </button>
                            <?php endif; ?>
                            
                            
                            <?php if($testDrive->status === 'confirmed'): ?>
                                <button type="button"
                                        class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50 complete-test-drive-btn"
                                        data-test-drive-id="<?php echo e($testDrive->id); ?>"
                                        title="Hoàn thành">
                                    <i class="fas fa-check-double w-4 h-4"></i>
                                </button>
                            <?php endif; ?>
                            
                            
                            <?php if(in_array($testDrive->status, ['scheduled', 'confirmed'])): ?>
                                <button type="button"
                                        class="text-orange-600 hover:text-orange-900 transition-colors p-1 rounded hover:bg-orange-50 cancel-test-drive-btn"
                                        data-test-drive-id="<?php echo e($testDrive->id); ?>"
                                        data-customer-name="<?php echo e($testDrive->customer_name); ?>"
                                        data-car="<?php echo e($testDrive->car_full_name); ?>"
                                        data-date="<?php echo e($testDrive->preferred_date ? $testDrive->preferred_date->format('d/m/Y') : ''); ?>"
                                        title="Hủy lịch hẹn">
                                    <i class="fas fa-ban w-4 h-4"></i>
                                </button>
                            <?php endif; ?>
                            
                            
                            <button type="button"
                                    class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50 delete-test-drive-btn"
                                    data-test-drive-id="<?php echo e($testDrive->id); ?>"
                                    data-customer-name="<?php echo e($testDrive->customer_name); ?>"
                                    data-car="<?php echo e($testDrive->car_full_name); ?>"
                                    data-date="<?php echo e($testDrive->preferred_date ? $testDrive->preferred_date->format('d/m/Y') : ''); ?>"
                                    title="Xóa lịch lái thử">
                                <i class="fas fa-trash w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-car text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có lịch lái thử nào</h3>
                            <p class="text-gray-500 mb-4">Không tìm thấy lịch lái thử nào phù hợp với bộ lọc.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if(isset($testDrives) && $testDrives->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $testDrives]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($testDrives)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5)): ?>
<?php $attributes = $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5; ?>
<?php unset($__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f9437379ffbb940ff05ba93353d3cd5)): ?>
<?php $component = $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5; ?>
<?php unset($__componentOriginal1f9437379ffbb940ff05ba93353d3cd5); ?>
<?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/test-drives/partials/table.blade.php ENDPATH**/ ?>