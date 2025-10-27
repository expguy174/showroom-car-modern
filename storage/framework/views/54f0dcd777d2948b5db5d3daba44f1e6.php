<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 16%;">Ngân hàng / Gói vay</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Lãi suất</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 12%;">Phí xử lý</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 13%;">Trả trước TT</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 13%;">Kỳ hạn</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 14%;">Hạn mức vay</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 12%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $financeOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900"><?php echo e($option->bank_name); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($option->name); ?></div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-blue-600"><?php echo e(number_format($option->interest_rate, 2)); ?>%</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <?php if($option->processing_fee > 0): ?>
                        <div class="text-sm text-gray-900"><?php echo e(number_format($option->processing_fee, 0, ',', '.')); ?>đ</div>
                    <?php else: ?>
                        <span class="text-sm text-green-600">Miễn phí</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">Tối thiểu <?php echo e(number_format($option->min_down_payment, 0)); ?>%</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?php echo e($option->min_tenure); ?> - <?php echo e($option->max_tenure); ?> tháng</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        <?php
                            $minAmount = $option->min_loan_amount / 1000000; // triệu
                            $maxAmount = $option->max_loan_amount / 1000000; // triệu
                            
                            // Format min amount
                            if ($minAmount >= 1000) {
                                $minInBillion = $minAmount / 1000;
                                $minDisplay = ($minInBillion == floor($minInBillion)) 
                                    ? number_format($minInBillion, 0) . ' tỷ'
                                    : number_format($minInBillion, 1) . ' tỷ';
                            } else {
                                $minDisplay = number_format($minAmount, 0) . 'tr';
                            }
                            
                            // Format max amount
                            if ($maxAmount >= 1000) {
                                $maxInBillion = $maxAmount / 1000;
                                $maxDisplay = ($maxInBillion == floor($maxInBillion)) 
                                    ? number_format($maxInBillion, 0) . ' tỷ'
                                    : number_format($maxInBillion, 1) . ' tỷ';
                            } else {
                                $maxDisplay = number_format($maxAmount, 0) . 'tr';
                            }
                        ?>
                        <?php echo e($minDisplay); ?> - <?php echo e($maxDisplay); ?>

                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <?php if (isset($component)) { $__componentOriginal34999d704fb4480704a28cb78ec57cce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34999d704fb4480704a28cb78ec57cce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-toggle','data' => ['itemId' => $option->id,'currentStatus' => $option->is_active,'entityType' => 'finance']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($option->id),'current-status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($option->is_active),'entity-type' => 'finance']); ?>
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
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <?php if (isset($component)) { $__componentOriginal2cf8d150d764feb90655ba7ed73d9171 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cf8d150d764feb90655ba7ed73d9171 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.table-actions','data' => ['item' => $option,'editRoute' => 'admin.finance-options.edit','deleteRoute' => 'admin.finance-options.destroy','hasToggle' => true,'deleteData' => [
                            'finance-bank' => $option->bank_name,
                            'finance-program' => $option->name
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.table-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($option),'edit-route' => 'admin.finance-options.edit','delete-route' => 'admin.finance-options.destroy','has-toggle' => true,'delete-data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            'finance-bank' => $option->bank_name,
                            'finance-program' => $option->name
                        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2cf8d150d764feb90655ba7ed73d9171)): ?>
<?php $attributes = $__attributesOriginal2cf8d150d764feb90655ba7ed73d9171; ?>
<?php unset($__attributesOriginal2cf8d150d764feb90655ba7ed73d9171); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2cf8d150d764feb90655ba7ed73d9171)): ?>
<?php $component = $__componentOriginal2cf8d150d764feb90655ba7ed73d9171; ?>
<?php unset($__componentOriginal2cf8d150d764feb90655ba7ed73d9171); ?>
<?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="px-4 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-calculator text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">Không có gói trả góp nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    
    <?php if($financeOptions->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $financeOptions]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($financeOptions)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/finance-options/partials/table.blade.php ENDPATH**/ ?>