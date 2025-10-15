<?php $__env->startSection('content'); ?>
<h2>Cập nhật thanh toán</h2>

<p>Xin chào <?php echo e($order->user->userProfile->name ?? 'Quý khách'); ?>,</p>

<p>Trạng thái thanh toán cho đơn hàng <strong>#<?php echo e($order->order_number); ?></strong> đã được cập nhật.</p>

<div class="info-box">
    <p><strong>Trạng thái mới:</strong> 
        <?php
            $statusLabels = [
                'pending' => 'Chờ thanh toán',
                'completed' => 'Đã thanh toán',
                'failed' => 'Thanh toán thất bại',
                'refunded' => 'Đã hoàn tiền',
            ];
        ?>
        <?php echo e($statusLabels[$newStatus] ?? $newStatus); ?>

    </p>
    <p><strong>Tổng tiền:</strong> <?php echo e(number_format($order->grand_total)); ?> VNĐ</p>
    <?php if($newStatus === 'completed' && $order->paid_at): ?>
    <p><strong>Thời gian thanh toán:</strong> <?php echo e($order->paid_at->format('d/m/Y H:i')); ?></p>
    <?php endif; ?>
</div>

<?php if($newStatus === 'completed'): ?>
<p>✅ Cảm ơn bạn đã thanh toán! Đơn hàng của bạn đang được xử lý.</p>
<?php elseif($newStatus === 'failed'): ?>
<p>⚠️ Thanh toán không thành công. Vui lòng thử lại hoặc liên hệ với chúng tôi.</p>
<?php elseif($newStatus === 'refunded'): ?>
<p>Số tiền đã được hoàn lại vào tài khoản của bạn trong vòng 5-7 ngày làm việc.</p>
<?php endif; ?>

<a href="<?php echo e(route('user.orders.show', $order->id)); ?>" class="button">Xem chi tiết</a>

<p>Trân trọng,<br><?php echo e(config('app.name')); ?></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/emails/payment-status-changed.blade.php ENDPATH**/ ?>