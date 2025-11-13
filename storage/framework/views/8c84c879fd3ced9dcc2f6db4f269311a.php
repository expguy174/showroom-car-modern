<?php $__env->startSection('content'); ?>
<h2>Thông báo hủy đơn hàng</h2>

<p>Xin chào <?php echo e($order->user->userProfile->name ?? 'Quý khách'); ?>,</p>

<p>Đơn hàng <strong>#<?php echo e($order->order_number); ?></strong> của bạn đã bị hủy.</p>

<div class="info-box">
    <p><strong>Mã đơn hàng:</strong> <?php echo e($order->order_number); ?></p>
    <p><strong>Tổng tiền:</strong> <?php echo e(number_format($order->grand_total)); ?> VNĐ</p>
    <?php if($reason): ?>
    <p><strong>Lý do:</strong> <?php echo e($reason); ?></p>
    <?php endif; ?>
</div>

<p>Nếu bạn đã thanh toán, chúng tôi sẽ hoàn tiền trong vòng 5-7 ngày làm việc.</p>

<p>Nếu có thắc mắc, vui lòng liên hệ với chúng tôi qua hotline: <strong>1900-xxxx</strong></p>

<a href="<?php echo e(route('user.orders.show', $order->id)); ?>" class="button">Xem chi tiết</a>

<p>Trân trọng,<br><?php echo e(config('app.name')); ?></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/emails/order-cancelled.blade.php ENDPATH**/ ?>