<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khuyến mãi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sử dụng</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($promotion->name); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($promotion->code); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php switch($promotion->type):
                                case ('percentage'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-percent mr-1"></i>
                                        Giảm theo %
                                    </span>
                                    <?php break; ?>
                                <?php case ('fixed_amount'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-dollar-sign mr-1"></i>
                                        Giảm cố định
                                    </span>
                                    <?php break; ?>
                                <?php case ('free_shipping'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-shipping-fast mr-1"></i>
                                        Miễn phí ship
                                    </span>
                                    <?php break; ?>
                                <?php case ('brand_specific'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-tags mr-1"></i>
                                        Theo thương hiệu
                                    </span>
                                    <?php break; ?>
                                <?php default: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Khác
                                    </span>
                            <?php endswitch; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php if($promotion->type == 'percentage'): ?>
                                <?php echo e($promotion->discount_value); ?>%
                            <?php elseif($promotion->type == 'fixed_amount'): ?>
                                <?php echo e(number_format($promotion->discount_value, 0, ',', '.')); ?>đ
                            <?php elseif($promotion->type == 'free_shipping'): ?>
                                <span class="text-green-600 font-medium">Miễn phí ship</span>
                            <?php elseif($promotion->type == 'brand_specific'): ?>
                                <?php if($promotion->discount_value > 0): ?>
                                    <?php echo e($promotion->discount_value); ?>%
                                <?php else: ?>
                                    <span class="text-orange-600 font-medium">Theo thương hiệu</span>
                                <?php endif; ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div><?php echo e($promotion->start_date ? $promotion->start_date->format('d/m/Y') : '-'); ?></div>
                            <div class="text-gray-500"><?php echo e($promotion->end_date ? $promotion->end_date->format('d/m/Y') : 'Không giới hạn'); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            <?php echo e($promotion->usage_count ?? 0); ?> / <?php echo e($promotion->usage_limit ?? '∞'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center gap-1 w-full">
                                <!-- Main Status - Using StatusToggle Component -->
                                <div class="w-full flex justify-center">
                                    <?php if (isset($component)) { $__componentOriginal34999d704fb4480704a28cb78ec57cce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34999d704fb4480704a28cb78ec57cce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-toggle','data' => ['itemId' => $promotion->id,'currentStatus' => $promotion->is_active,'entityType' => 'promotion']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($promotion->id),'current-status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($promotion->is_active),'entity-type' => 'promotion']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal34999d704fb4480704a28cb78ec57cce)): ?>
<?php $attributes = $__attributesOriginal34999d704fb4480704a28cb78ec57cce; ?>
<?php unset($__attributesOriginal34999d704fb4480704a28cb78ec57cce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal34999d704fb4480704a28cb78ec57cce)): ?>
<?php $component = $__componentOriginal34999d704fb4480704a28cb78ec57cce; ?>
<?php unset($__componentOriginal34999d704fb4480704a28cb78ec57cce); ?>
<?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex items-center justify-center space-x-1">
                                
                                <button type="button" 
                                        class="text-<?php echo e($promotion->is_active ? 'orange' : 'green'); ?>-600 hover:text-<?php echo e($promotion->is_active ? 'orange' : 'green'); ?>-900 status-toggle w-4 h-4 flex items-center justify-center"
                                        data-promotion-id="<?php echo e($promotion->id); ?>"
                                        data-status="<?php echo e($promotion->is_active ? 'false' : 'true'); ?>"
                                        title="<?php echo e($promotion->is_active ? 'Tạm dừng' : 'Kích hoạt'); ?>">
                                    <i class="fas fa-<?php echo e($promotion->is_active ? 'pause' : 'play'); ?> w-4 h-4"></i>
                                </button>

                                
                                <a href="<?php echo e(route('admin.promotions.show', $promotion->id)); ?>" 
                                   class="text-gray-600 hover:text-gray-900 w-4 h-4 flex items-center justify-center"
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye w-4 h-4"></i>
                                </a>

                                
                                <a href="<?php echo e(route('admin.promotions.edit', $promotion)); ?>" 
                                   class="text-blue-600 hover:text-blue-900 w-4 h-4 flex items-center justify-center"
                                   title="Chỉnh sửa">
                                    <i class="fas fa-edit w-4 h-4"></i>
                                </a>

                                
                                <button type="button" 
                                        class="text-red-600 hover:text-red-900 w-4 h-4 flex items-center justify-center delete-btn"
                                        data-promotion-id="<?php echo e($promotion->id); ?>"
                                        data-promotion-name="<?php echo e($promotion->name); ?>"
                                        data-promotion-code="<?php echo e($promotion->code); ?>"
                                        data-promotion-type="<?php echo e($promotion->type); ?>"
                                        data-promotion-value="<?php echo e($promotion->discount_value); ?>"
                                        data-usage-count="<?php echo e($promotion->usage_count ?? 0); ?>"
                                        title="Xóa">
                                    <i class="fas fa-trash w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-tags text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg font-medium">Không có khuyến mãi nào</p>
                                <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($promotions->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $promotions]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($promotions)]); ?>
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
</div>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/promotions/partials/table.blade.php ENDPATH**/ ?>