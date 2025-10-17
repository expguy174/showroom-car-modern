<?php $__env->startSection('content'); ?>
<h2>🎉 Xác nhận đơn hàng thành công!</h2>

<p>Xin chào <?php echo e($order->user->userProfile->name ?? $order->user->email ?? 'Quý khách'); ?>,</p>

<p>Cảm ơn bạn đã đặt hàng tại <?php echo e(config('app.name')); ?>! Đơn hàng của bạn đã được xác nhận và đang được xử lý.</p>

<div class="info-box">
    <p><strong>Mã đơn hàng:</strong> #<?php echo e($order->order_number); ?></p>
    <p><strong>Ngày đặt:</strong> <?php echo e($order->created_at->format('d/m/Y H:i')); ?></p>
    <p><strong>Phương thức thanh toán:</strong> <?php echo e($order->paymentMethod->name ?? 'N/A'); ?></p>
    <p><strong>Trạng thái:</strong> 
        <?php
            $statusLabels = [
                'pending' => '⏳ Chờ xử lý',
                'confirmed' => '✅ Đã xác nhận',
                'shipping' => '🚚 Đang giao hàng',
                'delivered' => '📦 Đã giao hàng',
                'cancelled' => '❌ Đã hủy'
            ];
        ?>
        <?php echo e($statusLabels[$order->status] ?? $order->status); ?>

    </p>
</div>

<h3>📦 Sản phẩm đã đặt</h3>
<div class="info-box">
    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <p style="margin: 5px 0; display: flex; justify-content: space-between;">
        <span><?php echo e($item->item_name ?? 'Sản phẩm'); ?> x<?php echo e($item->quantity); ?></span>
        <strong><?php echo e(number_format($item->line_total ?? ($item->price * $item->quantity))); ?> VNĐ</strong>
    </p>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <hr style="margin: 10px 0; border: none; border-top: 2px solid #667eea;">
    <p style="margin: 10px 0; display: flex; justify-content: space-between; font-size: 18px;">
        <strong>Tổng cộng:</strong>
        <strong style="color: #667eea;"><?php echo e(number_format($order->grand_total ?? $order->total_price)); ?> VNĐ</strong>
    </p>
</div>

<?php if($order->shippingAddress || $order->billingAddress): ?>
<h3>📍 Thông tin giao hàng</h3>
<div class="info-box">
    <p><strong>Người nhận:</strong> <?php echo e($order->user->userProfile->name ?? $order->user->email); ?></p>
    <?php if($order->user->userProfile && $order->user->userProfile->phone): ?>
    <p><strong>Số điện thoại:</strong> <?php echo e($order->user->userProfile->phone); ?></p>
    <?php endif; ?>
    <?php if($order->shippingAddress): ?>
    <p><strong>Địa chỉ giao hàng:</strong><br>
    <?php echo e($order->shippingAddress->line1); ?><br>
    <?php echo e($order->shippingAddress->city ?? ''); ?> <?php echo e($order->shippingAddress->district ?? ''); ?><br>
    <?php echo e($order->shippingAddress->province ?? ''); ?>

    </p>
    <?php endif; ?>
    <?php if($order->note): ?>
    <p><strong>Ghi chú:</strong> <?php echo e($order->note); ?></p>
    <?php endif; ?>
</div>
<?php endif; ?>

<p>Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận và giao hàng.</p>

<a href="<?php echo e(route('user.orders.show', $order->id)); ?>" class="button">Xem chi tiết đơn hàng</a>

<p>Trân trọng,<br><?php echo e(config('app.name')); ?></p>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/emails/order-confirmation.blade.php ENDPATH**/ ?>