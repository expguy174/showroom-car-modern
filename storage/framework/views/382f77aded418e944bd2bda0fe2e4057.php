<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 30%;">Phụ kiện</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Danh mục</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Giá bán</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $accessories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accessory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50 transition-colors">
                
                <?php if(config('app.debug')): ?>
                    <!-- DEBUG: Accessory ID <?php echo e($accessory->id ?? 'NULL'); ?>, Gallery: <?php echo e(json_encode($accessory->gallery ?? 'NULL')); ?> -->
                <?php endif; ?>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <?php
                                $resolveImage = function($value, $fallbackText = 'No Image'){
                                    $val = trim((string) $value);
                                    if ($val === '') {
                                        return 'https://via.placeholder.com/400x400/111827/ffffff?text=' . urlencode($fallbackText);
                                    }
                                    if (filter_var($val, FILTER_VALIDATE_URL)) {
                                        return $val;
                                    }
                                    return 'https://placehold.co/400x400/111827/ffffff?text=' . urlencode($val);
                                };
                                
                                // Get gallery images from JSON field - SAFE VERSION
                                $galleryRaw = $accessory->gallery ?? null;
                                $gallery = [];
                                
                                // Safe gallery processing
                                try {
                                    if (is_array($galleryRaw)) {
                                        $gallery = $galleryRaw;
                                    } elseif (is_string($galleryRaw) && !empty($galleryRaw)) {
                                        $decoded = json_decode($galleryRaw, true);
                                        $gallery = is_array($decoded) ? $decoded : [];
                                    }
                                } catch (Exception $e) {
                                    $gallery = [];
                                }
                                
                                // Get primary image URL from gallery - EXTRA SAFE
                                $primaryImageUrl = '';
                                if (is_array($gallery) && !empty($gallery)) {
                                    // First, try to find primary image
                                    $primaryImage = null;
                                    foreach ($gallery as $img) {
                                        if (is_array($img) && isset($img['is_primary']) && $img['is_primary']) {
                                            $primaryImage = $img;
                                            break;
                                        }
                                    }
                                    
                                    // If no primary, use first image
                                    if (!$primaryImage && isset($gallery[0])) {
                                        $primaryImage = $gallery[0];
                                    }
                                    
                                    // Extract URL from image
                                    if ($primaryImage) {
                                        if (is_array($primaryImage)) {
                                            // New format: array with url/title/etc
                                            $primaryImageUrl = $primaryImage['url'] ?? $primaryImage['file'] ?? '';
                                        } elseif (is_string($primaryImage)) {
                                            // Old format: direct URL string
                                            $primaryImageUrl = $primaryImage;
                                        }
                                    }
                                }
                                
                                $mainImage = $resolveImage(
                                    $primaryImageUrl,
                                    $accessory->name ?? 'No Image'
                                );
                            ?>
                            
                            <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" 
                                 src="<?php echo e($mainImage); ?>" 
                                 alt="<?php echo e($accessory->name); ?>"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="h-12 w-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm" style="display: none;">
                                <?php echo e(strtoupper(substr($accessory->name, 0, 2))); ?>

                            </div>
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate"><?php echo e($accessory->name); ?></div>
                            <div class="text-sm text-gray-500 truncate">
                                SKU: <?php echo e($accessory->sku); ?>

                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900"><?php echo e($accessory->vietnamese_category); ?></div>
                    <?php if($accessory->vietnamese_subcategory): ?>
                        <div class="text-sm text-gray-500"><?php echo e($accessory->vietnamese_subcategory); ?></div>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        <div class="font-semibold text-lg text-blue-600">
                            <?php echo e(number_format($accessory->current_price)); ?>đ
                        </div>
                        <?php if($accessory->is_on_sale && $accessory->current_price < $accessory->base_price): ?>
                            <div class="text-xs text-gray-500 line-through">
                                <?php echo e(number_format($accessory->base_price)); ?>đ
                            </div>
                            <div class="text-xs text-orange-600 font-medium">
                                <i class="fas fa-tags mr-1"></i>
                                Giảm <?php echo e(number_format((($accessory->base_price - $accessory->current_price) / $accessory->base_price) * 100, 1)); ?>%
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-toggle','data' => ['itemId' => $accessory->id,'currentStatus' => $accessory->is_active,'entityType' => 'accessory']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accessory->id),'current-status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accessory->is_active),'entity-type' => 'accessory']); ?>
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
                        
                        <!-- Additional Badges -->
                        <?php if($accessory->is_featured): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-star mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Nổi bật</span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if($accessory->is_on_sale): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-orange-100 text-orange-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-percentage mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Khuyến mãi</span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if($accessory->is_new_arrival): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-blue-100 text-blue-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-plus-circle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Mới</span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if($accessory->is_bestseller): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-purple-100 text-purple-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-fire mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Bán chạy</span>
                            </span>
                        <?php endif; ?>

                        <?php if($accessory->stock_status === 'out_of_stock'): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-red-100 text-red-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-exclamation-triangle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Hết hàng</span>
                            </span>
                        <?php elseif($accessory->stock_status === 'low_stock'): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-exclamation-circle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Sắp hết</span>
                            </span>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <?php if (isset($component)) { $__componentOriginal2cf8d150d764feb90655ba7ed73d9171 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cf8d150d764feb90655ba7ed73d9171 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.table-actions','data' => ['item' => $accessory,'showRoute' => 'admin.accessories.show','editRoute' => 'admin.accessories.edit','deleteRoute' => 'admin.accessories.destroy','hasToggle' => true,'deleteData' => [
                            'category' => $accessory->category ?? '',
                            'stock-quantity' => $accessory->stock_quantity
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.table-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accessory),'show-route' => 'admin.accessories.show','edit-route' => 'admin.accessories.edit','delete-route' => 'admin.accessories.destroy','has-toggle' => true,'delete-data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            'category' => $accessory->category ?? '',
                            'stock-quantity' => $accessory->stock_quantity
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
                        <i class="fas fa-cogs text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Không tìm thấy phụ kiện nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    
    <?php if($accessories->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $accessories]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($accessories)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/accessories/partials/table.blade.php ENDPATH**/ ?>