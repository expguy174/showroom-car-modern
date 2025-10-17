<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 30%;">Phiên bản xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Dòng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Giá bán</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $carVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <?php if($variant->images->where('is_main', true)->first()): ?>
                                <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" 
                                     src="<?php echo e($variant->images->where('is_main', true)->first()->image_url); ?>" 
                                     alt="<?php echo e($variant->name); ?>">
                            <?php else: ?>
                                <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                    <?php echo e(strtoupper(substr($variant->name, 0, 2))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate"><?php echo e($variant->name); ?></div>
                            <div class="text-sm text-gray-500 truncate">
                                <?php
                                    $engineType = $variant->specifications->where('spec_name', 'engine_type')->first();
                                    $fuelType = $variant->specifications->where('spec_name', 'fuel_type')->first();
                                    $displacement = $variant->specifications->where('spec_name', 'engine_displacement')->first();
                                    
                                    $engineInfo = [];
                                    if ($displacement && $displacement->spec_value) {
                                        $cc = (float) $displacement->spec_value;
                                        if ($cc > 0) {
                                            $engineInfo[] = $cc >= 1000 ? number_format($cc/1000, 1) . 'L' : $cc . 'cc';
                                        }
                                    }
                                    if ($engineType && $engineType->spec_value) {
                                        $engineInfo[] = $engineType->spec_value;
                                    }
                                    if ($fuelType && $fuelType->spec_value) {
                                        $fuelTypes = [
                                            'gasoline' => 'Xăng',
                                            'diesel' => 'Dầu',
                                            'hybrid' => 'Hybrid',
                                            'electric' => 'Điện'
                                        ];
                                        $engineInfo[] = $fuelTypes[$fuelType->spec_value] ?? ucfirst($fuelType->spec_value);
                                    }
                                ?>
                                <?php echo e(!empty($engineInfo) ? implode(' ', $engineInfo) : 'Chưa cập nhật'); ?>

                            </div>
                            <?php if($variant->sku): ?>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-barcode text-gray-400 mr-1"></i>
                                        <?php echo e($variant->sku); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <img class="h-8 w-8 rounded object-contain bg-white p-1 border border-gray-200" 
                                 src="<?php echo e($variant->carModel->carBrand->logo_url); ?>" 
                                 alt="<?php echo e($variant->carModel->carBrand->name); ?>">
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate"><?php echo e($variant->carModel->name); ?></div>
                            <div class="text-sm text-gray-500 truncate"><?php echo e($variant->carModel->carBrand->name); ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        <div class="font-semibold text-lg text-blue-600">
                            <?php echo e(number_format($variant->current_price ?: $variant->base_price)); ?>đ
                        </div>
                        <?php if($variant->is_on_sale && $variant->current_price < $variant->base_price): ?>
                            <div class="text-xs text-gray-500 line-through">
                                <?php echo e(number_format($variant->base_price)); ?>đ
                            </div>
                            <div class="text-xs text-orange-600 font-medium">
                                <i class="fas fa-tags mr-1"></i>
                                Giảm <?php echo e(number_format((($variant->base_price - $variant->current_price) / $variant->base_price) * 100, 1)); ?>%
                            </div>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col items-start gap-1 w-full">
                        <!-- Main Status - Using StatusToggle Component -->
                        <div class="w-full">
                            <?php if (isset($component)) { $__componentOriginal34999d704fb4480704a28cb78ec57cce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34999d704fb4480704a28cb78ec57cce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-toggle','data' => ['itemId' => $variant->id,'currentStatus' => $variant->is_active,'entityType' => 'carvariant']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($variant->id),'current-status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($variant->is_active),'entity-type' => 'carvariant']); ?>
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
                        
                        <!-- Additional Badges - Uniform height and spacing -->
                        <?php if($variant->is_featured): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-star mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Nổi bật</span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if($variant->is_on_sale): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-orange-100 text-orange-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-percentage mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Khuyến mãi</span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if($variant->is_new_arrival): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-blue-100 text-blue-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-plus-circle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Mới</span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if($variant->is_bestseller): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-purple-100 text-purple-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-fire mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Bán chạy</span>
                            </span>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <?php if (isset($component)) { $__componentOriginal2cf8d150d764feb90655ba7ed73d9171 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cf8d150d764feb90655ba7ed73d9171 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.table-actions','data' => ['item' => $variant,'showRoute' => 'admin.carvariants.show','editRoute' => 'admin.carvariants.edit','deleteRoute' => 'admin.carvariants.destroy','hasToggle' => true,'deleteData' => [
                            'model-name' => $variant->carModel->name ?? '',
                            'colors-count' => $variant->colors->count(),
                            'images-count' => $variant->images->count()
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.table-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($variant),'show-route' => 'admin.carvariants.show','edit-route' => 'admin.carvariants.edit','delete-route' => 'admin.carvariants.destroy','has-toggle' => true,'delete-data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            'model-name' => $variant->carModel->name ?? '',
                            'colors-count' => $variant->colors->count(),
                            'images-count' => $variant->images->count()
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
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-cubes text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Không tìm thấy phiên bản xe nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    
    <?php if($carVariants->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $carVariants]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($carVariants)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/carvariants/partials/table.blade.php ENDPATH**/ ?>