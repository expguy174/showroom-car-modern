
<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['stock', 'type' => 'accessory', 'size' => 'md']));

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

foreach (array_filter((['stock', 'type' => 'accessory', 'size' => 'md']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $badge = \App\Helpers\StockHelper::getStockBadge((int)$stock, $type);
    
    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
        default => 'px-2 py-1 text-xs'
    };
?>

<span <?php echo e($attributes->merge(['class' => "inline-flex items-center gap-1 {$sizeClasses} font-medium rounded {$badge['class']}"])); ?>>
    <span><?php echo e($badge['icon']); ?></span>
    <span><?php echo e($badge['text']); ?></span>
</span>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/stock-badge.blade.php ENDPATH**/ ?>