<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">Dịch vụ</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">Danh mục</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Giá</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Thời gian</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $services ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>
                        <div class="text-sm font-medium text-gray-900"><?php echo e($service->name); ?></div>
                        <?php if($service->code): ?>
                        <div class="mt-1">
                            <span class="text-xs text-gray-500 font-mono bg-gray-100 px-2 py-0.5 rounded"><?php echo e($service->code); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
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
                                    <i class="fas fa-search mr-1"></i>Chẩn đoán
                                </span>
                                <?php break; ?>
                            <?php case ('cosmetic'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                    <i class="fas fa-paint-brush mr-1"></i>Làm đẹp
                                </span>
                                <?php break; ?>
                            <?php case ('emergency'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Khẩn cấp
                                </span>
                                <?php break; ?>
                            <?php default: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <?php echo e(ucfirst($service->category)); ?>

                                </span>
                        <?php endswitch; ?>
                    </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm">
                        <?php if($service->price): ?>
                            <div class="text-gray-900 font-medium"><?php echo e(number_format($service->price, 0, ',', '.')); ?>đ</div>
                        <?php else: ?>
                            <span class="text-gray-500">Liên hệ</span>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm">
                        <?php if($service->duration_minutes): ?>
                            <div class="text-gray-900"><?php echo e($service->duration_minutes); ?> phút</div>
                        <?php else: ?>
                            <span class="text-gray-500">-</span>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex flex-col items-center gap-1">
                        <?php if (isset($component)) { $__componentOriginal34999d704fb4480704a28cb78ec57cce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34999d704fb4480704a28cb78ec57cce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-toggle','data' => ['itemId' => $service->id,'currentStatus' => $service->is_active,'entityType' => 'service']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service->id),'current-status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service->is_active),'entity-type' => 'service']); ?>
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
                        <?php if($service->is_featured): ?>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-star mr-1"></i>Nổi bật
                        </span>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <?php if (isset($component)) { $__componentOriginal2cf8d150d764feb90655ba7ed73d9171 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cf8d150d764feb90655ba7ed73d9171 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.table-actions','data' => ['item' => $service,'showRoute' => 'admin.services.show','editRoute' => 'admin.services.edit','deleteRoute' => 'admin.services.destroy','hasToggle' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.table-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($service),'show-route' => 'admin.services.show','edit-route' => 'admin.services.edit','delete-route' => 'admin.services.destroy','has-toggle' => true]); ?>
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
                <td colspan="6" class="px-4 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-cogs text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">Không có dịch vụ nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    
    <?php if($services->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $services]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($services)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/services/partials/table.blade.php ENDPATH**/ ?>