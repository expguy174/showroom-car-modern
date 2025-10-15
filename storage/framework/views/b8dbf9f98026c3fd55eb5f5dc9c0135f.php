<div class="space-y-4">
    <?php if($logs->isEmpty()): ?>
        <p class="text-gray-500 text-center py-8">Chưa có lịch sử hoạt động</p>
    <?php else: ?>
        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex-shrink-0">
                <?php if($log->action == 'order_created'): ?>
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-plus text-green-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'status_changed'): ?>
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-right text-blue-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'order_cancelled'): ?>
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-times text-red-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'payment_pending'): ?>
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'payment_status_changed'): ?>
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-credit-card text-emerald-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'payment_completed'): ?>
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'payment_failed'): ?>
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation text-yellow-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'tracking_updated'): ?>
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-shipping-fast text-indigo-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'note_updated'): ?>
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-edit text-purple-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'payment_refunded'): ?>
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-undo text-orange-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'refund_status_updated'): ?>
                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-indigo-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'installments_created'): ?>
                    <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-teal-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'installment_paid'): ?>
                    <div class="w-8 h-8 bg-cyan-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-money-check-alt text-cyan-600 text-sm"></i>
                    </div>
                <?php elseif($log->action == 'installment_completed'): ?>
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-trophy text-emerald-600 text-sm"></i>
                    </div>
                <?php else: ?>
                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-info text-gray-600 text-sm"></i>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-1">
                    <h4 class="text-sm font-medium text-gray-900">
                        <?php if($log->action == 'order_created'): ?>
                            Tạo đơn hàng
                        <?php elseif($log->action == 'order_updated'): ?>
                            Cập nhật đơn hàng
                        <?php elseif($log->action == 'status_changed'): ?>
                            Chuyển trạng thái đơn hàng
                        <?php elseif($log->action == 'order_cancelled'): ?>
                            Hủy đơn hàng
                        <?php elseif($log->action == 'payment_pending'): ?>
                            Chờ thanh toán
                        <?php elseif($log->action == 'payment_status_changed'): ?>
                            Cập nhật trạng thái thanh toán
                        <?php elseif($log->action == 'payment_completed'): ?>
                            Thanh toán thành công
                        <?php elseif($log->action == 'payment_failed'): ?>
                            Thanh toán thất bại
                        <?php elseif($log->action == 'tracking_updated'): ?>
                            Cập nhật mã vận đơn
                        <?php elseif($log->action == 'note_updated'): ?>
                            Cập nhật ghi chú
                        <?php elseif($log->action == 'payment_refunded'): ?>
                            Hoàn tiền
                        <?php elseif($log->action == 'refund_status_updated'): ?>
                            Cập nhật trạng thái hoàn tiền
                        <?php elseif($log->action == 'installments_created'): ?>
                            Tạo lịch trả góp
                        <?php elseif($log->action == 'installment_paid'): ?>
                            Thanh toán kỳ trả góp
                        <?php elseif($log->action == 'installment_completed'): ?>
                            Hoàn thành trả góp
                        <?php else: ?>
                            <?php echo e(ucfirst(str_replace('_', ' ', $log->action))); ?>

                        <?php endif; ?>
                    </h4>
                    <span class="text-xs text-gray-500"><?php echo e($log->created_at->format('d/m/Y H:i')); ?></span>
                </div>
                
                <?php if($log->message): ?>
                <p class="text-sm text-gray-600"><?php echo e($log->message); ?></p>
                <?php endif; ?>
                
                <?php if($log->details && is_array($log->details)): ?>
                <div class="text-xs text-gray-600 mt-2">
                    <?php if(isset($log->details['from']) && isset($log->details['to'])): ?>
                        <?php
                            // Translations for both order status and payment status
                            $orderStatusTranslations = [
                                'pending' => 'Chờ xử lý',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đang giao',
                                'delivered' => 'Đã giao',
                                'cancelled' => 'Đã hủy'
                            ];
                            $paymentStatusTranslations = [
                                'pending' => 'Chờ thanh toán',
                                'completed' => 'Đã thanh toán',
                                'partial' => 'Thanh toán một phần',
                                'failed' => 'Thất bại',
                                'refunded' => 'Đã hoàn tiền'
                            ];
                            
                            // Use payment translations if this is a payment-related action
                            $translations = in_array($log->action, ['payment_status_changed', 'payment_refunded'])
                                ? $paymentStatusTranslations 
                                : $orderStatusTranslations;
                            
                            $fromText = $translations[$log->details['from']] ?? $log->details['from'];
                            $toText = $translations[$log->details['to']] ?? $log->details['to'];
                        ?>
                        <span class="text-gray-500">Từ:</span> <span class="font-medium"><?php echo e($fromText); ?></span>
                        <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                        <span class="text-gray-500">Đến:</span> <span class="font-medium"><?php echo e($toText); ?></span>
                        
                        <?php if($log->action === 'payment_status_changed' && isset($log->details['paid_at'])): ?>
                        <div class="mt-1 text-gray-500">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Thanh toán lúc: <?php echo e(\Carbon\Carbon::parse($log->details['paid_at'])->format('d/m/Y H:i')); ?>

                        </div>
                        <?php endif; ?>
                    <?php elseif(isset($log->details['status'])): ?>
                        <?php
                            $statusTranslations = [
                                'pending' => 'Chờ xử lý',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đang giao',
                                'delivered' => 'Đã giao',
                                'cancelled' => 'Đã hủy'
                            ];
                            $statusText = $statusTranslations[$log->details['status']] ?? $log->details['status'];
                        ?>
                        <span class="text-gray-500">Trạng thái:</span> <span class="font-medium"><?php echo e($statusText); ?></span>
                    <?php endif; ?>
                    
                    <?php if(isset($log->details['reason'])): ?>
                    <div class="mt-2 p-2 bg-amber-50 border border-amber-200 rounded">
                        <span class="text-gray-700 font-medium">Lý do:</span>
                        <p class="text-gray-600 mt-1"><?php echo e($log->details['reason']); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($log->action === 'payment_refunded' && isset($log->details['refund_amount'])): ?>
                    <div class="mt-2 p-2 bg-orange-50 border border-orange-200 rounded">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Số tiền hoàn:</span>
                            <span class="text-orange-600 font-bold"><?php echo e(number_format($log->details['refund_amount'], 0, ',', '.')); ?> VNĐ</span>
                        </div>
                        <?php if(isset($log->details['refund_type'])): ?>
                        <div class="mt-1 text-xs">
                            <span class="text-gray-600">Loại:</span>
                            <span class="font-medium"><?php echo e($log->details['refund_type'] === 'full' ? 'Hoàn toàn bộ' : 'Hoàn một phần'); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($log->action === 'refund_status_updated'): ?>
                    <div class="mt-2 p-2 bg-indigo-50 border border-indigo-200 rounded">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Trạng thái:</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600"><?php echo e($log->details['from_label'] ?? $log->details['from_status']); ?></span>
                                <i class="fas fa-arrow-right text-gray-400 text-xs"></i>
                                <span class="text-sm font-medium text-indigo-600"><?php echo e($log->details['to_label'] ?? $log->details['to_status']); ?></span>
                            </div>
                        </div>
                        <?php if(isset($log->details['amount'])): ?>
                        <div class="mt-1 flex justify-between items-center">
                            <span class="text-gray-700 font-medium">Số tiền:</span>
                            <span class="text-indigo-600 font-bold"><?php echo e(number_format($log->details['amount'], 0, ',', '.')); ?> VNĐ</span>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($log->details['admin_notes']) && $log->details['admin_notes']): ?>
                        <div class="mt-1">
                            <span class="text-gray-600 text-xs">Ghi chú:</span>
                            <p class="text-gray-700 text-sm mt-1"><?php echo e($log->details['admin_notes']); ?></p>
                        </div>
                        <?php endif; ?>
                        <?php if(isset($log->details['admin_name'])): ?>
                        <div class="mt-1 text-xs text-gray-500">
                            Bởi: <?php echo e($log->details['admin_name']); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($log->action === 'installments_created' && isset($log->details['total_installments'])): ?>
                    <div class="mt-2 p-3 bg-teal-50 border border-teal-200 rounded">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1">
                                <span class="text-gray-600">Số kỳ:</span>
                                <span class="font-medium"><?php echo e($log->details['total_installments']); ?> tháng</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-gray-600">Hàng tháng:</span>
                                <span class="font-medium text-teal-600"><?php echo e(number_format($log->details['monthly_amount'], 0, ',', '.')); ?> VNĐ</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if(($log->action === 'installment_paid' || $log->action === 'installment_completed') && isset($log->details['installment_number'])): ?>
                    <div class="mt-2 p-3 bg-cyan-50 border border-cyan-200 rounded">
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Kỳ <?php echo e($log->details['installment_number']); ?>

                                </span>
                                <?php if(isset($log->details['amount'])): ?>
                                <span class="font-medium text-cyan-700"><?php echo e(number_format($log->details['amount'], 0, ',', '.')); ?> VNĐ</span>
                                <?php endif; ?>
                            </div>
                            <?php if($log->action === 'installment_completed'): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                <i class="fas fa-trophy mr-1"></i> Hoàn thành toàn bộ
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    
                    <?php if($log->action === 'order_created' && isset($log->details['order_number'])): ?>
                    <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded">
                        <div class="text-xs space-y-1">
                            <?php if(isset($log->details['order_number'])): ?>
                            <div><span class="text-gray-600">Số đơn:</span> <span class="font-medium"><?php echo e($log->details['order_number']); ?></span></div>
                            <?php endif; ?>
                            <?php if(isset($log->details['grand_total'])): ?>
                            <div><span class="text-gray-600">Tổng tiền:</span> <span class="font-medium text-green-600"><?php echo e(number_format($log->details['grand_total'], 0, ',', '.')); ?> VNĐ</span></div>
                            <?php endif; ?>
                            <?php if(isset($log->details['payment_method_id'])): ?>
                            <?php
                                $paymentMethod = \App\Models\PaymentMethod::find($log->details['payment_method_id']);
                                $paymentMethodName = $paymentMethod ? $paymentMethod->name : 'ID ' . $log->details['payment_method_id'];
                            ?>
                            <div><span class="text-gray-600">Phương thức thanh toán:</span> <span class="font-medium"><?php echo e($paymentMethodName); ?></span></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
                <?php endif; ?>
                
                <?php
                    $performedBy = 'Hệ thống';
                    
                    if ($log->user) {
                        $userName = $log->user->userProfile->name ?? $log->user->email ?? ('User #' . $log->user->id);
                        $performedBy = $userName;
                    } elseif ($log->user_id && $order->user && $log->user_id == $order->user_id) {
                        $userName = $order->user->userProfile->name ?? $order->user->email ?? ('User #' . $order->user->id);
                        $performedBy = $userName . ' (Khách hàng)';
                    } elseif ($order->user && in_array($log->action, ['order_created', 'payment_pending'])) {
                        $userName = $order->user->userProfile->name ?? $order->user->email ?? ('User #' . $order->user->id);
                        $performedBy = $userName . ' (Khách hàng)';
                    }
                ?>
                <div class="text-xs text-gray-500 mt-2 space-y-1">
                    <p>
                        <i class="fas fa-user mr-1"></i>
                        Bởi: <?php echo e($performedBy); ?>

                    </p>
                    <?php if($log->ip_address): ?>
                    <p>
                        <i class="fas fa-globe mr-1"></i>
                        IP: <?php echo e($log->ip_address); ?>

                    </p>
                    <?php endif; ?>
                    <?php if($log->user_agent): ?>
                    <p class="break-all">
                        <i class="fas fa-desktop mr-1"></i>
                        Thiết bị: 
                        <?php
                            $userAgent = $log->user_agent;
                            // Extract browser and OS info from user agent
                            $browser = 'Unknown';
                            $os = 'Unknown';
                            
                            if (strpos($userAgent, 'Chrome') !== false) $browser = 'Chrome';
                            elseif (strpos($userAgent, 'Firefox') !== false) $browser = 'Firefox';
                            elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) $browser = 'Safari';
                            elseif (strpos($userAgent, 'Edge') !== false) $browser = 'Edge';
                            
                            if (strpos($userAgent, 'Windows') !== false) $os = 'Windows';
                            elseif (strpos($userAgent, 'Mac') !== false) $os = 'macOS';
                            elseif (strpos($userAgent, 'Linux') !== false) $os = 'Linux';
                            elseif (strpos($userAgent, 'Android') !== false) $os = 'Android';
                            elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) $os = 'iOS';
                        ?>
                        <span class="font-medium"><?php echo e($browser); ?> trên <?php echo e($os); ?></span>
                        <span class="text-gray-400 ml-2 text-xs">(<?php echo e(Str::limit($userAgent, 60)); ?>)</span>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</div>


<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/orders/partials/timeline.blade.php ENDPATH**/ ?>