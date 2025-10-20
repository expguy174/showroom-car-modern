<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'value', 'icon', 'color' => 'blue', 'clickAction' => null, 'description' => null, 'trend' => null, 'trendColor' => 'green', 'size' => 'normal', 'dataStat' => null]));

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

foreach (array_filter((['title', 'value', 'icon', 'color' => 'blue', 'clickAction' => null, 'description' => null, 'trend' => null, 'trendColor' => 'green', 'size' => 'normal', 'dataStat' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Define color classes function
    $getColorClasses = function() use ($color) {
        switch($color) {
            case 'blue':
                return ['light' => 'bg-blue-100', 'text' => 'text-blue-600'];
            case 'green':
                return ['light' => 'bg-green-100', 'text' => 'text-green-600'];
            case 'red':
                return ['light' => 'bg-red-100', 'text' => 'text-red-600'];
            case 'yellow':
                return ['light' => 'bg-yellow-100', 'text' => 'text-yellow-600'];
            case 'orange':
                return ['light' => 'bg-orange-100', 'text' => 'text-orange-600'];
            case 'purple':
                return ['light' => 'bg-purple-100', 'text' => 'text-purple-600'];
            case 'pink':
                return ['light' => 'bg-pink-100', 'text' => 'text-pink-600'];
            case 'gray':
                return ['light' => 'bg-gray-100', 'text' => 'text-gray-600'];
            default:
                return ['light' => 'bg-blue-100', 'text' => 'text-blue-600'];
        }
    };

    // Define trend color classes function
    $getTrendColorClasses = function() use ($trendColor) {
        switch($trendColor) {
            case 'green':
                return 'text-green-600';
            case 'red':
                return 'text-red-600';
            case 'yellow':
                return 'text-yellow-600';
            default:
                return 'text-green-600';
        }
    };

    $colorClasses = $getColorClasses();
    $trendColorClass = $getTrendColorClasses();
    $isClickable = false; // Disabled click functionality
    $cardClasses = '';
    $sizeClasses = $size === 'large' ? 'p-8' : 'p-6';
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 <?php echo e($cardClasses); ?> <?php echo e($sizeClasses); ?>">
    
    <div class="flex items-center">
        
        <div class="flex-shrink-0">
            <div class="w-12 h-12 <?php echo e($colorClasses['light']); ?> rounded-lg flex items-center justify-center">
                <i class="<?php echo e($icon); ?> <?php echo e($colorClasses['text']); ?> text-xl"></i>
            </div>
        </div>
        
        
        <div class="ml-4 flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-600 whitespace-nowrap truncate" title="<?php echo e($title); ?>"><?php echo e($title); ?></p>
                    <p class="text-2xl font-bold text-gray-900" data-stat="<?php echo e($dataStat ?? strtolower(str_replace(' ', '-', $title))); ?>"><?php echo e($value); ?></p>
                </div>
                
                
                <?php if($trend): ?>
                <div class="text-right">
                    <div class="flex items-center <?php echo e($trendColorClass); ?>">
                        <?php if(str_contains($trend, '+') || str_contains($trend, 'tăng')): ?>
                            <i class="fas fa-arrow-up text-sm mr-1"></i>
                        <?php elseif(str_contains($trend, '-') || str_contains($trend, 'giảm')): ?>
                            <i class="fas fa-arrow-down text-sm mr-1"></i>
                        <?php else: ?>
                            <i class="fas fa-minus text-sm mr-1"></i>
                        <?php endif; ?>
                        <span class="text-sm font-medium"><?php echo e($trend); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            
            <?php if($description): ?>
            <p class="text-xs text-gray-500 mt-1 whitespace-nowrap truncate" title="<?php echo e($description); ?>"><?php echo e($description); ?></p>
            <?php endif; ?>
        </div>
    </div>
    
</div>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/stats-card.blade.php ENDPATH**/ ?>