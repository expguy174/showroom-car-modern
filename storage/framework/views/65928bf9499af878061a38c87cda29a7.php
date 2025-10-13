<?php if($orders->count() === 0): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 sm:p-14 text-center">
        <div class="w-16 h-16 mx-auto mb-5 bg-gray-100 rounded-full flex items-center justify-center">
            <i class="fas fa-box text-2xl text-gray-400"></i>
        </div>
        <div class="text-lg sm:text-xl font-semibold text-gray-700 mb-2">Chưa có đơn hàng</div>
        <p class="text-sm sm:text-base text-gray-500 max-w-xl mx-auto">Bạn chưa có đơn hàng nào. Khám phá sản phẩm và đặt hàng ngay hôm nay.</p>
        
    </div>
<?php else: ?>
    <div class="space-y-3 sm:space-y-4">
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow order-card" data-id="<?php echo e($order->id); ?>">
                <div class="p-3 sm:p-5 flex flex-col h-full">
                    <div class="flex-1 flex flex-col space-y-2">
                        <!-- Dòng 1: Mã đơn + Ngày tạo (trái) | Giá tiền (phải) -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 flex-wrap min-w-0">
                                <a href="<?php echo e(route('user.orders.show', $order)); ?>" class="text-gray-800 font-semibold truncate hover:underline">#<?php echo e($order->order_number ?? $order->id); ?></a>
                                <span class="hidden xs:inline text-gray-400">•</span>
                                <span class="text-gray-500 text-xs sm:text-sm"><?php echo e($order->created_at?->format('d/m/Y H:i')); ?></span>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-indigo-700 font-extrabold text-sm sm:text-lg"><?php echo e(number_format($order->finance_option_id ? ($order->down_payment_amount ?? 0) : $order->grand_total, 0, ',', '.')); ?> đ</div>
                            </div>
                        </div>

                        <!-- Dòng 2: Meta info (trái) | Trả trước/Khuyến mãi (phải) -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="text-xs sm:text-sm text-gray-500 flex items-center gap-3 min-w-0" data-role="order-meta">
                                <div><?php echo e($order->items->count()); ?> sản phẩm</div>
                                <div class="flex items-center gap-1 text-gray-600">
                                    <i class="fas fa-shipping-fast text-[10px]"></i>
                                    <span><?php echo e($order->shipping_method === 'express' ? 'Giao nhanh' : 'Tiêu chuẩn'); ?></span>
                                </div>
                                <?php if($order->paymentMethod): ?>
                                    <div class="flex items-center gap-1 text-gray-600">
                                        <i class="fas fa-credit-card text-[10px]"></i>
                                        <span><?php echo e($order->paymentMethod->name); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="text-right shrink-0 flex flex-col space-y-2">
                                <?php if($order->finance_option_id): ?>
                                    <div class="text-[11px] sm:text-xs text-gray-500">Trả trước</div>
                                <?php else: ?>
                                    <div class="text-[11px] sm:text-xs text-gray-500">Tổng cộng</div>
                                <?php endif; ?>
                                <?php if((float)($order->discount_total ?? 0) > 0): ?>
                                    <div class="text-[10px] sm:text-xs text-green-600">
                                        <i class="fas fa-tag mr-1"></i>Có khuyến mãi
                                    </div>
                                <?php else: ?>
                                    <?php if($order->finance_option_id): ?>
                                        <div class="text-[10px] sm:text-xs text-blue-600">
                                            <i class="fas fa-credit-card mr-1"></i><?php echo e($order->tenure_months ?? 0); ?> tháng
                                        </div>
                                    <?php else: ?>
                                        <div class="text-[10px] sm:text-xs text-emerald-600">
                                            <i class="fas fa-check-circle mr-1"></i>Thanh toán đầy đủ
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Dòng 3: Status (trái) | Buttons (phải) -->
                        <?php
                            // Improved cancel logic consistent with detail page
                            $canCancel = in_array($order->status, ['pending', 'confirmed']) 
                                && !in_array($order->payment_status, ['completed', 'processing']);
                            
                            // Additional checks for finance orders
                            if ($order->finance_option_id && $order->down_payment_amount > 0) {
                                // If down payment is made, only allow cancel if payment is still pending
                                $canCancel = $canCancel && $order->payment_status === 'pending';
                            }
                            
                            // Time-based restriction: 24 hours window for cancellation
                            $withinCancelWindow = $order->created_at->diffInHours(now()) <= 24;
                            $canCancel = $canCancel && $withinCancelWindow;
                            
                            // Generate cancel reason for better UX
                            $cancelReason = '';
                            if (!in_array($order->status, ['pending', 'confirmed'])) {
                                $cancelReason = 'Đơn hàng đã được xử lý, không thể hủy';
                            } elseif (in_array($order->payment_status, ['completed', 'processing'])) {
                                $cancelReason = 'Thanh toán đã được xử lý, không thể hủy';
                            } elseif ($order->finance_option_id && $order->down_payment_amount > 0 && $order->payment_status !== 'pending') {
                                $cancelReason = 'Đã thanh toán trả trước, vui lòng yêu cầu hoàn tiền';
                            } elseif (!$withinCancelWindow) {
                                $cancelReason = 'Chỉ có thể hủy trong vòng 24 giờ sau khi đặt hàng';
                            } else {
                                $cancelReason = 'Hủy đơn hàng';
                            }
                        ?>
                        <div class="flex items-center justify-between gap-2">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3" data-role="status-container">
                            <div class="flex items-center gap-1 text-[10px] sm:text-xs">
                                <span class="text-gray-500">Đơn hàng:</span>
                                <span class="inline-flex items-center py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold
                                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'bg-yellow-50 text-yellow-700' => $order->status === 'pending',
                                        'bg-blue-50 text-blue-700' => $order->status === 'confirmed',
                                        'bg-indigo-50 text-indigo-700' => $order->status === 'shipping',
                                        'bg-emerald-50 text-emerald-700' => $order->status === 'delivered',
                                        'text-gray-800 font-semibold' => $order->status === 'cancelled',
                                    ]); ?>"" 
                                    data-role="status-badge"
                                    data-status="<?php echo e($order->status); ?>">
                                    <i class="fas 
                                        <?php if($order->status === 'pending'): ?> fa-clock
                                        <?php elseif($order->status === 'confirmed'): ?> fa-check-circle
                                        <?php elseif($order->status === 'shipping'): ?> fa-shipping-fast
                                        <?php elseif($order->status === 'delivered'): ?> fa-check-double
                                        <?php elseif($order->status === 'cancelled'): ?> fa-ban
                                        <?php else: ?> fa-box
                                        <?php endif; ?> mr-1"></i> <?php echo e($order->status_display); ?>

                                </span>
                            </div>
                            <div class="flex items-center gap-1 text-[10px] sm:text-xs">
                                <span class="text-gray-500">Thanh toán:</span>
                                <span class="inline-flex items-center py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-semibold
                                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'bg-gray-50 text-gray-700' => $order->payment_status === 'pending',
                                        'bg-blue-50 text-blue-700' => $order->payment_status === 'processing',
                                        'bg-emerald-50 text-emerald-700' => $order->payment_status === 'completed',
                                        'text-gray-800 font-semibold' => $order->payment_status === 'failed',
                                        'text-gray-800 font-semibold' => $order->payment_status === 'cancelled',
                                    ]); ?>""
                                    data-role="payment-status-badge"
                                    data-payment-status="<?php echo e($order->payment_status); ?>">
                                    <i class="fas 
                                        <?php if($order->payment_status === 'pending'): ?> fa-clock
                                        <?php elseif($order->payment_status === 'processing'): ?> fa-spinner
                                        <?php elseif($order->payment_status === 'completed'): ?> fa-check-circle
                                        <?php elseif($order->payment_status === 'failed'): ?> fa-times-circle
                                        <?php elseif($order->payment_status === 'cancelled'): ?> fa-ban
                                        <?php else: ?> fa-credit-card
                                        <?php endif; ?> mr-1"></i> 
                                    <?php echo e($order->payment_status_display); ?>

                                </span>
                            </div>
                        </div>
                            <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('user.orders.show', $order)); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs"><i class="fas fa-eye"></i> Chi tiết</a>
                            <?php if($order->status !== 'cancelled'): ?>
                                <form action="<?php echo e(route('user.orders.cancel', $order)); ?>" method="post" title="<?php echo e($cancelReason); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500 text-white hover:bg-rose-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed" <?php echo e($canCancel ? '' : 'disabled'); ?>>
                                        <i class="fas fa-ban"></i> Hủy đơn
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-gray-500 bg-gray-100">
                                    <i class="fas fa-ban"></i> Đã hủy
                                </span>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>


<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/orders/partials/list.blade.php ENDPATH**/ ?>