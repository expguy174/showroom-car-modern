<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['itemId', 'currentStatus', 'entityType' => 'variant']));

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

foreach (array_filter((['itemId', 'currentStatus', 'entityType' => 'variant']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>


<span class="status-badge inline-flex items-center px-2 py-1 text-xs rounded-md font-medium whitespace-nowrap min-h-[20px] <?php echo e($currentStatus ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>"
      data-<?php echo e($entityType); ?>-id="<?php echo e($itemId); ?>">
    <i class="fas <?php echo e($currentStatus ? 'fa-check-circle' : 'fa-times-circle'); ?> mr-1.5 w-3 h-3 flex-shrink-0"></i>
    <span><?php echo e($currentStatus ? 'Hoạt động' : 'Tạm dừng'); ?></span>
</span>

<?php $__env->startPush('scripts'); ?>
<script>
// Function to update status badge when toggle button is clicked
window.updateStatusBadge = function(itemId, newStatus, entityType = 'variant') {
    // Find all status badges for this item
    const badges = document.querySelectorAll(`[data-${entityType}-id="${itemId}"].status-badge`);
    
    badges.forEach(badge => {
        const statusIcon = badge.querySelector('i');
        const statusText = badge.querySelector('span:last-child');
        
        // Update classes
        badge.classList.remove(
            'bg-green-100', 'text-green-800',
            'bg-red-100', 'text-red-800'
        );
        
        if (newStatus) {
            badge.classList.add('bg-green-100', 'text-green-800');
            if (statusIcon) {
                statusIcon.className = 'fas fa-check-circle mr-1.5 w-3 h-3 flex-shrink-0';
            }
            if (statusText) {
                statusText.textContent = 'Hoạt động';
            }
        } else {
            badge.classList.add('bg-red-100', 'text-red-800');
            if (statusIcon) {
                statusIcon.className = 'fas fa-times-circle mr-1.5 w-3 h-3 flex-shrink-0';
            }
            if (statusText) {
                statusText.textContent = 'Tạm dừng';
            }
        }
    });
};
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/components/admin/status-toggle.blade.php ENDPATH**/ ?>