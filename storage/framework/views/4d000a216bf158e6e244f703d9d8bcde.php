<?php 
    // Use model fields: base_price (original) vs current_price (current)
    $originalPriceBeforeDiscount = (float) ($item->item->base_price ?? 0);
    $currentPrice = (float) ($item->item->current_price ?? 0);
    $hasDiscount = (bool) ($item->item->has_discount ?? ($currentPrice > 0 && $originalPriceBeforeDiscount > $currentPrice));
    $discountPercentage = $hasDiscount && $originalPriceBeforeDiscount > 0
        ? (int) round((($originalPriceBeforeDiscount - $currentPrice) / $originalPriceBeforeDiscount) * 100)
        : 0;
    $discountAmount = $hasDiscount ? max(0, $originalPriceBeforeDiscount - $currentPrice) : 0;
    
    // Get color price adjustment
    $colorPriceAdjustment = 0;
    if ($item->item_type === 'car_variant' && $item->color && method_exists($item->item, 'getPriceWithColorAdjustment')) {
        $priceWithColor = $item->item->getPriceWithColorAdjustment($item->color_id);
        $colorPriceAdjustment = $priceWithColor - $currentPrice;
    }
    
    // Base unit includes color adjustment
    $baseUnit = $currentPrice + $colorPriceAdjustment;
    
    // Add-on fees from session meta
    $meta = session('cart_item_meta.' . $item->id, []);
    $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
    $selectedFeatures = !empty($featIds) ? \App\Models\CarVariantFeature::whereIn('id', $featIds)->get() : collect();
    $addonSum = 0;
    foreach ($selectedFeatures as $sf) { $addonSum += (float) ($sf->price ?? 0); }
    
    // Get stock info for selected color
    $colorStockInfo = \App\Helpers\StockHelper::getCarColorStock($item->item->color_inventory, $item->color_id ?? 0);
    
    // Prepare stock data for all colors (for dynamic update)
    $allColorStocks = [];
    if ($item->item_type === 'car_variant' && isset($item->item->colors)) {
        foreach ($item->item->colors as $color) {
            $stockInfo = \App\Helpers\StockHelper::getCarColorStock($item->item->color_inventory, $color->id);
            $allColorStocks[$color->id] = [
                'available' => $stockInfo['available'],
                'badge' => $stockInfo['badge']
            ];
        }
    }
    
    $displayUnit = $baseUnit + $addonSum;
    $itemTotal = $displayUnit * $item->quantity; 
    $baseTotal = $baseUnit * $item->quantity;
    $addonTotal = $addonSum * $item->quantity;
    $imageUrl = asset('images/default-car.jpg');
    if ($item->item_type === 'car_variant' && $item->item->images->isNotEmpty()) {
        $first = $item->item->images->first();
        if ($first->image_url) {
            $imageUrl = filter_var($first->image_url, FILTER_VALIDATE_URL) ? $first->image_url : asset('storage/'.$first->image_url);
        } elseif ($first->image_path) {
            $imageUrl = asset('storage/'.$first->image_path);
        }
    }
?>

<!-- Desktop Table Layout - HIDDEN on mobile via CSS -->
<tr class="cart-item-desktop desktop-only" data-id="<?php echo e($item->id); ?>" data-item-type="<?php echo e($item->item_type); ?>" data-color-id="<?php echo e($item->color_id); ?>" data-base-unit="<?php echo e((int) $baseUnit); ?>" data-original-price="<?php echo e((int) $originalPriceBeforeDiscount); ?>" data-current-price="<?php echo e((int) $currentPrice); ?>">
    <td class="align-middle text-center" data-label="Ảnh" style="vertical-align: middle;">
        <a href="<?php echo e(route('car-variants.show', $item->item->slug ?? $item->item->id)); ?>" class="inline-block w-24 h-20 rounded-lg overflow-hidden bg-gray-100 border mx-auto">
            <img src="<?php echo e($imageUrl); ?>" class="w-full h-full object-cover" alt="<?php echo e($item->item->name); ?>" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Image';">
        </a>
    </td>
    <td class="align-middle" data-label="Sản phẩm" style="vertical-align: middle;">
        <div class="min-w-0">
            <div class="font-bold text-gray-900 truncate">
                <a href="<?php echo e(route('car-variants.show', $item->item->slug ?? $item->item->id)); ?>" class="hover:underline"><?php echo e($item->item->name); ?></a>
            </div>
            <?php if($item->item->carModel && $item->item->carModel->carBrand): ?>
                <div class="text-sm text-gray-600 truncate"><?php echo e($item->item->carModel->carBrand->name); ?> • <?php echo e($item->item->carModel->name); ?></div>
            <?php endif; ?>
            <?php
                $brief = null;
                if ($item->item->carModel && !empty($item->item->carModel->description)) {
                    $brief = Str::limit(strip_tags($item->item->carModel->description), 90);
                }
            ?>
            <?php if($brief): ?>
                <div class="text-xs text-gray-500 mt-1 line-clamp-2"><?php echo e($brief); ?></div>
            <?php endif; ?>
            <div class="mt-2 text-sm text-gray-700">
                Màu đã chọn: <span class="font-medium selected-color-name"><?php echo e($item->color->color_name ?? 'Chưa chọn'); ?></span>
            </div>
            
            <div class="mt-1" id="stock-badge-<?php echo e($item->id); ?>" <?php if(!$item->color_id): ?> style="display:none" <?php endif; ?>>
                <?php if($item->color_id && $colorStockInfo): ?>
                    <?php if (isset($component)) { $__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock-badge','data' => ['stock' => $colorStockInfo['available'],'type' => 'car_variant','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['stock' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($colorStockInfo['available']),'type' => 'car_variant','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4)): ?>
<?php $attributes = $__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4; ?>
<?php unset($__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4)): ?>
<?php $component = $__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4; ?>
<?php unset($__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4); ?>
<?php endif; ?>
                <?php endif; ?>
            </div>
            <?php if($item->item_type === 'car_variant' && isset($item->item->colors) && $item->item->colors->count() > 0): ?>
            <div class="mt-1 flex items-center gap-2">
                <?php $__currentLoopData = $item->item->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $hexColor = \App\Helpers\ColorHelper::getColorHex($color->color_name); ?>
                    <button type="button" class="color-option w-6 h-6 rounded-full border-2 <?php echo e($item->color_id == $color->id ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-300'); ?>" title="<?php echo e($color->color_name); ?>"
                        style="background-color: <?php echo e($hexColor); ?> !important; border-color: <?php echo e($item->color_id == $color->id ? '#3b82f6' : '#d1d5db'); ?>;"
                        data-bg-hex="<?php echo e($hexColor); ?>" data-color-id="<?php echo e($color->id); ?>" data-color-name="<?php echo e($color->color_name); ?>" data-item-id="<?php echo e($item->id); ?>"
                        data-update-url="<?php echo e(route('user.cart.update', $item->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>"></button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php endif; ?>

            <?php if($item->item_type === 'car_variant' && isset($item->item->featuresRelation) && $item->item->featuresRelation->count() > 0): ?>
            <?php
                $allFeats = $item->item->featuresRelation;
                $standardFeats = $allFeats->filter(function($f){
                    $isIncluded = (bool)($f->is_included ?? false);
                    $isStandard = ($f->availability ?? 'standard') === 'standard';
                    $zeroPrice = (float)($f->price ?? 0) <= 0;
                    return $isIncluded || $isStandard || $zeroPrice;
                });
                $optionalFeats = $allFeats->filter(function($f){
                    $isIncluded = (bool)($f->is_included ?? false);
                    $isStandard = ($f->availability ?? 'standard') === 'standard';
                    $zeroPrice = (float)($f->price ?? 0) <= 0;
                    return !($isIncluded || $isStandard || $zeroPrice);
                });
            ?>
            <div class="mt-2">
                <?php if($standardFeats->count() > 0): ?>
                <div class="mb-2">
                    <div class="text-[11px] text-gray-600 mb-1">Trang bị sẵn</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                        <?php $__currentLoopData = $standardFeats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-flex items-center gap-1 text-[11px] text-gray-600">
                                <i class="fas fa-check text-green-500 text-xs"></i>
                                <span class="truncate whitespace-nowrap overflow-hidden max-w-[10rem]" title="<?php echo e($f->feature_name); ?>"><?php echo e($f->feature_name); ?></span>
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if($optionalFeats->count() > 0): ?>
                <div>
                    <div class="text-[11px] text-gray-600 mb-1">Tuỳ chọn thêm</div>
                    <div class="grid grid-cols-1 gap-1">
                        <?php $__currentLoopData = $optionalFeats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $fee=(float)($f->price ?? 0); ?>
                            <label class="inline-flex items-center gap-2 text-[11px]">
                                <input type="checkbox" class="cart-feature js-opt" data-fee="<?php echo e((int)$fee); ?>" data-id="<?php echo e($item->id); ?>" value="<?php echo e($f->id); ?>" <?php echo e(in_array($f->id, $featIds ?? []) ? 'checked' : ''); ?>>
                                <span class="text-gray-700" title="<?php echo e($f->feature_name); ?>"><?php echo e($f->feature_name); ?></span>
                                <?php if($fee>0): ?><span class="text-indigo-700 font-semibold whitespace-nowrap">+<?php echo e(number_format($fee,0,',','.')); ?>đ</span><?php endif; ?>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </td>
    <td class="align-top hidden md:table-cell mobile-hide" data-label="Số lượng">
        <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-decrease" data-id="<?php echo e($item->id); ?>" aria-label="Giảm">-</button>
            <input type="number" value="<?php echo e($item->quantity); ?>" min="1" step="1" inputmode="numeric" pattern="[0-9]*" class="w-14 text-center border-0 cart-qty-input" data-update-url="<?php echo e(route('user.cart.update', $item->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>" data-id="<?php echo e($item->id); ?>">
            <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-increase" data-id="<?php echo e($item->id); ?>" aria-label="Tăng">+</button>
        </div>
    </td>

    <td class="text-right align-middle hidden md:table-cell mobile-hide" data-label="Giá" style="vertical-align: middle;">
        <div class="text-[11px] text-gray-400 line-through js-price-original" <?php if(!($originalPriceBeforeDiscount>0)): ?> style="display:none" <?php endif; ?>>
            Gốc: <span class="js-price-original-val"><?php echo e(number_format($originalPriceBeforeDiscount,0,',','.')); ?></span> đ
        </div>
        <div class="text-[11px] text-red-600 js-price-discount" <?php if(!($discountPercentage > 0)): ?> style="display:none" <?php endif; ?>>
            Giảm giá: -<span class="js-price-discount-percent"><?php echo e(number_format($discountPercentage,0)); ?></span>% (<span class="js-price-discount-amount"><?php echo e(number_format($discountAmount,0,',','.')); ?></span> đ)
        </div>
        <div class="text-[11px] text-gray-700 js-price-current" <?php if(!($currentPrice>0)): ?> style="display:none" <?php endif; ?>>
            Hiện tại: <span class="js-price-current-val"><?php echo e(number_format($currentPrice,0,',','.')); ?></span> đ
        </div>
        <div class="text-[11px] text-blue-600 js-price-color" <?php if(!$item->color_id): ?> style="display:none" <?php endif; ?>>
            Màu <span class="js-price-color-name"><?php echo e($item->color->color_name ?? ''); ?></span>: +<span class="js-price-color-val"><?php echo e(number_format($colorPriceAdjustment,0,',','.')); ?></span> đ
        </div>
        <div class="text-[11px] text-indigo-700 js-price-addon" style="display:none"></div>
        <?php $hasPaidOption = false; ?>
        <?php if($selectedFeatures->count() > 0): ?>
            <?php $__currentLoopData = $selectedFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $fee=(float)($sf->price ?? 0); if($fee>0){ $hasPaidOption=true; } ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        <div class="js-price-options" <?php if(!$hasPaidOption): ?> style="display:none" <?php endif; ?>>
            <?php if($hasPaidOption): ?>
                <?php $__currentLoopData = $selectedFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $fee=(float)($sf->price ?? 0); ?>
                    <?php if($fee > 0): ?>
                        <div class="text-[11px] text-emerald-700"><?php echo e($sf->feature_name); ?>: +<?php echo e(number_format($fee,0,',','.')); ?> đ</div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
        <div class="border-t border-gray-300 pt-2 mt-2">
            <div class="text-gray-900 font-bold whitespace-nowrap text-sm sm:text-base"><span class="item-total whitespace-nowrap" data-id="<?php echo e($item->id); ?>"><?php echo e(number_format($itemTotal,0,',','.')); ?></span> đ</div>
        </div>
    </td>
    <td class="text-center align-middle hidden md:table-cell mobile-hide" data-label="Thao tác" style="vertical-align: middle;">
        <div class="flex items-center justify-center gap-2">
            <button type="button" aria-label="Thêm cấu hình khác" title="Thêm cấu hình khác" class="duplicate-item-btn w-8 h-8 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center js-duplicate-line" style="transition: all 0.2s ease; position: relative; overflow: hidden; z-index: 1;" data-add-url="<?php echo e(route('user.cart.add')); ?>" data-variant-id="<?php echo e($item->item->id); ?>" data-item-type="<?php echo e($item->item_type); ?>">
                <i class="fas fa-plus text-sm" style="position: relative; z-index: 1;"></i>
            </button>
            <style>
                .duplicate-item-btn::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 100%;
                    height: 100%;
                    background-color: #dbeafe;
                    border-radius: 50%;
                    transform: translate(-50%, -50%) scale(0);
                    transition: all 0.3s ease;
                    z-index: 0;
                }
                .duplicate-item-btn:hover::before {
                    transform: translate(-50%, -50%) scale(1);
                }
                .duplicate-item-btn:hover {
                    /* No scale effect */
                }
            </style>
            <button type="button" aria-label="Xóa" title="Xóa" class="remove-item-btn w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center" data-id="<?php echo e($item->id); ?>" data-url="<?php echo e(route('user.cart.remove', $item->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    </td>
</tr>

<!-- Mobile Card Layout - ONLY visible on mobile -->
<tr class="cart-item-row mobile-only" data-id="<?php echo e($item->id); ?>" data-item-type="<?php echo e($item->item_type); ?>" data-color-id="<?php echo e($item->color_id); ?>" data-base-unit="<?php echo e((int) $baseUnit); ?>" data-original-price="<?php echo e((int) $originalPriceBeforeDiscount); ?>" data-current-price="<?php echo e((int) $currentPrice); ?>">
    <td colspan="5" class="p-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mx-4 mb-4 overflow-hidden">
            <!-- Product Header -->
            <div class="p-4 pb-3">
                <div class="flex items-center gap-3">
                    <!-- Product Image -->
                    <a href="<?php echo e(route('car-variants.show', $item->item->slug ?? $item->item->id)); ?>" class="flex-shrink-0 w-20 h-16 rounded-xl overflow-hidden bg-gray-100 border flex items-center justify-center">
                        <img src="<?php echo e($imageUrl); ?>" class="w-full h-full object-cover" alt="<?php echo e($item->item->name); ?>" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Image';">
                    </a>
                    
                    <!-- Product Info -->
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-gray-900 text-base leading-tight">
                            <a href="<?php echo e(route('car-variants.show', $item->item->slug ?? $item->item->id)); ?>" class="hover:text-blue-600 transition-colors"><?php echo e($item->item->name); ?></a>
                        </div>
                        <?php if($item->item->carModel && $item->item->carModel->carBrand): ?>
                            <div class="text-sm text-gray-600 mt-1"><?php echo e($item->item->carModel->carBrand->name); ?> • <?php echo e($item->item->carModel->name); ?></div>
                        <?php endif; ?>
                        <?php
                            $brief = null;
                            if ($item->item->carModel && !empty($item->item->carModel->description)) {
                                $brief = Str::limit(strip_tags($item->item->carModel->description), 80);
                            }
                        ?>
                        <?php if($brief): ?>
                            <div class="text-xs text-gray-500 mt-1 line-clamp-2"><?php echo e($brief); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center justify-center gap-4">
                        <button type="button" aria-label="Thêm cấu hình khác" title="Thêm cấu hình khác" class="duplicate-item-btn w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center js-duplicate-line" style="transition: all 0.2s ease; position: relative; overflow: hidden; z-index: 1;" data-add-url="<?php echo e(route('user.cart.add')); ?>" data-variant-id="<?php echo e($item->item->id); ?>" data-item-type="<?php echo e($item->item_type); ?>">
                            <i class="fas fa-plus text-base" style="position: relative; z-index: 1;"></i>
                        </button>
                        <button type="button" aria-label="Xóa" title="Xóa" class="remove-item-btn w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center" data-id="<?php echo e($item->id); ?>" data-url="<?php echo e(route('user.cart.remove', $item->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>">
                            <i class="fas fa-trash text-base"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Color Selection -->
            <?php if($item->item_type === 'car_variant' && isset($item->item->colors) && $item->item->colors->count() > 0): ?>
            <div class="px-4 pb-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Màu: <span class="font-medium selected-color-name"><?php echo e($item->color->color_name ?? 'Chưa chọn'); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php $__currentLoopData = $item->item->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" class="color-option w-6 h-6 rounded-full border-2 <?php echo e($item->color_id == $color->id ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-300'); ?> transition-all hover:scale-110" title="<?php echo e($color->color_name); ?>"
                                data-bg-hex="<?php echo e(\App\Helpers\ColorHelper::getColorHex($color->color_name)); ?>" data-color-id="<?php echo e($color->id); ?>" data-color-name="<?php echo e($color->color_name); ?>" data-item-id="<?php echo e($item->id); ?>"
                                data-update-url="<?php echo e(route('user.cart.update', $item->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>"></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                
                <div class="mt-2" id="stock-badge-mobile-<?php echo e($item->id); ?>" <?php if(!$item->color_id): ?> style="display:none" <?php endif; ?>>
                    <?php if($item->color_id && $colorStockInfo): ?>
                        <?php if (isset($component)) { $__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stock-badge','data' => ['stock' => $colorStockInfo['available'],'type' => 'car_variant','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stock-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['stock' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($colorStockInfo['available']),'type' => 'car_variant','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4)): ?>
<?php $attributes = $__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4; ?>
<?php unset($__attributesOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4)): ?>
<?php $component = $__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4; ?>
<?php unset($__componentOriginal8c4d3e6a7bdeb5f6eaebbbf1808b98c4); ?>
<?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Features Section -->
            <?php if($item->item_type === 'car_variant' && isset($item->item->featuresRelation) && $item->item->featuresRelation->count() > 0): ?>
            <?php
                $allFeats = $item->item->featuresRelation;
                $standardFeats = $allFeats->filter(function($f){
                    $isIncluded = (bool)($f->is_included ?? false);
                    $isStandard = ($f->availability ?? 'standard') === 'standard';
                    $zeroPrice = (float)($f->price ?? 0) <= 0;
                    return $isIncluded || $isStandard || $zeroPrice;
                });
                $optionalFeats = $allFeats->filter(function($f){
                    $isIncluded = (bool)($f->is_included ?? false);
                    $isStandard = ($f->availability ?? 'standard') === 'standard';
                    $zeroPrice = (float)($f->price ?? 0) <= 0;
                    return !($isIncluded || $isStandard || $zeroPrice);
                });
            ?>
            <div class="px-4 pb-3">
                <?php if($standardFeats->count() > 0): ?>
                <div class="mb-3">
                    <div class="text-xs font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        Trang bị sẵn
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $standardFeats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-full bg-green-50 border border-green-200 text-xs text-green-700">
                                <i class="fas fa-check text-green-500 text-xs"></i>
                                <span class="whitespace-nowrap"><?php echo e($f->feature_name); ?></span>
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if($optionalFeats->count() > 0): ?>
                <div>
                    <div class="text-xs font-medium text-gray-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-plus-circle text-blue-500"></i>
                        Tuỳ chọn thêm
                    </div>
                    <div class="space-y-2">
                        <?php $__currentLoopData = $optionalFeats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $fee=(float)($f->price ?? 0); ?>
                            <label class="flex items-center gap-3 p-2 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer">
                                <input type="checkbox" class="cart-feature js-opt w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" data-fee="<?php echo e((int)$fee); ?>" data-id="<?php echo e($item->id); ?>" value="<?php echo e($f->id); ?>" <?php echo e(in_array($f->id, $featIds ?? []) ? 'checked' : ''); ?>>
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm text-gray-700 font-medium"><?php echo e($f->feature_name); ?></span>
                                </div>
                                <?php if($fee>0): ?>
                                <span class="text-sm font-semibold text-blue-600 whitespace-nowrap">+<?php echo e(number_format($fee,0,',','.')); ?>đ</span>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Action Bar -->
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                <div class="flex flex-col gap-3">
                    <!-- Quantity and Price Row -->
                    <div class="flex items-center justify-between">
                        <!-- Quantity Control -->
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600 font-medium">Số lượng:</span>
                            <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
                                <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-decrease transition-colors" data-id="<?php echo e($item->id); ?>" aria-label="Giảm">-</button>
                                <input type="number" value="<?php echo e($item->quantity); ?>" min="1" step="1" inputmode="numeric" pattern="[0-9]*" class="w-12 text-center border-0 cart-qty-input bg-transparent text-sm font-medium" data-update-url="<?php echo e(route('user.cart.update', $item->id)); ?>" data-csrf="<?php echo e(csrf_token()); ?>" data-id="<?php echo e($item->id); ?>">
                                <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-increase transition-colors" data-id="<?php echo e($item->id); ?>" aria-label="Tăng">+</button>
                            </div>
                        </div>
                        
                        <!-- Total Price Info -->
                        <div class="text-right min-w-0 flex-1">
                            <div class="space-y-1">
                                <div class="text-[11px] text-gray-400 line-through js-price-original" <?php if(!($originalPriceBeforeDiscount>0)): ?> style="display:none" <?php endif; ?>>
                                    Gốc: <span class="js-price-original-val"><?php echo e(number_format($originalPriceBeforeDiscount,0,',','.')); ?></span> đ
                                </div>
                                <div class="text-[11px] text-red-600 js-price-discount" <?php if(!($discountPercentage > 0)): ?> style="display:none" <?php endif; ?>>
                                    Giảm giá: -<span class="js-price-discount-percent"><?php echo e(number_format($discountPercentage,0)); ?></span>% (<span class="js-price-discount-amount"><?php echo e(number_format($discountAmount,0,',','.')); ?></span> đ)
                                </div>
                                <div class="text-[11px] text-gray-700 js-price-current" <?php if(!($currentPrice>0)): ?> style="display:none" <?php endif; ?>>
                                    Hiện tại: <span class="js-price-current-val"><?php echo e(number_format($currentPrice,0,',','.')); ?></span> đ
                                </div>
                                <div class="text-[11px] text-blue-600 js-price-color" <?php if(!$item->color_id): ?> style="display:none" <?php endif; ?>>
                                    Màu <span class="js-price-color-name"><?php echo e($item->color->color_name ?? ''); ?></span>: +<span class="js-price-color-val"><?php echo e(number_format($colorPriceAdjustment,0,',','.')); ?></span> đ
                                </div>
                                <div class="text-[11px] text-indigo-700 js-price-addon" style="display:none"></div>
                                <?php $hasPaidOption = false; ?>
                                <?php if($selectedFeatures->count() > 0): ?>
                                    <?php $__currentLoopData = $selectedFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $fee=(float)($sf->price ?? 0); if($fee>0){ $hasPaidOption=true; } ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <div class="js-price-options" <?php if(!$hasPaidOption): ?> style="display:none" <?php endif; ?>>
                                    <?php if($hasPaidOption): ?>
                                        <?php $__currentLoopData = $selectedFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $fee=(float)($sf->price ?? 0); ?>
                                            <?php if($fee > 0): ?>
                                                <div class="text-[11px] text-emerald-700"><?php echo e($sf->feature_name); ?>: +<?php echo e(number_format($fee,0,',','.')); ?> đ</div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="pt-2 mt-2">
                                    <div class="text-lg font-bold text-blue-600">Giá: <span class="item-total" data-id="<?php echo e($item->id); ?>"><?php echo e(number_format($itemTotal,0,',','.')); ?></span> đ</div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </td>
</tr>


<script>
window.cartItemStockData = window.cartItemStockData || {};
window.cartItemStockData[<?php echo e($item->id); ?>] = <?php echo json_encode($allColorStocks, 15, 512) ?>;
</script>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/cart/partials/car-item.blade.php ENDPATH**/ ?>