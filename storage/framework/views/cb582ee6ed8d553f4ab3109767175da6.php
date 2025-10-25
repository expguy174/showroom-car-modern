<?php (
    $from = $paginator->firstItem() ?? 0
); ?>
<?php (
    $to = $paginator->lastItem() ?? 0
); ?>
<?php (
    $total = $paginator->total() ?? 0
); ?>
<div class="flex items-center justify-between text-sm text-gray-600 mb-3">
    <div>Tổng: <span class="font-semibold"><?php echo e(number_format($total)); ?></span> lịch</div>
    <div>Hiển thị: <span class="font-semibold"><?php echo e($from); ?></span>–<span class="font-semibold"><?php echo e($to); ?></span></div>
</div>

<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/test-drives/partials/summary.blade.php ENDPATH**/ ?>