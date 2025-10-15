<?php $__env->startSection('content'); ?>
<?php if($isLastInstallment): ?>
    <h2>🎉 Chúc mừng! Hoàn thành trả góp</h2>
    
    <p>Xin chào <?php echo e($installment->user->userProfile->name ?? 'Quý khách'); ?>,</p>
    
    <p>Chúc mừng bạn đã hoàn thành <strong>tất cả <?php echo e($installment->order->tenure_months); ?> kỳ</strong> trả góp!</p>
    
    <div class="info-box">
        <p><strong>Đơn hàng:</strong> #<?php echo e($installment->order->order_number); ?></p>
        <p><strong>Kỳ cuối:</strong> Kỳ <?php echo e($installment->installment_number); ?>/<?php echo e($installment->order->tenure_months); ?></p>
        <p><strong>Số tiền:</strong> <?php echo e(number_format($installment->amount)); ?> VNĐ</p>
        <p><strong>Ngày thanh toán:</strong> <?php echo e($installment->paid_at->format('d/m/Y H:i')); ?></p>
    </div>
    
    <p>🎊 Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ trả góp của chúng tôi!</p>
    
<?php else: ?>
    <h2>Xác nhận thanh toán kỳ trả góp</h2>
    
    <p>Xin chào <?php echo e($installment->user->userProfile->name ?? 'Quý khách'); ?>,</p>
    
    <p>Chúng tôi đã nhận được thanh toán cho <strong>kỳ <?php echo e($installment->installment_number); ?></strong> của đơn hàng <strong>#<?php echo e($installment->order->order_number); ?></strong>.</p>
    
    <div class="info-box">
        <p><strong>Kỳ:</strong> <?php echo e($installment->installment_number); ?>/<?php echo e($installment->order->tenure_months); ?></p>
        <p><strong>Số tiền:</strong> <?php echo e(number_format($installment->amount)); ?> VNĐ</p>
        <p><strong>Ngày thanh toán:</strong> <?php echo e($installment->paid_at->format('d/m/Y H:i')); ?></p>
        <?php
            $remainingInstallments = $installment->order->installments()
                ->whereIn('status', ['pending', 'overdue'])
                ->count();
        ?>
        <p><strong>Còn lại:</strong> <?php echo e($remainingInstallments); ?> kỳ</p>
    </div>
    
    <p>✅ Cảm ơn bạn đã thanh toán đúng hạn!</p>
<?php endif; ?>

<a href="<?php echo e(route('user.orders.show', $installment->order_id)); ?>" class="button">Xem lịch trả góp</a>

<p>Trân trọng,<br><?php echo e(config('app.name')); ?></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/emails/installment-paid.blade.php ENDPATH**/ ?>