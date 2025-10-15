<?php $__env->startSection('title', 'Chi tiết đơn hàng #' . ($order->order_number ?? $order->id)); ?>

<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginaldb1b157d84f8f63332f3508c9e385c0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.flash-messages','data' => ['showIcons' => true,'dismissible' => true,'position' => 'top-right','autoHide' => 5000]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show-icons' => true,'dismissible' => true,'position' => 'top-right','auto-hide' => 5000]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a)): ?>
<?php $attributes = $__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a; ?>
<?php unset($__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldb1b157d84f8f63332f3508c9e385c0a)): ?>
<?php $component = $__componentOriginaldb1b157d84f8f63332f3508c9e385c0a; ?>
<?php unset($__componentOriginaldb1b157d84f8f63332f3508c9e385c0a); ?>
<?php endif; ?>

<div class="space-y-6">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-shopping-cart text-blue-600 text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Đơn hàng #<?php echo e($order->order_number ?? $order->id); ?>

                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Tạo lúc <?php echo e($order->created_at->format('d/m/Y H:i')); ?>

                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-box text-blue-600 mr-3"></i>
                    Sản phẩm đã đặt (<?php echo e($order->items->count()); ?>)
                </h2>
                
                <div class="space-y-4">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        
                        <div class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                            <?php if(isset($item->item->image_url) && $item->item->image_url): ?>
                                <img src="<?php echo e(str_starts_with($item->item->image_url, 'http') ? $item->item->image_url : asset('storage/' . $item->item->image_url)); ?>" 
                                     alt="<?php echo e($item->item_name); ?>" 
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <div class="flex-1 min-w-0">
                            <?php
                                $metadata = is_string($item->item_metadata) ? json_decode($item->item_metadata, true) : $item->item_metadata;
                            ?>
                            
                            <h3 class="text-sm font-medium text-gray-900">
                                <?php echo e($metadata['base_name'] ?? ($item->item->name ?? $item->item_name ?? 'Sản phẩm không xác định')); ?>

                            </h3>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-600">
                                <span>SL: <?php echo e($item->quantity); ?></span>
                                <?php
                                    // Prioritize metadata color, fallback to relationship
                                    $colorDisplay = null;
                                    if ($metadata && isset($metadata['color']) && $metadata['color']) {
                                        $colorDisplay = [
                                            'name' => $metadata['color']['name'],
                                            'hex' => $metadata['color']['hex'] ?? '#cccccc'
                                        ];
                                    } elseif ($item->color) {
                                        $colorDisplay = [
                                            'name' => $item->color->color_name,
                                            'hex' => $item->color->hex_code ?? '#cccccc'
                                        ];
                                    }
                                ?>
                                <?php if($colorDisplay): ?>
                                <span class="flex items-center">
                                    Màu: 
                                    <div class="w-3 h-3 rounded-full border border-gray-300 mx-1" 
                                         style="background-color: <?php echo e($colorDisplay['hex']); ?>"></div>
                                    <?php echo e($colorDisplay['name']); ?>

                                </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($metadata && isset($metadata['features']) && !empty($metadata['features'])): ?>
                            <p class="text-xs text-gray-600 mt-1">
                                Tuỳ chọn: <?php echo e(collect($metadata['features'])->pluck('name')->join(', ')); ?>

                            </p>
                            <?php endif; ?>
                        </div>

                        
                        <div class="text-right text-xs space-y-0.5">
                            <?php if($metadata): ?>
                                
                                <?php if(isset($metadata['base_price'])): ?>
                                <div class="text-gray-500">
                                    <span class="line-through">Gốc: <?php echo e(number_format($metadata['base_price'], 0, ',', '.')); ?> đ</span>
                                </div>
                                <?php endif; ?>
                                
                                
                                <?php if($item->discount_amount > 0): ?>
                                <div class="text-red-500">
                                    Giảm giá: -<?php echo e(number_format($item->discount_amount, 0, ',', '.')); ?> đ
                                </div>
                                <?php endif; ?>
                                
                                
                                <?php if(isset($metadata['base_price'])): ?>
                                <div class="text-gray-700">
                                    Hiện tại: <?php echo e(number_format($metadata['base_price'] - ($item->discount_amount ?? 0), 0, ',', '.')); ?> đ
                                </div>
                                <?php endif; ?>
                                
                                
                                <?php if(isset($metadata['color_price']) && $metadata['color_price'] != 0): ?>
                                <div class="text-blue-600">
                                    Màu <?php echo e($metadata['color']['name'] ?? ''); ?>: +<?php echo e(number_format($metadata['color_price'], 0, ',', '.')); ?> đ
                                </div>
                                <?php endif; ?>
                                
                                
                                <?php if(isset($metadata['features']) && !empty($metadata['features'])): ?>
                                    <?php $__currentLoopData = $metadata['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="text-blue-600">
                                        <?php echo e($feature['name']); ?>: +<?php echo e(number_format($feature['price'], 0, ',', '.')); ?> đ
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            
                            <div class="pt-1 border-t border-gray-300 mt-1">
                                <p class="text-sm font-bold text-gray-900">
                                    <?php echo e(number_format($item->line_total, 0, ',', '.')); ?> đ
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="text-gray-900"><?php echo e(number_format($order->subtotal, 0, ',', '.')); ?> VNĐ</span>
                        </div>
                        <?php if($order->discount_total > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Giảm giá:</span>
                            <span class="text-red-600">-<?php echo e(number_format($order->discount_total, 0, ',', '.')); ?> VNĐ</span>
                        </div>
                        <?php endif; ?>
                        <?php if($order->tax_total > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Thuế:</span>
                            <span class="text-gray-900"><?php echo e(number_format($order->tax_total, 0, ',', '.')); ?> VNĐ</span>
                        </div>
                        <?php endif; ?>
                        <?php if($order->shipping_fee > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí vận chuyển:</span>
                            <span class="text-gray-900"><?php echo e(number_format($order->shipping_fee, 0, ',', '.')); ?> VNĐ</span>
                        </div>
                        <?php endif; ?>
                        <?php if($order->payment_fee > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Phí thanh toán:</span>
                            <span class="text-gray-900"><?php echo e(number_format($order->payment_fee, 0, ',', '.')); ?> VNĐ</span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                            <span class="text-gray-900">Tổng cộng:</span>
                            <span class="text-blue-600"><?php echo e(number_format($order->grand_total ?? $order->total_price, 0, ',', '.')); ?> VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php
                $totalLogs = $order->logs()->count();
                $showAll = request()->boolean('all_logs');
                $displayLogs = $order->logs()->with('user')->orderByDesc('created_at')
                    ->when(!$showAll, fn($q) => $q->limit(20))
                    ->get();
            ?>
            
            <?php if($totalLogs > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-history text-purple-600 mr-3"></i>
                        Lịch sử đơn hàng
                        <span class="ml-2 px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">
                            <?php echo e($totalLogs); ?> hoạt động
                        </span>
                    </h2>
                    
                    <?php if($totalLogs > 20): ?>
                    <button onclick="loadAllLogs()" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-expand-alt mr-1"></i>
                        Xem tất cả (<?php echo e($totalLogs); ?>)
                    </button>
                    <?php endif; ?>
                </div>
                
                <div id="timeline-container">
                    <?php echo $__env->make('admin.orders.partials.timeline', ['logs' => $displayLogs], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                
                <?php if($totalLogs > 20 && !$showAll): ?>
                <div class="mt-4 text-center">
                    <button onclick="loadAllLogs()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-chevron-down mr-2"></i>
                        Tải thêm (<?php echo e($totalLogs - 20); ?> hoạt động)
                    </button>
                </div>

                <?php endif; ?>
                
                <?php if($totalLogs > 20): ?>
                <script>
                function loadAllLogs() {
                    const button = event.target;
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang tải...';
                    
                    fetch('<?php echo e(route("admin.orders.show", $order->id)); ?>?all_logs=1')
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newTimeline = doc.querySelector('#timeline-container');
                            if (newTimeline) {
                                document.querySelector('#timeline-container').innerHTML = newTimeline.innerHTML;
                            }
                            // Hide load more button
                            const loadMoreDiv = button.closest('.mt-4');
                            if (loadMoreDiv) loadMoreDiv.style.display = 'none';
                        })
                        .catch(error => {
                            console.error('Error loading logs:', error);
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-chevron-down mr-2"></i>Tải thêm';
                        });
                }
                </script>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Trạng thái đơn hàng</h3>
                
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái giao hàng</label>
                    <?php if($order->status == 'pending'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-2"></i>Chờ xử lý
                        </span>
                    <?php elseif($order->status == 'confirmed'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-check-circle mr-2"></i>Đã xác nhận
                        </span>
                    <?php elseif($order->status == 'shipping'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-truck mr-2"></i>Đang giao
                        </span>
                    <?php elseif($order->status == 'delivered'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-double mr-2"></i>Đã giao
                        </span>
                    <?php elseif($order->status == 'cancelled'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-2"></i>Đã hủy
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-question mr-2"></i><?php echo e($order->status ?? 'Không xác định'); ?>

                        </span>
                    <?php endif; ?>
                </div>

                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái thanh toán</label>
                    <?php if($order->payment_status == 'pending'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-clock mr-2"></i>Chờ thanh toán
                        </span>
                    <?php elseif($order->payment_status == 'processing'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-spinner mr-2"></i>Đang xử lý
                        </span>
                    <?php elseif($order->payment_status == 'completed'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>Đã thanh toán
                        </span>
                    <?php elseif($order->payment_status == 'failed'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Thất bại
                        </span>
                    <?php elseif($order->payment_status == 'cancelled'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-ban mr-2"></i>Đã hủy
                        </span>
                    <?php elseif($order->payment_status == 'partial'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                            <i class="fas fa-coins mr-2"></i>Thanh toán một phần
                        </span>
                    <?php elseif($order->payment_status == 'refunded'): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-undo mr-2"></i>Đã hoàn tiền
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-question mr-2"></i><?php echo e($order->payment_status ?? 'Không xác định'); ?>

                        </span>
                    <?php endif; ?>
                </div>

                
                <div class="pt-4 border-t border-gray-200 space-y-3">
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            <?php echo e($order->payment_status == 'refunded' ? 'Trạng thái thanh toán' : 'Cập nhật trạng thái thanh toán'); ?>

                        </label>
                        <?php if($order->payment_status == 'refunded'): ?>
                            
                            <div class="flex items-center gap-2 px-4 py-2 bg-purple-50 border border-purple-200 rounded-lg">
                                <i class="fas fa-undo text-purple-600"></i>
                                <span class="text-sm font-medium text-purple-800">Đã hoàn tiền</span>
                                <span class="text-xs text-purple-600 ml-auto">(Không thể thay đổi)</span>
                            </div>
                        <?php elseif($order->finance_option_id && $order->installments()->exists()): ?>
                            
                            <?php
                                $unpaidInstallments = $order->installments()->where('status', '!=', 'paid')->count();
                                $totalInstallments = $order->installments()->count();
                                $paidInstallments = $totalInstallments - $unpaidInstallments;
                            ?>
                            
                            <?php if($order->payment_status == 'completed'): ?>
                                <div class="flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                    <i class="fas fa-check text-green-600"></i>
                                    <span class="text-sm font-medium text-green-800">Đã thanh toán</span>
                                    <span class="text-xs text-green-600 ml-auto">(Tự động sau khi hoàn thành trả góp)</span>
                                </div>
                            <?php else: ?>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                                        <i class="fas fa-calendar-check text-blue-600"></i>
                                        <div class="flex-1">
                                            <span class="text-sm font-medium text-blue-800">Đơn hàng trả góp</span>
                                            <div class="text-xs text-blue-600 mt-1">
                                                Đã thanh toán: <?php echo e($paidInstallments); ?>/<?php echo e($totalInstallments); ?> kỳ
                                                <?php if($unpaidInstallments > 0): ?>
                                                    (còn <?php echo e($unpaidInstallments); ?> kỳ)
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 px-2">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Trạng thái sẽ tự động chuyển thành "Đã thanh toán" khi hoàn thành tất cả kỳ trả góp
                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            
                            <form method="POST" action="<?php echo e(route('admin.orders.update-payment-status', $order)); ?>" class="flex gap-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <select name="payment_status" class="flex-1 text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending" <?php echo e($order->payment_status == 'pending' ? 'selected' : ''); ?>>Chờ thanh toán</option>
                                    <option value="completed" <?php echo e($order->payment_status == 'completed' ? 'selected' : ''); ?>>Đã thanh toán</option>
                                    <option value="failed" <?php echo e($order->payment_status == 'failed' ? 'selected' : ''); ?>>Thất bại</option>
                                </select>
                                <button type="submit" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-lg transition">
                                    Cập nhật
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    
                    <?php if($order->payment_status == 'completed'): ?>
                    <?php
                        $hasUserRefundRequest = $order->hasPendingRefundRequest();
                    ?>
                    
                    <?php if($hasUserRefundRequest): ?>
                        
                        <a href="<?php echo e(route('admin.payments.refunds')); ?>" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                            <i class="fas fa-list mr-2"></i>
                            Xử lý yêu cầu hoàn tiền
                        </a>
                        <p class="text-xs text-blue-600 mt-1 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Khách hàng đã yêu cầu hoàn tiền
                        </p>
                    <?php else: ?>
                        
                        <button onclick="document.getElementById('refundModal').classList.remove('hidden')" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg transition">
                            <i class="fas fa-undo mr-2"></i>
                            Hoàn tiền trực tiếp
                        </button>
                        <p class="text-xs text-gray-500 mt-1 text-center">
                            Admin chủ động hoàn tiền
                        </p>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>

                
                <?php if($order->status != 'delivered' && $order->status != 'cancelled'): ?>
                <div class="pt-4 border-t border-gray-200 space-y-2">
                    <?php if($order->status == 'pending'): ?>
                    <form method="POST" action="<?php echo e(route('admin.orders.nextStatus', $order->id)); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Xác nhận đơn
                        </button>
                    </form>
                    <?php elseif($order->status == 'confirmed'): ?>
                    <form method="POST" action="<?php echo e(route('admin.orders.nextStatus', $order->id)); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Bắt đầu giao hàng
                        </button>
                    </form>
                    <?php elseif($order->status == 'shipping'): ?>
                    <form method="POST" action="<?php echo e(route('admin.orders.nextStatus', $order->id)); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Hoàn tất giao hàng
                        </button>
                    </form>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('admin.orders.cancel', $order->id)); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors" 
                                onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                            <i class="fas fa-times-circle mr-2"></i>
                            Hủy đơn hàng
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Thông tin khách hàng
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Họ tên:</span>
                        <p class="text-gray-900 mt-1">
                            <?php echo e($order->shippingAddress->contact_name ?? $order->billingAddress->contact_name ?? $order->user->name ?? 'N/A'); ?>

                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Email:</span>
                        <p class="text-gray-900 mt-1"><?php echo e($order->user->email ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Số điện thoại:</span>
                        <p class="text-gray-900 mt-1">
                            <?php echo e($order->shippingAddress->phone ?? $order->billingAddress->phone ?? $order->user->phone ?? 'N/A'); ?>

                        </p>
                    </div>
                </div>
            </div>

            
            <?php if($order->billingAddress || $order->shippingAddress): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                    Địa chỉ
                </h3>
                <div class="space-y-4 text-sm">
                    <?php if($order->billingAddress): ?>
                    <div class="pb-3 <?php if($order->shippingAddress && $order->billing_address_id != $order->shipping_address_id): ?> border-b border-gray-200 <?php endif; ?>">
                        <h4 class="font-medium text-gray-700 mb-2">
                            <i class="fas fa-file-invoice mr-1"></i>
                            Địa chỉ thanh toán:
                        </h4>
                        <p class="text-gray-900"><?php echo e($order->billingAddress->address); ?></p>
                        <p class="text-gray-900"><?php echo e($order->billingAddress->city); ?><?php if($order->billingAddress->state): ?>, <?php echo e($order->billingAddress->state); ?><?php endif; ?></p>
                        <?php if($order->billingAddress->postal_code): ?>
                        <p class="text-gray-600 text-xs">Mã bưu điện: <?php echo e($order->billingAddress->postal_code); ?></p>
                        <?php endif; ?>
                        <p class="text-gray-600 text-xs mt-1"><?php echo e($order->billingAddress->contact_name); ?> - <?php echo e($order->billingAddress->phone); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($order->shippingAddress && $order->billing_address_id != $order->shipping_address_id): ?>
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">
                            <i class="fas fa-shipping-fast mr-1"></i>
                            Địa chỉ giao hàng:
                        </h4>
                        <p class="text-gray-900"><?php echo e($order->shippingAddress->address); ?></p>
                        <p class="text-gray-900"><?php echo e($order->shippingAddress->city); ?><?php if($order->shippingAddress->state): ?>, <?php echo e($order->shippingAddress->state); ?><?php endif; ?></p>
                        <?php if($order->shippingAddress->postal_code): ?>
                        <p class="text-gray-600 text-xs">Mã bưu điện: <?php echo e($order->shippingAddress->postal_code); ?></p>
                        <?php endif; ?>
                        <p class="text-gray-600 text-xs mt-1"><?php echo e($order->shippingAddress->contact_name); ?> - <?php echo e($order->shippingAddress->phone); ?></p>
                    </div>
                    <?php elseif($order->billing_address_id == $order->shipping_address_id && $order->billingAddress): ?>
                    <p class="text-xs text-gray-500 italic">
                        <i class="fas fa-check-circle text-green-600 mr-1"></i>
                        Địa chỉ giao hàng trùng với địa chỉ thanh toán
                    </p>
                    <?php endif; ?>
                    <?php if($order->shipping_method): ?>
                    <div>
                        <span class="font-medium text-gray-700">Phương thức:</span>
                        <p class="text-gray-900 mt-1">
                            <?php if($order->shipping_method == 'standard'): ?>
                                Giao hàng tiêu chuẩn
                            <?php elseif($order->shipping_method == 'express'): ?>
                                Giao hàng nhanh
                            <?php else: ?>
                                <?php echo e($order->shipping_method); ?>

                            <?php endif; ?>
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->tracking_number): ?>
                    <div>
                        <span class="font-medium text-gray-700">Mã vận đơn:</span>
                        <p class="text-gray-900 mt-1 font-mono"><?php echo e($order->tracking_number); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-credit-card text-green-600 mr-2"></i>
                    Thông tin thanh toán
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Loại thanh toán:</span>
                        <p class="text-gray-900 mt-1">
                            <?php if($order->isInstallmentOrder()): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Trả góp
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Thanh toán một lần
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Phương thức:</span>
                        <p class="text-gray-900 mt-1"><?php echo e($order->paymentMethod->name ?? 'N/A'); ?></p>
                    </div>
                    <?php if($order->transaction_id): ?>
                    <div>
                        <span class="font-medium text-gray-700">Mã giao dịch:</span>
                        <p class="text-gray-900 mt-1 font-mono"><?php echo e($order->transaction_id); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->paid_at): ?>
                    <div>
                        <span class="font-medium text-gray-700">Thanh toán lúc:</span>
                        <p class="text-gray-900 mt-1"><?php echo e($order->paid_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if($order->isInstallmentOrder()): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-money-bill-wave text-orange-600 mr-2"></i>
                    Thông tin trả góp
                </h3>
                <div class="space-y-3 text-sm">
                    <?php if($order->financeOption): ?>
                    <div>
                        <span class="font-medium text-gray-700">Gói tài chính:</span>
                        <p class="text-gray-900 mt-1"><?php echo e($order->financeOption->name); ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->down_payment_amount): ?>
                    <div>
                        <span class="font-medium text-gray-700">Trả trước:</span>
                        <p class="text-gray-900 mt-1 font-semibold text-blue-600">
                            <?php echo e(number_format($order->down_payment_amount, 0, ',', '.')); ?> VNĐ
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->tenure_months): ?>
                    <div>
                        <span class="font-medium text-gray-700">Thời hạn:</span>
                        <p class="text-gray-900 mt-1"><?php echo e($order->tenure_months); ?> tháng</p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->monthly_payment_amount): ?>
                    <div>
                        <span class="font-medium text-gray-700">Trả hàng tháng:</span>
                        <p class="text-gray-900 mt-1 font-semibold text-orange-600">
                            <?php echo e(number_format($order->monthly_payment_amount, 0, ',', '.')); ?> VNĐ/tháng
                        </p>
                    </div>
                    <?php endif; ?>
                    <?php if($order->tenure_months && $order->monthly_payment_amount && $order->down_payment_amount): ?>
                    <div class="pt-3 border-t border-gray-200">
                        <span class="font-medium text-gray-700">Tổng số tiền trả:</span>
                        <p class="text-gray-900 mt-1 font-bold">
                            <?php echo e(number_format($order->down_payment_amount + ($order->monthly_payment_amount * $order->tenure_months), 0, ',', '.')); ?> VNĐ
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <span class="font-medium text-gray-700">Lịch trả góp:</span>
                                <?php if($order->installments->count() > 0): ?>
                                    <?php
                                        $totalInstallments = $order->installments->count();
                                        $paidInstallments = $order->installments->where('status', 'paid')->count();
                                        $isCompleted = $paidInstallments === $totalInstallments;
                                    ?>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <?php if($isCompleted): ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <i class="fas fa-check-double mr-1"></i>
                                                    Hoàn thành <?php echo e($totalInstallments); ?>/<?php echo e($totalInstallments); ?> kỳ
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Đã trả <?php echo e($paidInstallments); ?>/<?php echo e($totalInstallments); ?> kỳ
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full transition-all" style="width: <?php echo e($totalInstallments > 0 ? ($paidInstallments / $totalInstallments * 100) : 0); ?>%"></div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p class="text-yellow-600 text-sm mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Chưa tạo lịch
                                    </p>
                                <?php endif; ?>
                            </div>
                            <?php if($order->installments->count() == 0): ?>
                            <form method="POST" action="<?php echo e(route('admin.orders.generate-installments', $order)); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-medium rounded-lg transition">
                                    <i class="fas fa-calendar-plus mr-1"></i>
                                    Tạo lịch trả góp
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    
                    <?php if($order->installments->count() > 0): ?>
                    <div class="pt-3 border-t border-gray-200">
                        <a href="<?php echo e(route('admin.installments.show', $order->id)); ?>" 
                           class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Xem chi tiết lịch trả góp
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            
            <?php if($order->note): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                    Ghi chú
                </h3>
                <p class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($order->note); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<div id="markAsPaidModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Xác nhận thanh toán kỳ trả góp</h3>
                <button onclick="document.getElementById('markAsPaidModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" id="markAsPaidForm" onsubmit="return handleMarkAsPaid(event)">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phương thức thanh toán <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                        <option value="">Chọn phương thức</option>
                        <?php $__currentLoopData = \App\Models\PaymentMethod::where('is_active', true)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($method->id); ?>"><?php echo e($method->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày thanh toán <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           name="payment_date" 
                           value="<?php echo e(date('Y-m-d')); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea name="notes" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Ghi chú về giao dịch (tùy chọn)"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="document.getElementById('markAsPaidModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                        <i class="fas fa-check mr-2"></i>
                        Xác nhận thanh toán
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showMarkAsPaidModal(installmentId) {
    const form = document.getElementById('markAsPaidForm');
    form.action = `/admin/installments/${installmentId}/mark-as-paid`;
    
    document.getElementById('markAsPaidModal').classList.remove('hidden');
}

function handleMarkAsPaid(event) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
    
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.showMessage) {
                window.showMessage(data.message, 'success');
            }
            document.getElementById('markAsPaidModal').classList.add('hidden');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showMessage) {
            window.showMessage(error.message || 'Có lỗi xảy ra khi xác nhận thanh toán', 'error');
        }
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
    
    return false;
}
</script>


<div id="refundModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Hoàn tiền trực tiếp</h3>
                <p class="text-sm text-gray-600 mt-1">Admin chủ động hoàn tiền cho khách hàng</p>
                <button onclick="document.getElementById('refundModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form method="POST" action="<?php echo e(route('admin.orders.refund', $order)); ?>" id="refundForm">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số tiền hoàn</label>
                    <input type="number" 
                           id="refundAmount"
                           name="refund_amount" 
                           max="<?php echo e(number_format($order->grand_total, 0, '.', '')); ?>"
                           value="<?php echo e(number_format($order->grand_total, 0, '.', '')); ?>"
                           step="1000"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <p class="mt-1 text-xs text-gray-500">Tổng đơn hàng: <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?> VNĐ</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lý do hoàn tiền</label>
                    <textarea id="refundReason"
                              name="refund_reason" 
                              rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="Nhập lý do hoàn tiền..."></textarea>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
                    <p class="text-sm text-orange-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Lưu ý:</strong> Hành động này sẽ thay đổi trạng thái thanh toán thành "Đã hoàn tiền" và gửi thông báo đến khách hàng.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            onclick="document.getElementById('refundModal').classList.add('hidden')"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Hủy
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition">
                        <i class="fas fa-check mr-2"></i>
                        Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Refund Form Validation
document.getElementById('refundForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const amountInput = document.getElementById('refundAmount');
    const reasonInput = document.getElementById('refundReason');
    const maxAmount = parseFloat('<?php echo e(number_format($order->grand_total, 0, ".", "")); ?>');
    
    const amount = parseFloat(amountInput.value);
    const reason = reasonInput.value.trim();
    
    // Validate amount
    if (!amountInput.value || isNaN(amount) || amount <= 0) {
        window.showMessage('Vui lòng nhập số tiền hoàn hợp lệ', 'error');
        amountInput.focus();
        return;
    }
    
    if (amount > maxAmount) {
        window.showMessage('Số tiền hoàn không được vượt quá tổng đơn hàng (' + new Intl.NumberFormat('vi-VN').format(maxAmount) + ' VNĐ)', 'error');
        amountInput.focus();
        return;
    }
    
    // Validate reason
    if (!reason) {
        window.showMessage('Vui lòng nhập lý do hoàn tiền', 'error');
        reasonInput.focus();
        return;
    }
    
    if (reason.length < 10) {
        window.showMessage('Lý do hoàn tiền phải có ít nhất 10 ký tự', 'error');
        reasonInput.focus();
        return;
    }
    
    // All validation passed - submit form
    this.submit();
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>