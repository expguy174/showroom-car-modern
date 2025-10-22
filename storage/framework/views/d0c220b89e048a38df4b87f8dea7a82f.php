<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    // New style props (preferred)
    'item' => null,
    'showRoute' => null,
    'editRoute' => null, 
    'deleteRoute' => null,
    'hasToggle' => false,
    
    // Legacy props (for backward compatibility)
    'itemId' => null,
    'itemName' => null, 
    'currentStatus' => null,
    'entityName' => 'item',
    'entityType' => 'variant',
    'toggleEndpoint' => null,
    'deleteData' => []
]));

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

foreach (array_filter(([
    // New style props (preferred)
    'item' => null,
    'showRoute' => null,
    'editRoute' => null, 
    'deleteRoute' => null,
    'hasToggle' => false,
    
    // Legacy props (for backward compatibility)
    'itemId' => null,
    'itemName' => null, 
    'currentStatus' => null,
    'entityName' => 'item',
    'entityType' => 'variant',
    'toggleEndpoint' => null,
    'deleteData' => []
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    // Use new style if item is provided, otherwise use legacy props
    $id = $item ? $item->id : $itemId;
    $name = $item ? $item->name : $itemName;
    $status = $item ? $item->is_active : $currentStatus;
    $entity = $item ? class_basename($item) : $entityType;
?>


<div class="flex items-center justify-center space-x-1">
    
    <?php if($hasToggle && $item): ?>
        <button type="button" 
                class="text-<?php echo e($status ? 'orange' : 'green'); ?>-600 hover:text-<?php echo e($status ? 'orange' : 'green'); ?>-900 status-toggle w-4 h-4 flex items-center justify-center"
                data-<?php echo e(strtolower($entity)); ?>-id="<?php echo e($id); ?>"
                data-status="<?php echo e($status ? 'false' : 'true'); ?>"
                title="<?php echo e($status ? 'Tạm dừng' : 'Kích hoạt'); ?>">
            <i class="fas fa-<?php echo e($status ? 'pause' : 'play'); ?> w-4 h-4"></i>
        </button>
    
    <?php elseif($toggleEndpoint): ?>
        <button type="button" 
                class="text-<?php echo e($currentStatus ? 'orange' : 'green'); ?>-600 hover:text-<?php echo e($currentStatus ? 'orange' : 'green'); ?>-900 status-toggle w-4 h-4 flex items-center justify-center"
                data-<?php echo e($entityType); ?>-id="<?php echo e($itemId); ?>"
                data-status="<?php echo e($currentStatus ? 'false' : 'true'); ?>"
                data-toggle-endpoint="<?php echo e($toggleEndpoint); ?>"
                title="<?php echo e($currentStatus ? 'Tạm dừng' : 'Kích hoạt'); ?>">
            <i class="fas fa-<?php echo e($currentStatus ? 'pause' : 'play'); ?> w-4 h-4"></i>
        </button>
    <?php endif; ?>
    
    
    <?php if($showRoute): ?>
        <a href="<?php echo e(route($showRoute, $id)); ?>" 
           class="text-gray-600 hover:text-gray-900 w-4 h-4 flex items-center justify-center" 
           title="Xem chi tiết">
            <i class="fas fa-eye w-4 h-4"></i>
        </a>
    <?php endif; ?>
    
    
    <?php if($editRoute): ?>
        <a href="<?php echo e(route($editRoute, $id)); ?>" 
           class="text-blue-600 hover:text-blue-900 w-4 h-4 flex items-center justify-center" 
           title="Chỉnh sửa">
            <i class="fas fa-edit w-4 h-4"></i>
        </a>
    <?php endif; ?>
    
    
    <?php if($deleteRoute && $item): ?>
        <button 
            class="text-red-600 hover:text-red-900 delete-btn w-4 h-4 flex items-center justify-center" 
            title="Xóa"
            data-<?php echo e(strtolower($entity)); ?>-id="<?php echo e($id); ?>"
            data-<?php echo e(strtolower($entity)); ?>-name="<?php echo e($name); ?>"
            data-delete-url="<?php echo e(route($deleteRoute, $id)); ?>"
            <?php if($deleteData): ?>
                <?php $__currentLoopData = $deleteData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    data-<?php echo e($key); ?>="<?php echo e($value); ?>"
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>>
            <i class="fas fa-trash w-4 h-4"></i>
        </button>
    
    <?php else: ?>
        <button 
            class="text-red-600 hover:text-red-900 delete-btn w-4 h-4 flex items-center justify-center" 
            title="Xóa"
            data-<?php echo e($entityType); ?>-id="<?php echo e($itemId); ?>"
            data-<?php echo e($entityType); ?>-name="<?php echo e($itemName); ?>"
            <?php $__currentLoopData = $deleteData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                data-<?php echo e($key); ?>="<?php echo e($value); ?>"
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>>
            <i class="fas fa-trash w-4 h-4"></i>
        </button>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/table-actions.blade.php ENDPATH**/ ?>