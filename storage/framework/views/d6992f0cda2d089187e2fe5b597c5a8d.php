<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['brand']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['brand']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<a href="<?php echo e(route('car-brands.show', $brand->id)); ?>"
   class="group relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
    
    <!-- Featured Badge - Top Right -->
    <?php if($brand->is_featured): ?>
        <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
            <i class="fas fa-star text-[10px]"></i> Nổi bật
        </span>
    <?php endif; ?>
    
    <div class="text-center">
        <!-- Brand Logo -->
        <?php $logo = $brand->logo_url ?? null; ?>
        <?php if($logo): ?>
            <img src="<?php echo e($logo); ?>" 
                 alt="<?php echo e($brand->name); ?>" 
                 class="w-20 h-20 mx-auto mb-4 object-contain group-hover:scale-110 transition-transform duration-300" loading="lazy" decoding="async">
        <?php else: ?>
            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-car text-white text-3xl"></i>
            </div>
        <?php endif; ?>
        
        <!-- Brand Name -->
        <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-blue-600 transition-colors duration-300">
            <?php echo e($brand->name); ?>

        </h3>

        <!-- Show number of models instead of country -->
        <?php 
            $modelsCount = $brand->relationLoaded('carModels') ? $brand->carModels->count() : ($brand->carModels()->count());
        ?>
        <div class="text-sm text-gray-600 mb-4 flex items-center justify-center gap-2">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-xs font-medium">
                <i class="fas fa-layer-group mr-1 text-blue-500"></i>
                <?php echo e(number_format($modelsCount)); ?> dòng xe
            </span>
        </div>
        

    </div>
</a>


<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/brand-card.blade.php ENDPATH**/ ?>