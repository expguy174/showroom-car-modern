<?php $__env->startSection('title', 'Hoàn tất đơn hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Hoàn tất đơn hàng</h1>
                        <p class="text-gray-600">Đơn hàng đã được tạo thành công</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="<?php echo e(route('user.cart.index')); ?>" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay về giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div>
                <div class="flex items-center justify-center space-x-8">
                    <a href="<?php echo e(route('user.cart.index')); ?>" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                        <span class="font-medium text-gray-500">Giỏ hàng</span>
                    </a>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                        <span class="font-medium text-gray-500">Thanh toán</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                        <span class="font-semibold text-emerald-600">Hoàn tất</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fas fa-check"></i></div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-extrabold text-gray-900">Cảm ơn bạn! Đơn hàng đã được tạo</h1>
                        <div class="text-sm text-gray-600">Mã đơn: <span class="font-semibold text-indigo-700"><?php echo e($order->order_number ?? ('#'.$order->id)); ?></span></div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="md:col-span-3 space-y-4">
                    <div class="p-4 rounded-xl border bg-white">
                        <div class="mb-3">
                            <div class="text-sm font-semibold text-gray-800">Trạng thái</div>
                        </div>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                            <!-- Cột trái -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                    <dt class="text-gray-500">Đơn hàng</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'bg-yellow-50 text-yellow-700 border border-yellow-200' => $order->status === 'pending',
                                                'bg-blue-50 text-blue-700 border border-blue-200' => $order->status === 'confirmed',
                                                'bg-indigo-50 text-indigo-700 border border-indigo-200' => $order->status === 'shipping',
                                                'bg-emerald-50 text-emerald-700 border border-emerald-200' => $order->status === 'delivered',
                                                'bg-rose-50 text-rose-700 border border-rose-200' => $order->status === 'cancelled',
                                            ]); ?>""><?php echo e($order->status_display); ?></span>
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                    <dt class="text-gray-500">Trạng thái</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                            class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                                'bg-gray-50 text-gray-700 border border-gray-200' => $order->payment_status === 'pending',
                                                'bg-blue-50 text-blue-700 border border-blue-200' => $order->payment_status === 'processing',
                                                'bg-emerald-50 text-emerald-700 border border-emerald-200' => $order->payment_status === 'completed',
                                                'bg-rose-50 text-rose-700 border border-rose-200' => $order->payment_status === 'failed',
                                                'bg-slate-50 text-slate-700 border border-slate-200' => $order->payment_status === 'cancelled',
                                            ]); ?>""><?php echo e($order->payment_status_display); ?></span>
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                    <dt class="text-gray-500">Tạo lúc</dt>
                                    <dd class="font-medium text-gray-900"><?php echo e($order->created_at?->format('d/m/Y H:i')); ?></dd>
                                </div>
                            </div>
                            
                            <!-- Cột phải -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                    <dt class="text-gray-500">Hình thức</dt>
                                    <dd class="font-medium text-gray-900">
                                        <?php if($order->finance_option_id): ?>
                                            Trả góp
                                        <?php else: ?>
                                            Thanh toán 1 lần
                                        <?php endif; ?>
                                    </dd>
                                </div>
                                <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                                    <dt class="text-gray-500">Phương thức</dt>
                                    <dd class="font-medium text-gray-900"><?php echo e($order->paymentMethod->name ?? '—'); ?></dd>
                                </div>
                            </div>
                        </dl>
                        
                        <?php if(session('payment_method') === 'bank_transfer' || $order->paymentMethod?->code === 'bank_transfer'): ?>
                        <?php if(!$order->finance_option_id): ?>
                        <!-- Bank Transfer Info for Full Payment -->
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="text-sm font-semibold text-blue-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-university"></i>
                                Thông tin chuyển khoản
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-blue-800 mb-3">
                                <div><span class="font-medium">Ngân hàng:</span> Vietcombank - CN TP.HCM</div>
                                <div><span class="font-medium">Tên tài khoản:</span> CONG TY TNHH SHOWROOM</div>
                                <div><span class="font-medium">Số tài khoản:</span> <span class="font-mono">0123456789</span></div>
                                <div><span class="font-medium">Nội dung:</span> <span class="font-mono"><?php echo e($order->order_number ?? ('#'.$order->id)); ?></span></div>
                            </div>
                            <div class="text-center p-2 bg-blue-100 rounded border border-blue-300">
                                <div class="text-xs text-blue-700 font-medium">Số tiền cần chuyển</div>
                                <div class="text-lg font-bold text-blue-900"><?php echo e(number_format($order->grand_total ?? $order->total_price, 0, ',', '.')); ?> đ</div>
                            </div>
                            <div class="mt-2 text-xs text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                Vui lòng chuyển khoản chính xác số tiền và nội dung để hệ thống đối soát tự động.
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if($order->finance_option_id && $order->financeOption): ?>
                        <!-- Finance Details Section -->
                        <div class="mt-4 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                            <div class="text-sm font-semibold text-indigo-900 mb-3 flex items-center gap-2">
                                <i class="fas fa-calculator"></i>
                                Chi tiết trả góp
                            </div>
                            <!-- Finance Provider Info -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-4">
                                <div>
                                    <div class="text-indigo-700 font-medium">Ngân hàng</div>
                                    <div class="text-indigo-900"><?php echo e($order->financeOption->bank_name); ?></div>
                                </div>
                                <div>
                                    <div class="text-indigo-700 font-medium">Lãi suất</div>
                                    <div class="text-indigo-900"><?php echo e($order->financeOption->interest_rate); ?>%/năm</div>
                                </div>
                            </div>

                            <!-- Finance Amount Breakdown -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm mb-4">
                                <div class="text-center p-3 bg-white rounded-lg border border-indigo-100">
                                    <div class="text-indigo-700 font-medium text-xs mb-1">Trả trước</div>
                                    <div class="text-indigo-900 font-bold text-lg"><?php echo e(number_format($order->down_payment_amount ?? 0, 0, ',', '.')); ?> đ</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg border border-indigo-100">
                                    <div class="text-indigo-700 font-medium text-xs mb-1">Số tiền vay</div>
                                    <?php
                                        $financeableAmount = ($order->subtotal ?? $order->total_price) - ($order->discount_total ?? 0);
                                        $loanAmount = $financeableAmount - ($order->down_payment_amount ?? 0);
                                    ?>
                                    <div class="text-indigo-900 font-bold text-lg"><?php echo e(number_format($loanAmount, 0, ',', '.')); ?> đ</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg border border-indigo-100">
                                    <div class="text-indigo-700 font-medium text-xs mb-1">Trả hàng tháng</div>
                                    <div class="text-indigo-900 font-bold text-lg"><?php echo e(number_format($order->monthly_payment_amount ?? 0, 0, ',', '.')); ?> đ</div>
                                </div>
                            </div>

                            <!-- Tenure Info -->
                            <div class="text-center mb-4">
                                <div class="text-indigo-700 font-medium text-sm">Thời hạn vay</div>
                                <div class="text-indigo-900 font-semibold text-lg"><?php echo e($order->tenure_months ?? 0); ?> tháng</div>
                            </div>
                            
                            <!-- Additional Costs Info -->
                            <?php if($order->tax_total > 0 || $order->shipping_fee > 0 || $order->payment_fee > 0): ?>
                            <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 mb-3">
                                <div class="text-xs text-amber-800 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span class="font-medium">Lưu ý về chi phí bổ sung:</span>
                                </div>
                                <div class="text-xs text-amber-700 space-y-1">
                                    <?php if($order->tax_total > 0): ?>
                                    <div>• Thuế: <?php echo e(number_format($order->tax_total, 0, ',', '.')); ?> đ (thanh toán riêng)</div>
                                    <?php endif; ?>
                                    <?php if($order->shipping_fee > 0): ?>
                                    <div>• Phí vận chuyển: <?php echo e(number_format($order->shipping_fee, 0, ',', '.')); ?> đ (thanh toán riêng)</div>
                                    <?php endif; ?>
                                    <?php if($order->payment_fee > 0): ?>
                                    <div>• Phí thanh toán: <?php echo e(number_format($order->payment_fee, 0, ',', '.')); ?> đ (thanh toán riêng)</div>
                                    <?php endif; ?>
                                    <div class="mt-1 font-medium">→ Trả góp chỉ áp dụng cho giá trị sản phẩm</div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if(session('payment_method') === 'bank_transfer' || $order->paymentMethod?->code === 'bank_transfer'): ?>
                            <!-- Bank Transfer Info for Finance -->
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="text-sm font-semibold text-blue-900 mb-2 flex items-center gap-2">
                                    <i class="fas fa-university"></i>
                                    Thông tin chuyển khoản (trả trước)
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-blue-800 mb-3">
                                    <div><span class="font-medium">Ngân hàng:</span> Vietcombank - CN TP.HCM</div>
                                    <div><span class="font-medium">Tên tài khoản:</span> CONG TY TNHH SHOWROOM</div>
                                    <div><span class="font-medium">Số tài khoản:</span> <span class="font-mono">0123456789</span></div>
                                    <div><span class="font-medium">Nội dung:</span> <span class="font-mono"><?php echo e($order->order_number ?? ('#'.$order->id)); ?></span></div>
                                </div>
                                <div class="text-center p-2 bg-blue-100 rounded border border-blue-300">
                                    <div class="text-xs text-blue-700 font-medium">Số tiền cần chuyển</div>
                                    <div class="text-lg font-bold text-blue-900"><?php echo e(number_format($order->down_payment_amount ?? 0, 0, ',', '.')); ?> đ</div>
                                    <div class="text-xs text-blue-600">(Khoản trả trước)</div>
                                </div>
                                <div class="mt-2 text-xs text-blue-700">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Chuyển khoản chính xác số tiền và nội dung để hệ thống đối soát tự động.
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="mt-3 p-3 bg-white rounded-lg border border-indigo-100">
                                <div class="text-xs text-indigo-700 flex items-start gap-2">
                                    <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                                    <div>
                                        <div class="font-medium mb-1">Lưu ý quan trọng:</div>
                                        <ul class="space-y-1">
                                            <?php if(session('payment_method') === 'bank_transfer' || $order->paymentMethod?->code === 'bank_transfer'): ?>
                                            <li>• Sau khi chuyển khoản, ngân hàng sẽ liên hệ để hoàn tất thủ tục vay</li>
                                            <?php else: ?>
                                            <li>• Bạn đã thanh toán khoản trả trước qua <?php echo e($order->paymentMethod->name ?? 'phương thức đã chọn'); ?></li>
                                            <?php endif; ?>
                                            <li>• Ngân hàng sẽ liên hệ để hoàn tất thủ tục vay trong 1-2 ngày làm việc</li>
                                            <li>• Vui lòng chuẩn bị đầy đủ hồ sơ theo yêu cầu của ngân hàng</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="rounded-xl border overflow-hidden">
                        <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-800">Tóm tắt đơn hàng</div>
                        <div class="px-4 py-4">
                            <?php $itemsCount = $order->items->sum('quantity'); ?>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <div class="text-gray-500">Mã đơn</div>
                                    <div class="font-semibold text-gray-900"><?php echo e($order->order_number ?? ('#'.$order->id)); ?></div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Sản phẩm</div>
                                    <div class="font-semibold text-gray-900"><?php echo e($itemsCount); ?></div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Tổng cộng</div>
                                    <div class="font-semibold text-gray-900 tabular-nums"><?php echo e(number_format($order->grand_total ?? $order->total_price, 0, ',', '.')); ?> đ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <aside class="md:col-span-2">
                    <div class="rounded-xl border overflow-hidden sticky top-4">
                        <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-800">Tổng kết</div>
                        <div class="px-4 py-4 space-y-3">
                            <?php
                                $ship = $order->shippingAddress ?: $order->billingAddress;
                                $recipientName = $ship?->contact_name
                                    ?? $ship?->full_name
                                    ?? $ship?->name
                                    ?? optional($order->user)->name
                                    ?? '';
                                $recipientPhone = $ship?->phone ?? optional($order->user)->phone ?? '';
                                $recipientEmail = optional($order->user)->email ?? '';
                            ?>
                            <div class="text-sm text-gray-700">
                                <div class="font-semibold mb-1">Người nhận</div>
                                <div class="font-medium"><?php echo e($recipientName !== '' ? $recipientName : (optional($order->user)->name ?? '—')); ?></div>
                                <div class="text-gray-500"><?php if($recipientPhone): ?> <?php echo e($recipientPhone); ?> <?php endif; ?> <?php if($recipientPhone && $recipientEmail): ?> • <?php endif; ?> <?php if($recipientEmail): ?> <?php echo e($recipientEmail); ?> <?php endif; ?></div>
                            </div>
                            <div class="text-sm text-gray-700">
                                <div class="font-semibold mb-1">Địa chỉ giao</div>
                                <?php if($ship): ?>
                                    <div class="space-y-1">
                                        <div><?php echo e($ship->address_line1 ?? $ship->address ?? ''); ?></div>
                                        <div class="text-gray-500"><?php echo e($ship->ward ?? ''); ?><?php if($ship?->ward && $ship?->district): ?>, <?php endif; ?><?php echo e($ship->district ?? ''); ?><?php if(($ship?->ward || $ship?->district) && $ship?->city): ?>, <?php endif; ?><?php echo e($ship->city ?? ''); ?></div>
                                    </div>
                                <?php else: ?>
                                    <div class="text-gray-500">Không có thông tin</div>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Tạm tính</span>
                                <span><?php echo e(number_format($order->subtotal ?? 0, 0, ',', '.')); ?> đ</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Thuế</span>
                                <span><?php echo e(number_format($order->tax_total ?? 0, 0, ',', '.')); ?> đ</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Vận chuyển
                                    <?php if($order->shipping_method): ?>
                                        <span class="text-xs text-gray-500 ml-1">
                                            (<?php echo e($order->shipping_method === 'express' ? 'Nhanh' : 'Tiêu chuẩn'); ?>)
                                        </span>
                                    <?php endif; ?>
                                    <?php if($order->promotion && $order->promotion->type === 'free_shipping'): ?>
                                        <span class="text-xs text-green-600 ml-1">(Miễn phí)</span>
                                    <?php endif; ?>
                                </span>
                                <?php if($order->promotion && $order->promotion->type === 'free_shipping'): ?>
                                    <span class="line-through text-gray-400"><?php echo e(number_format($order->shipping_fee ?? 0, 0, ',', '.')); ?> đ</span>
                                    <span class="text-green-600 font-medium ml-2">0 đ</span>
                                <?php else: ?>
                                    <span><?php echo e(number_format($order->shipping_fee ?? 0, 0, ',', '.')); ?> đ</span>
                                <?php endif; ?>
                            </div>
                            <?php if((float)($order->payment_fee ?? 0) > 0): ?>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Phí thanh toán
                                    <?php if($order->paymentMethod): ?>
                                        <span class="text-xs text-gray-500 ml-1">(<?php echo e($order->paymentMethod->name); ?>)</span>
                                    <?php endif; ?>
                                </span>
                                <span><?php echo e(number_format($order->payment_fee ?? 0, 0, ',', '.')); ?> đ</span>
                            </div>
                            <?php endif; ?>
                            <?php if((float)($order->discount_total ?? 0) > 0): ?>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Giảm giá
                                    <?php if($order->promotion): ?>
                                        <span class="text-xs text-gray-500 ml-1">
                                            (<?php echo e($order->promotion->code); ?>)
                                        </span>
                                    <?php endif; ?>
                                </span>
                                <span class="text-rose-600">-<?php echo e(number_format($order->discount_total ?? 0, 0, ',', '.')); ?> đ</span>
                            </div>
                            <?php endif; ?>
                            <div class="border-t pt-3 flex items-center justify-between text-base font-bold text-gray-900">
                                <?php if($order->finance_option_id): ?>
                                    <span>Đã thanh toán (trả trước)</span>
                                    <span><?php echo e(number_format($order->down_payment_amount ?? 0, 0, ',', '.')); ?> đ</span>
                                <?php else: ?>
                                    <span>Tổng cộng</span>
                                    <span><?php echo e(number_format($order->grand_total ?? $order->total_price, 0, ',', '.')); ?> đ</span>
                                <?php endif; ?>
                            </div>
                            <?php if($order->finance_option_id): ?>
                            <div class="flex items-center justify-between text-sm text-gray-600 mt-2">
                                <span>Còn lại (trả góp)</span>
                                <?php
                                    $financeableAmountRemaining = ($order->subtotal ?? $order->total_price) - ($order->discount_total ?? 0);
                                    $remainingAmount = $financeableAmountRemaining - ($order->down_payment_amount ?? 0);
                                ?>
                                <span><?php echo e(number_format($remainingAmount, 0, ',', '.')); ?> đ</span>
                            </div>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    <?php if(!empty($order->note)): ?>
                    <div class="mt-4 p-4 rounded-xl border bg-white">
                        <div class="text-sm font-semibold text-gray-700 mb-2">Ghi chú</div>
                        <div class="text-sm text-gray-700"><?php echo e($order->note); ?></div>
                    </div>
                    <?php endif; ?>
                </aside>
            </div>
            
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Show success message
  if (typeof window.showMessage === 'function') {
    var msg = <?php echo json_encode(session('success') ?? ''); ?> || 'Đặt hàng thành công!';
    if (msg) {
      window.showMessage(msg, 'success');
    }
  }
  
  // Force update cart count to 0 since order was successful
  if (typeof window.updateCartCount === 'function') {
    window.updateCartCount(0);
  }
  
  // Clear any cached cart data in localStorage/sessionStorage
  if (typeof Storage !== 'undefined') {
    localStorage.removeItem('cart_items');
    localStorage.removeItem('cart_count');
    sessionStorage.removeItem('cart_items');
    sessionStorage.removeItem('cart_count');
  }
  
  // Update cart badge immediately
  const cartBadges = document.querySelectorAll('.cart-count, [data-cart-count]');
  cartBadges.forEach(badge => {
    badge.textContent = '0';
    badge.style.display = 'none';
  });
  
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/cart/success.blade.php ENDPATH**/ ?>