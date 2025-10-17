<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'description' => null, 'icon' => null]));

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

foreach (array_filter((['title', 'description' => null, 'icon' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
        <div class="min-w-0 flex-1">
            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
                <?php if($icon): ?>
                    <i class="<?php echo e($icon); ?> text-blue-600 mr-2 sm:mr-3 text-base sm:text-lg lg:text-xl"></i>
                <?php endif; ?>
                <span class="truncate"><?php echo e($title); ?></span>
            </h1>
            <?php if($description): ?>
                <p class="text-gray-600 mt-1 text-xs sm:text-sm lg:text-base"><?php echo e($description); ?></p>
            <?php endif; ?>
        </div>
        <div class="flex-shrink-0">
            <?php echo e($slot); ?>

        </div>
    </div>
</div>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/page-header.blade.php ENDPATH**/ ?>