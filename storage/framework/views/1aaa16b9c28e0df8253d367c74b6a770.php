<?php $__env->startSection('title', 'Chi tiết đơn hàng'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4 sm:mb-6">
        <div class="px-4 sm:px-6 py-4 border-b bg-gradient-to-r from-indigo-50 to-white">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <div class="text-xs text-gray-500">Mã đơn</div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-extrabold text-gray-900">#<?php echo e($order->order_number ?? $order->id); ?></h1>
                    <div class="mt-1 text-sm text-gray-500">Tạo lúc <?php echo e($order->created_at?->format('d/m/Y H:i')); ?></div>
                </div>
                <div class="text-right">
                    <?php if($order->finance_option_id): ?>
                        <!-- Finance Order Display -->
                        <div class="text-indigo-700 font-extrabold text-base sm:text-lg"><?php echo e(number_format($order->down_payment_amount ?? 0, 0, ',', '.')); ?> đ</div>
                        <div class="text-xs text-gray-500">Trả trước</div>
                        <?php if((float)($order->discount_total ?? 0) > 0): ?>
                            <div class="text-xs text-green-600 mt-1">
                                <i class="fas fa-tag mr-1"></i>Có khuyến mãi
                            </div>
                        <?php else: ?>
                            <div class="text-xs text-blue-600 mt-1">
                                <i class="fas fa-credit-card mr-1"></i><?php echo e($order->tenure_months ?? 0); ?> tháng
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Full Payment Display -->
                        <div class="text-indigo-700 font-extrabold text-base sm:text-lg"><?php echo e(number_format($order->grand_total, 0, ',', '.')); ?> đ</div>
                        <div class="text-xs text-gray-500">Tổng cộng</div>
                        <?php if((float)($order->discount_total ?? 0) > 0): ?>
                            <div class="text-xs text-green-600 mt-1">
                                <i class="fas fa-tag mr-1"></i>Có khuyến mãi
                            </div>
                        <?php else: ?>
                            <div class="text-xs text-emerald-600 mt-1">
                                <i class="fas fa-check-circle mr-1"></i>Thanh toán đầy đủ
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($order->status !== 'cancelled'): ?>
                        <?php
                            // Improved cancel logic with better edge case handling
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
                        <div class="mt-2 flex items-center gap-2">
                            <a href="<?php echo e(route('user.order.index')); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs">
                                <i class="fas fa-arrow-left"></i> Quay về
                            </a>
                            <?php if($order->status !== 'cancelled'): ?>
                                <form action="<?php echo e(route('user.orders.cancel', $order->id)); ?>" method="post" title="<?php echo e($cancelReason); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-500 text-white hover:bg-rose-600 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors duration-200" <?php echo e($canCancel ? '' : 'disabled'); ?>>
                                        <i class="fas fa-ban"></i> Hủy đơn
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-gray-500 bg-gray-100">
                                    <i class="fas fa-ban"></i> Đã hủy
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="mt-2 flex items-center gap-2">
                            <a href="<?php echo e(route('user.order.index')); ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs">
                                <i class="fas fa-arrow-left"></i> Quay về
                            </a>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs text-gray-500 bg-gray-100">
                                <i class="fas fa-ban"></i> Đã hủy
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
            $orderSteps = ['pending' => 'Đặt hàng', 'confirmed' => 'Xác nhận', 'shipping' => 'Vận chuyển', 'delivered' => 'Hoàn tất'];
            // Bỏ chữ "Đã" trong nhãn tiến trình
            $paySteps = ['pending' => 'Chờ thanh toán', 'processing' => 'Đang xử lý', 'completed' => 'Thanh toán'];
            $orderKeys = array_keys($orderSteps);
            $payKeys = array_keys($paySteps);
            
            // Xử lý logic hiển thị cho đơn hàng bị hủy
            if ($order->status === 'cancelled') {
                // Đơn hàng bị hủy: chỉ hiển thị bước đầu tiên với màu đỏ
                $orderIndex = -1; // Không có bước nào hoàn thành
                $payIndex = -1; // Không có bước thanh toán nào hoàn thành
            } else {
                $orderIndex = array_search($order->status, $orderKeys);
                if ($orderIndex === false) { $orderIndex = 0; }
                $payIndex = array_search($order->payment_status, $payKeys);
                if ($payIndex === false) { $payIndex = 0; }
            }
        ?>
        <div class="px-4 sm:px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-gray-800">Tiến trình đơn hàng</div>
                </div>
                <div class="flex items-center gap-2">
                    <?php $__currentLoopData = $orderSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $i = array_search($key, $orderKeys); ?>
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold <?php echo e($order->status === 'cancelled' ? ($i === 0 ? 'bg-rose-500 text-white' : 'bg-gray-200 text-gray-500') : ($i <= $orderIndex ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500')); ?>"><?php echo e($i+1); ?></div>
                            <div class="text-xs sm:text-sm text-gray-700 truncate max-w-[90px] sm:max-w-none"><?php echo e($label); ?></div>
                        </div>
                        <?php if(!$loop->last): ?>
                            <div class="flex-1 h-1 rounded-full <?php echo e($order->status === 'cancelled' ? 'bg-gray-200' : ($i < $orderIndex ? 'bg-indigo-600' : 'bg-gray-200')); ?>"></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-gray-800">Tiến trình thanh toán</div>
                </div>
                <div class="flex items-center gap-2">
                    <?php $__currentLoopData = $paySteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $j = array_search($key, $payKeys); ?>
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold <?php echo e($order->status === 'cancelled' ? ($j === 0 ? 'bg-rose-500 text-white' : 'bg-gray-200 text-gray-500') : (in_array($order->payment_status, ['failed','cancelled']) ? 'bg-rose-100 text-rose-700' : ($j <= $payIndex ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500'))); ?>"><?php echo e($j+1); ?></div>
                            <div class="text-xs sm:text-sm text-gray-700 truncate max-w-[110px] sm:max-w-none"><?php echo e($label); ?></div>
                        </div>
                        <?php if(!$loop->last): ?>
                            <div class="flex-1 h-1 rounded-full <?php echo e($order->status === 'cancelled' ? 'bg-gray-200' : (in_array($order->payment_status, ['failed','cancelled']) ? 'bg-rose-100' : ($j < $payIndex ? 'bg-emerald-600' : 'bg-gray-200'))); ?>"></div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php if(in_array($order->payment_status, ['failed','cancelled']) && $order->status !== 'cancelled'): ?>
                <div class="mt-2 text-xs text-rose-600"><i class="fas fa-exclamation-circle mr-1"></i> Thanh toán không thành công</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            <!-- Trạng thái -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-bold">Trạng thái</h2>
                    <div class="text-sm text-gray-500">Tạo lúc <?php echo e($order->created_at?->format('d/m/Y H:i')); ?></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Cột trái: Thông tin cơ bản -->
                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Mã đơn</dt>
                            <dd class="font-medium text-gray-900">#<?php echo e($order->order_number ?? $order->id); ?></dd>
                        </div>
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Loại thanh toán</dt>
                            <dd class="font-medium text-gray-900"><?php echo e($order->payment_type_display); ?></dd>
                        </div>
                        <?php if($order->financeOption): ?>
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Gói trả góp</dt>
                            <dd class="font-medium text-gray-900"><?php echo e($order->financeOption->name); ?></dd>
                        </div>
                        <?php else: ?>
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Số sản phẩm</dt>
                            <dd class="font-medium text-gray-900"><?php echo e($order->items->count()); ?> sản phẩm</dd>
                        </div>
                        <?php endif; ?>
                    </dl>
                    
                    <!-- Cột phải: Phương thức và Trạng thái -->
                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between sm:justify-start sm:gap-3">
                            <dt class="text-gray-500">Phương thức</dt>
                            <dd class="font-medium text-gray-900"><?php echo e($order->paymentMethod->name ?? '—'); ?></dd>
                        </div>
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
                            <dt class="text-gray-500">Thanh toán</dt>
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
                    </dl>
                </div>
                
                <?php if(!$order->finance_option_id): ?>
                <!-- Payment Type Info for Full Payment -->
                <div class="mt-4 p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center mt-0.5">
                            <i class="fas fa-check text-emerald-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-emerald-800 mb-1">Thanh toán một lần</h4>
                            <p class="text-sm text-emerald-700">Đơn hàng này được thanh toán toàn bộ một lần, không có lịch trả góp.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($order->paymentMethod && in_array($order->paymentMethod->code, ['bank_transfer']) && !$order->finance_option_id): ?>
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
                            <div class="text-indigo-900 font-bold text-lg"><?php echo e(number_format(($order->subtotal ?? $order->total_price) - ($order->down_payment_amount ?? 0), 0, ',', '.')); ?> đ</div>
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
                    <?php if($order->tax_total > 0 || $order->shipping_fee > 0): ?>
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
                            <div class="mt-1 font-medium">→ Trả góp chỉ áp dụng cho giá trị sản phẩm</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if($order->paymentMethod && in_array($order->paymentMethod->code, ['bank_transfer'])): ?>
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
                                    <?php if($order->paymentMethod && in_array($order->paymentMethod->code, ['bank_transfer'])): ?>
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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-4 sm:px-6 py-4 border-b flex items-center justify-between gap-2">
                    <h2 class="text-lg font-bold">Thông tin đơn hàng</h2>
                    <div class="text-sm text-gray-500">Sản phẩm (<?php echo e($order->items->count()); ?>)</div>
                </div>
                <div class="divide-y">
                    <?php $__empty_1 = true; $__currentLoopData = $order->items->sortBy(function($it){ return $it->item_type === 'car_variant' ? 0 : 1; }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $model = $it->item;
                            $unit = $it->price;
                            $line = $it->line_total ?: ($unit * $it->quantity);
                            $meta = is_array($it->item_metadata) ? $it->item_metadata : (json_decode($it->item_metadata ?? 'null', true) ?: []);
                            $img = null;
                            if ($it->item_type === 'car_variant' && $model?->images?->isNotEmpty()) {
                                $f = $model->images->first();
                                $img = $f->image_url ?: ($f->image_path ? asset('storage/'.$f->image_path) : null);
                            } elseif ($it->item_type === 'accessory') {
                                $galleryRaw = $model->gallery ?? null;
                                $gallery = is_array($galleryRaw) ? $galleryRaw : (json_decode($galleryRaw ?? '[]', true) ?: []);
                                $firstGalleryImg = $gallery[0] ?? null;
                                if ($firstGalleryImg) {
                                    $img = $firstGalleryImg;
                                } elseif (!empty($model->image_url)) {
                                    $img = filter_var($model->image_url, FILTER_VALIDATE_URL) ? $model->image_url : asset('storage/'.$model->image_url);
                                } else {
                                    $img = asset('images/default-accessory.jpg');
                                }
                            }
                        ?>
                        <div class="px-4 py-3 flex items-center gap-3 flex-wrap">
                            <div class="w-16 h-12 rounded-md bg-gray-100 overflow-hidden flex-shrink-0">
                                <?php if($img): ?>
                                    <img src="<?php echo e($img); ?>" class="w-full h-full object-cover" alt="<?php echo e($model?->name ?? $it->item_name); ?>" />
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-[11px]">No image</div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-900 line-clamp-2" title="<?php echo e($model?->name ?? $it->item_name); ?>"><?php echo e($model?->name ?? $it->item_name); ?></div>
                                <?php if($it->item_type === 'car_variant'): ?>
                                    <div class="text-[11px] text-gray-500 whitespace-normal break-words">
                                        <?php 
                                            $colorName = $it->color?->color_name;
                                            $colorHex = $colorName ? \App\Helpers\ColorHelper::getColorHex($colorName) : null;
                                        ?>
                                        SL: <?php echo e($it->quantity); ?>

                                        <span>•</span>
                                        <span class="inline-flex items-center gap-1">
                                            <span>Màu:</span>
                                            <?php if($colorName): ?>
                                                <span class="inline-flex items-center gap-1">
                                                    <span class="inline-block w-3 h-3 rounded-full border border-gray-200 bg-gray-200"></span>
                                                    <span class="text-gray-700"><?php echo e($colorName); ?></span>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400">Chưa chọn</span>
                                            <?php endif; ?>
                                        </span>
                                        <?php $featureNames = $meta['feature_names'] ?? []; ?>
                                        <?php if(!empty($featureNames)): ?>
                                            <div class="mt-1 space-y-1">
                                                <div class="text-[11px] text-gray-600">Tùy chọn:
                                                    <?php $__currentLoopData = $featureNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="inline-flex items-center gap-1 mr-2"><?php echo e($fname); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php $optionNames = $meta['option_names'] ?? []; ?>
                                        <?php if(!empty($optionNames)): ?>
                                            <div class="mt-1 space-y-1">
                                                <div class="text-[11px] text-gray-600">Gói:
                                                    <?php $__currentLoopData = $optionNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oname): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="inline-flex items-center gap-1 mr-2"><?php echo e($oname); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-[11px] text-gray-500">SL: <?php echo e($it->quantity); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="text-right sm:shrink-0 sm:min-w-[140px]">
                                <div class="text-xs text-gray-500 whitespace-nowrap leading-none">Đơn giá</div>
                                <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none"><?php echo e(number_format($unit)); ?> đ</div>
                                <div class="text-xs text-gray-500 whitespace-nowrap leading-none mt-2">Tổng</div>
                                <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none"><?php echo e(number_format($line)); ?> đ</div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-6 text-center text-gray-500">Không có sản phẩm trong đơn hàng</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="space-y-4 sm:space-y-6">
            <!-- Tổng kết (giống success) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base font-bold mb-4">Tổng kết</h3>
                <?php
                    $ship = $order->shippingAddress ?: $order->billingAddress;
                    // Ưu tiên contact_name -> full_name -> name trên address; sau đó fallback về user->name
                    $recipientName = $ship?->contact_name
                        ?? $ship?->full_name
                        ?? $ship?->name
                        ?? optional($order->user)->name
                        ?? '';
                    $recipientPhone = $ship?->phone ?? optional($order->user)->phone ?? '';
                    $recipientEmail = optional($order->user)->email ?? '';
                ?>
                <div class="space-y-3 text-sm">
                    <div class="text-gray-700">
                        <div class="font-semibold mb-1">Người nhận</div>
                        <div class="font-medium"><?php echo e($recipientName !== '' ? $recipientName : (optional($order->user)->name ?? '—')); ?></div>
                        <div class="text-gray-500"><?php if($recipientPhone): ?> <?php echo e($recipientPhone); ?> <?php endif; ?> <?php if($recipientPhone && $recipientEmail): ?> • <?php endif; ?> <?php if($recipientEmail): ?> <?php echo e($recipientEmail); ?> <?php endif; ?></div>
                    </div>
                    <div class="text-gray-700">
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
                </div>
                <div class="mt-4 border-t pt-4 space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Tạm tính</span>
                        <span class="text-gray-900 font-medium"><?php echo e(number_format($order->subtotal ?? 0, 0, ',', '.')); ?> đ</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thuế (<?php echo e(number_format(($order->tax_rate ?? 0.1) * 100, 1)); ?>%)</span>
                        <span class="text-gray-900 font-medium"><?php echo e(number_format($order->tax_total ?? 0, 0, ',', '.')); ?> đ</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">
                            Vận chuyển
                            <?php if($order->shipping_method): ?>
                                <span class="text-xs text-blue-600 ml-1">
                                    (<?php echo e($order->shipping_method === 'express' ? 'Nhanh' : ($order->shipping_method === 'standard' ? 'Tiêu chuẩn' : ucfirst($order->shipping_method))); ?>)
                                </span>
                            <?php endif; ?>
                        </span>
                        <span class="text-gray-900 font-medium"><?php echo e(number_format($order->shipping_fee ?? 0, 0, ',', '.')); ?> đ</span>
                    </div>
                    <?php if((float)($order->discount_total ?? 0) > 0): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">
                            Giảm giá
                            <?php if($order->promotion): ?>
                                <span class="text-xs text-green-600 ml-1">(<?php echo e($order->promotion->code); ?>)</span>
                            <?php endif; ?>
                        </span>
                        <span class="text-rose-600 font-medium">-<?php echo e(number_format($order->discount_total ?? 0, 0, ',', '.')); ?> đ</span>
                    </div>
                    <?php endif; ?>
                    <?php if($order->financeOption && $order->down_payment_amount): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Trả trước</span>
                        <span class="text-gray-900 font-medium"><?php echo e(number_format($order->down_payment_amount, 0, ',', '.')); ?> đ</span>
                    </div>
                    <?php if($order->monthly_payment_amount && $order->tenure_months): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Trả góp</span>
                        <span class="text-gray-900 font-medium"><?php echo e(number_format($order->monthly_payment_amount, 0, ',', '.')); ?> đ/tháng × <?php echo e($order->tenure_months); ?> tháng</span>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <div class="pt-2 mt-2 border-t flex items-center justify-between">
                        <span class="text-gray-700 font-semibold">Tổng cộng</span>
                        <span class="text-indigo-700 font-extrabold text-lg"><?php echo e(number_format($order->grand_total ?? $order->total_price, 0, ',', '.')); ?> đ</span>
                    </div>
                    
                </div>
            </div>

            <!-- Promotion Details Section -->
            <?php if($order->promotion): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-tag text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Khuyến mãi đã áp dụng</h3>
                        <p class="text-sm text-gray-600">Thông tin chi tiết về ưu đãi</p>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?php echo e($order->promotion->code); ?>

                                </span>
                                <span class="text-xs text-gray-500">
                                    <?php switch($order->promotion->type):
                                        case ('percentage'): ?>
                                            Giảm theo %
                                            <?php break; ?>
                                        <?php case ('fixed_amount'): ?>
                                            Giảm cố định
                                            <?php break; ?>
                                        <?php case ('free_shipping'): ?>
                                            Miễn phí ship
                                            <?php break; ?>
                                        <?php case ('brand_specific'): ?>
                                            Theo thương hiệu
                                            <?php break; ?>
                                        <?php case ('category_specific'): ?>
                                            Theo danh mục
                                            <?php break; ?>
                                        <?php case ('buy_x_get_y'): ?>
                                            Mua X tặng Y
                                            <?php break; ?>
                                        <?php case ('bundle_discount'): ?>
                                            Combo giảm giá
                                            <?php break; ?>
                                        <?php case ('tiered_discount'): ?>
                                            Giảm theo bậc
                                            <?php break; ?>
                                        <?php case ('time_based'): ?>
                                            Flash Sale
                                            <?php break; ?>
                                        <?php default: ?>
                                            <?php echo e(ucfirst($order->promotion->type)); ?>

                                    <?php endswitch; ?>
                                </span>
                            </div>
                            <h4 class="font-semibold text-green-900 mb-1"><?php echo e($order->promotion->name); ?></h4>
                            <p class="text-sm text-green-700"><?php echo e($order->promotion->description); ?></p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-900">-<?php echo e(number_format($order->discount_total, 0, ',', '.')); ?> đ</div>
                            <div class="text-xs text-green-600">Đã tiết kiệm</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($order->note): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h3 class="text-base font-bold mb-2">Ghi chú</h3>
                <p class="text-sm text-gray-700"><?php echo e($order->note); ?></p>
            </div>
            <?php endif; ?>

            <!-- Refund Section -->
            <?php if($order->payment_status === 'completed' && $order->status !== 'cancelled'): ?>
                <?php
                    $existingRefund = $order->refunds->whereIn('status', ['pending', 'processing'])->first();
                    $canRequestRefund = !$existingRefund && $order->created_at->diffInDays(now()) <= 30; // 30 days refund policy
                ?>
                
                <?php if($existingRefund): ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                        <h3 class="text-base font-bold mb-4">Yêu cầu hoàn tiền</h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-clock text-yellow-600 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-yellow-800">Đang xử lý yêu cầu hoàn tiền</h4>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        Số tiền: <span class="font-medium"><?php echo e(number_format($existingRefund->amount, 0, ',', '.')); ?> đ</span>
                                    </p>
                                    <p class="text-sm text-yellow-700">
                                        Lý do: <?php echo e($existingRefund->reason); ?>

                                    </p>
                                    <p class="text-xs text-yellow-600 mt-2">
                                        Yêu cầu từ <?php echo e($existingRefund->created_at->format('d/m/Y H:i')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif($canRequestRefund): ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold">Yêu cầu hoàn tiền</h3>
                            <span class="text-xs text-gray-500">Trong vòng 30 ngày</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">
                            Nếu bạn không hài lòng với đơn hàng, bạn có thể yêu cầu hoàn tiền trong vòng 30 ngày kể từ ngày đặt hàng.
                        </p>
                        <button onclick="openRefundModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">
                            <i class="fas fa-undo"></i> Yêu cầu hoàn tiền
                        </button>
                    </div>
                <?php endif; ?>
            <?php endif; ?>


            
            
            
            
            
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeRefundModal()">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Yêu cầu hoàn tiền</h3>
                <button type="button" onclick="closeRefundModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="<?php echo e(route('user.orders.refund', $order)); ?>" method="POST" id="refundForm">
                <?php echo csrf_field(); ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số tiền hoàn (VND)</label>
                        <input type="number" name="amount" id="refundAmount"
                               value="<?php echo e(intval($order->grand_total)); ?>"
                               class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                               placeholder="Nhập số tiền hoàn">
                        <p class="text-xs text-gray-500 mt-1">Tối đa: <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?> đ</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lý do hoàn tiền</label>
                        <textarea name="reason" id="refundReason" rows="4"
                                  class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500"
                                  placeholder="Vui lòng mô tả lý do bạn muốn hoàn tiền..."></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeRefundModal()" 
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 font-medium">
                        Gửi yêu cầu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Handle cancel order form with confirm dialog (same as orders index page)
document.addEventListener('click', function(e) {
    if (e.target.closest('form[action*="/cancel"]')) {
        e.preventDefault();
        const form = e.target.closest('form');
        const button = form.querySelector('button[type="submit"]');
        
        const orderNumber = '#<?php echo e($order->order_number ?? $order->id); ?>';
        const orderAmount = '<?php echo e(number_format($order->grand_total, 0, ",", ".")); ?> đ';
        
        // Enhanced confirm dialog with more details
        let confirmMessage = `Bạn có chắc chắn muốn hủy đơn hàng ${orderNumber}?\n\nGiá trị đơn hàng: ${orderAmount}`;
        
        <?php if($order->finance_option_id): ?>
        confirmMessage += `\nLưu ý: Nếu đã thanh toán trả trước, bạn có thể yêu cầu hoàn tiền sau khi hủy.`;
        <?php endif; ?>
        
        confirmMessage += `\n\nHành động này không thể hoàn tác.`;
        
        showConfirmDialog(
            'Xác nhận hủy đơn hàng',
            confirmMessage,
            'Xác nhận hủy',
            'Không hủy',
            () => {
                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Đang hủy...';
                
                // Submit the form and reload page after success
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Parse JSON response
                        return response.json().then(data => {
                            if (data.success) {
                                // Show success message
                                if (typeof window.showMessage === 'function') {
                                    window.showMessage(data.message || 'Đã hủy đơn hàng thành công', 'success');
                                }
                                
                                // Reload page to show updated status
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                throw new Error(data.message || 'Failed to cancel order');
                            }
                        });
                    } else {
                        // Handle different error status codes
                        if (response.status === 403) {
                            throw new Error('Bạn không có quyền hủy đơn hàng này');
                        } else if (response.status === 422) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Đơn hàng không thể hủy ở trạng thái hiện tại');
                            });
                        } else {
                            throw new Error('Có lỗi xảy ra khi hủy đơn hàng');
                        }
                    }
                })
                .catch(error => {
                    console.error('Cancel order error:', error);
                    if (typeof window.showMessage === 'function') {
                        window.showMessage(error.message || 'Có lỗi xảy ra khi hủy đơn hàng', 'error');
                    } else {
                        alert(error.message || 'Có lỗi xảy ra khi hủy đơn hàng');
                    }
                    
                    // Reset button
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-ban"></i> Hủy đơn';
                });
            }
        );
    }
});

// Confirm dialog function (same as orders index page)
function showConfirmDialog(title, message, confirmText, cancelText, onConfirm){
    const existing = document.querySelector('.fast-confirm-dialog');
    if (existing) existing.remove();
    const wrapper = document.createElement('div');
    wrapper.className = 'fast-confirm-dialog fixed inset-0 z-[100000] bg-black/50 backdrop-blur-sm flex items-center justify-center p-4';
    wrapper.innerHTML = `
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-200 scale-95 opacity-0">
            <div class="p-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">${title}</h3>
                <p class="text-gray-600 text-center mb-6">${message}</p>
                <div class="flex space-x-3">
                    <button class="fast-cancel flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200">${cancelText}</button>
                    <button class="fast-confirm flex-1 px-4 py-2.5 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium transition-colors duration-200">${confirmText}</button>
                </div>
            </div>
        </div>`;
    document.body.appendChild(wrapper);
    const panel = wrapper.firstElementChild;
    
    // Animate in
    requestAnimationFrame(() => {
        panel.style.transform = 'scale(1)';
        panel.style.opacity = '1';
    });
    
    // Handle clicks
    wrapper.querySelector('.fast-cancel').addEventListener('click', () => {
        wrapper.remove();
    });
    
    wrapper.querySelector('.fast-confirm').addEventListener('click', () => {
        wrapper.remove();
        onConfirm();
    });
    
    // Close on backdrop click
    wrapper.addEventListener('click', (e) => {
        if (e.target === wrapper) {
            wrapper.remove();
        }
    });
}

// Refund modal functions
function openRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('refundAmount').focus();
    }, 100);
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
    document.getElementById('refundForm').reset();
    document.getElementById('refundAmount').value = '<?php echo e(intval($order->grand_total)); ?>';
}

// Handle ESC key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('refundModal');
        if (!modal.classList.contains('hidden')) {
            closeRefundModal();
        }
    }
});

// Handle refund form submission
document.getElementById('refundForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    const amountInput = form.querySelector('#refundAmount');
    const reasonInput = form.querySelector('#refundReason');
    
    // Client-side validation with Vietnamese toast messages
    const amountValue = parseFloat(amountInput.value);
    const reasonValue = reasonInput.value.trim();
    const maxAmount = parseInt('<?php echo e($order->grand_total); ?>');
    
    if (!amountValue || amountValue <= 0) {
        if (typeof window.showMessage === 'function') {
            window.showMessage('Vui lòng nhập số tiền hoàn hợp lệ', 'error');
        } else {
            alert('Vui lòng nhập số tiền hoàn hợp lệ');
        }
        amountInput.focus();
        return;
    }
    
    if (amountValue > maxAmount) {
        if (typeof window.showMessage === 'function') {
            window.showMessage('Số tiền hoàn không được vượt quá ' + maxAmount.toLocaleString('vi-VN') + ' đ', 'error');
        } else {
            alert('Số tiền hoàn không được vượt quá ' + maxAmount.toLocaleString('vi-VN') + ' đ');
        }
        amountInput.focus();
        return;
    }
    
    if (!reasonValue) {
        if (typeof window.showMessage === 'function') {
            window.showMessage('Vui lòng nhập lý do hoàn tiền', 'error');
        } else {
            alert('Vui lòng nhập lý do hoàn tiền');
        }
        reasonInput.focus();
        return;
    }
    
    if (reasonValue.length < 10) {
        if (typeof window.showMessage === 'function') {
            window.showMessage('Lý do hoàn tiền phải có ít nhất 10 ký tự', 'error');
        } else {
            alert('Lý do hoàn tiền phải có ít nhất 10 ký tự');
        }
        reasonInput.focus();
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...';
    
    // Use FormData from form directly
    const formData = new FormData(form);
    
    // Submit form
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show Vietnamese success message
            const successMessage = data.message || 'Yêu cầu hoàn tiền đã được gửi thành công!';
            if (typeof window.showMessage === 'function') {
                window.showMessage(successMessage, 'success');
            } else {
                alert(successMessage);
            }
            
            // Close modal immediately
            closeRefundModal();
            
            // Reload page to show updated status
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra khi gửi yêu cầu hoàn tiền');
        }
    })
    .catch(error => {
        console.error('Refund request error:', error);
        if (typeof window.showMessage === 'function') {
            window.showMessage(error.message || 'Có lỗi xảy ra khi gửi yêu cầu hoàn tiền', 'error');
        } else {
            alert(error.message || 'Có lỗi xảy ra khi gửi yêu cầu hoàn tiền');
        }
        
        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Modal close functionality is now handled by onclick attributes in HTML
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/orders/show.blade.php ENDPATH**/ ?>