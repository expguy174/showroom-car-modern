<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] whitespace-nowrap">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[20%] whitespace-nowrap">Xe & Dịch vụ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%] whitespace-nowrap">Thời gian hẹn</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%] whitespace-nowrap">Showroom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[10%] whitespace-nowrap">Số tiền</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[15%] whitespace-nowrap">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $appointments ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            
                            <div class="flex-shrink-0">
                                <?php if($appointment->user->userProfile && $appointment->user->userProfile->avatar_path): ?>
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                         src="<?php echo e(Storage::url($appointment->user->userProfile->avatar_path)); ?>" 
                                         alt="<?php echo e($appointment->user->userProfile->name ?? $appointment->user->email); ?>">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            <?php echo e(strtoupper(mb_substr($appointment->user->userProfile->name ?? $appointment->user->email, 0, 2, 'UTF-8'))); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    <?php echo e($appointment->user->userProfile->name ?? 'N/A'); ?>

                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    <?php echo e($appointment->user->email ?? '-'); ?>

                                </div>
                                <?php if($appointment->user->userProfile && $appointment->user->userProfile->phone): ?>
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-phone text-gray-400 mr-1"></i>
                                        <?php echo e($appointment->user->userProfile->phone); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">
                            <?php if($appointment->vehicle_registration): ?>
                            <div class="font-medium text-gray-900">
                                <i class="fas fa-car text-xs mr-1"></i><?php echo e($appointment->vehicle_registration); ?>

                            </div>
                            <?php endif; ?>
                            <div class="text-gray-700 mt-1 text-xs"><?php echo e($appointment->service->name ?? 'N/A'); ?></div>
                            <?php if($appointment->current_mileage): ?>
                                <div class="text-gray-500 text-xs mt-1">
                                    <i class="fas fa-tachometer-alt text-xs mr-1"></i><?php echo e(number_format($appointment->current_mileage)); ?> km
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="text-sm"><?php echo e($appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : '-'); ?></div>
                        <div class="text-gray-500 text-xs"><?php echo e($appointment->appointment_time ?? '-'); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo e($appointment->showroom->name ?? 'N/A'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?php echo e($appointment->estimated_cost ? number_format($appointment->estimated_cost, 0, ',', '.') . 'đ' : '-'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php switch($appointment->status):
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
                            <?php case ('in_progress'): ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-cog mr-1"></i>
                                    Đang thực hiện
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
                                    <?php echo e(ucfirst($appointment->status)); ?>

                                </span>
                        <?php endswitch; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center justify-center gap-2">
                            
                            <a href="<?php echo e(route('admin.service-appointments.show', $appointment)); ?>" 
                               class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                               title="Xem chi tiết">
                                <i class="fas fa-eye w-4 h-4"></i>
                            </a>
                            
                            
                            <?php if(in_array($appointment->status, ['scheduled', 'rescheduled'])): ?>
                                <button type="button"
                                        class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 confirm-btn"
                                        data-appointment-id="<?php echo e($appointment->id); ?>"
                                        data-customer-name="<?php echo e($appointment->user->userProfile->name ?? 'Khách hàng'); ?>"
                                        title="Xác nhận lịch hẹn">
                                    <i class="fas fa-check-circle w-4 h-4"></i>
                                </button>
                            <?php endif; ?>
                            
                            
                            <?php if($appointment->status == 'confirmed'): ?>
                                <button type="button"
                                        class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50 start-service-btn"
                                        data-appointment-id="<?php echo e($appointment->id); ?>"
                                        title="Bắt đầu thực hiện">
                                    <i class="fas fa-play-circle w-4 h-4"></i>
                                </button>
                            <?php endif; ?>
                            
                            
                            <?php if($appointment->status == 'in_progress'): ?>
                                <button type="button"
                                        class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 complete-service-btn"
                                        data-appointment-id="<?php echo e($appointment->id); ?>"
                                        title="Hoàn thành">
                                    <i class="fas fa-check-double w-4 h-4"></i>
                                </button>
                            <?php endif; ?>
                            
                            
                            <?php if(in_array($appointment->status, ['scheduled', 'confirmed'])): ?>
                                <button type="button"
                                        class="text-orange-600 hover:text-orange-900 transition-colors p-1 rounded hover:bg-orange-50 cancel-btn"
                                        data-appointment-id="<?php echo e($appointment->id); ?>"
                                        data-customer-name="<?php echo e($appointment->user->userProfile->name ?? 'Khách hàng'); ?>"
                                        data-service="<?php echo e($appointment->service->name ?? 'N/A'); ?>"
                                        data-date="<?php echo e($appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : ''); ?>"
                                        title="Hủy lịch hẹn">
                                    <i class="fas fa-ban w-4 h-4"></i>
                                </button>
                            <?php endif; ?>
                            
                            
                            <button type="button"
                                    class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50 delete-btn"
                                    data-appointment-id="<?php echo e($appointment->id); ?>"
                                    data-customer-name="<?php echo e($appointment->user->userProfile->name ?? 'Khách hàng'); ?>"
                                    data-service="<?php echo e($appointment->service->name ?? 'N/A'); ?>"
                                    data-date="<?php echo e($appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : ''); ?>"
                                    title="Xóa lịch hẹn">
                                <i class="fas fa-trash w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có lịch hẹn nào</h3>
                            <p class="text-gray-500 mb-4">Không tìm thấy lịch hẹn nào phù hợp với bộ lọc.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if(isset($appointments) && $appointments->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $appointments]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($appointments)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/service-appointments/partials/table.blade.php ENDPATH**/ ?>