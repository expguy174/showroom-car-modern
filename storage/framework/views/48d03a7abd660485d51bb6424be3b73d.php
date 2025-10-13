<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #4f46e5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .order-details {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            color: #4f46e5;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Đơn hàng của bạn đã được xác nhận!</h1>
        <p>Mã đơn hàng: #<?php echo e($order->id); ?></p>
    </div>

    <div class="content">
        <h2>Xin chào <?php echo e(optional($order->user)->name); ?>,</h2>
        
        <p>Cảm ơn bạn đã đặt hàng tại Showroom Car. Đơn hàng của bạn đã được xác nhận và đang được xử lý.</p>

        <div class="order-details">
            <h3>📋 Chi tiết đơn hàng</h3>
            
            <div class="item">
                <span><strong>Mã đơn hàng:</strong></span>
                <span>#<?php echo e($order->order_number ?? $order->id); ?></span>
            </div>
            
            <div class="item">
                <span><strong>Ngày đặt:</strong></span>
                <span><?php echo e($order->created_at->format('d/m/Y H:i')); ?></span>
            </div>
            
            <div class="item">
                <span><strong>Phương thức thanh toán:</strong></span>
                <span><?php echo e(optional($order->paymentMethod)->name ?? 'N/A'); ?></span>
            </div>
            
            <div class="item">
                <span><strong>Trạng thái:</strong></span>
                <span>
                    <?php switch($order->status):
                        case ('pending'): ?>
                            ⏳ Chờ xử lý
                            <?php break; ?>
                        <?php case ('confirmed'): ?>
                            ✅ Đã xác nhận
                            <?php break; ?>
                        <?php case ('shipping'): ?>
                            🚚 Đang giao hàng
                            <?php break; ?>
                        <?php case ('delivered'): ?>
                            📦 Đã giao hàng
                            <?php break; ?>
                        <?php case ('cancelled'): ?>
                            ❌ Đã hủy
                            <?php break; ?>
                        <?php default: ?>
                            <?php echo e($order->status); ?>

                    <?php endswitch; ?>
                </span>
            </div>
        </div>

        <div class="order-details">
            <h3>📦 Sản phẩm đã đặt</h3>
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="item">
                <span><?php echo e($item->item_name ?? 'Sản phẩm'); ?></span>
                <span><?php echo e(number_format($item->price, 0, ',', '.')); ?> VNĐ x <?php echo e($item->quantity); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <div class="item total">
                <span>Tổng cộng:</span>
                <span><?php echo e(number_format($order->total_price, 0, ',', '.')); ?> VNĐ</span>
            </div>
        </div>

        <div class="order-details">
            <h3>📍 Thông tin giao hàng</h3>
            <p><strong>Người nhận:</strong> <?php echo e(optional($order->user)->name); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo e(optional($order->user)->phone); ?></p>
            <?php if(optional($order->user)->email): ?>
            <p><strong>Email:</strong> <?php echo e(optional($order->user)->email); ?></p>
            <?php endif; ?>
            <p><strong>Địa chỉ:</strong> <?php echo e($order->shippingAddress->line1 ?? ($order->billingAddress->line1 ?? 'Không có thông tin')); ?></p>
            <?php if($order->note): ?>
            <p><strong>Ghi chú:</strong> <?php echo e($order->note); ?></p>
            <?php endif; ?>
        </div>

        <p>Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận và giao hàng. Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.</p>

        <p>Trân trọng,<br>
        <strong>Showroom Car Team</strong></p>
    </div>

    <div class="footer">
        <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        <p>© <?php echo e(date('Y')); ?> Showroom Car. Tất cả quyền được bảo lưu.</p>
    </div>
</body>
</html> <?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/emails/order-confirmation.blade.php ENDPATH**/ ?>