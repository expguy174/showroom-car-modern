<?php $__env->startSection('title', 'Chi tiết lịch trả góp - #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginaldb1b157d84f8f63332f3508c9e385c0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.flash-messages','data' => ['showIcons' => true,'dismissible' => true,'position' => 'top-right','autoDismiss' => 5000]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show-icons' => true,'dismissible' => true,'position' => 'top-right','auto-dismiss' => 5000]); ?>
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
    
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?php echo e(route('admin.installments.index')); ?>" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
                <span class="text-gray-300">|</span>
                <h1 class="text-2xl font-bold text-gray-900">
                    Lịch trả góp - Đơn hàng #<?php echo e($order->order_number); ?>

                </h1>
            </div>
            <p class="text-sm text-gray-600">
                Khách hàng: <strong><?php echo e($order->user->userProfile->name ?? $order->user->email); ?></strong>
                <?php if($order->financeOption): ?>
                    • Gói vay: <strong><?php echo e($order->financeOption->name); ?> (<?php echo e($order->financeOption->bank_name); ?>)</strong>
                <?php endif; ?>
            </p>
        </div>
        <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-file-invoice mr-2"></i>
            Xem đơn hàng
        </a>
    </div>

    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Sản phẩm</h3>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-3 mb-2">
                        <i class="fas fa-car text-blue-600"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900"><?php echo e($item->item_name); ?></p>
                            <p class="text-xs text-gray-500">SL: <?php echo e($item->quantity); ?> • <?php echo e(number_format($item->price)); ?> đ</p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Thông tin tài chính</h3>
                <?php if($order->financeOption): ?>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Gói vay:</span>
                        <span class="font-medium"><?php echo e($order->financeOption->name); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngân hàng:</span>
                        <span class="font-medium"><?php echo e($order->financeOption->bank_name); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lãi suất:</span>
                        <span class="font-medium"><?php echo e($order->financeOption->interest_rate); ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Thời hạn:</span>
                        <span class="font-medium"><?php echo e($order->tenure_months); ?> tháng</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Tổng quan thanh toán</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tổng giá trị:</span>
                        <span class="font-bold text-gray-900"><?php echo e(number_format($order->grand_total ?? $order->total_price)); ?> đ</span>
                    </div>
                    <?php if($order->down_payment_amount): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Đã trả trước:</span>
                        <span class="font-medium text-green-600"><?php echo e(number_format($order->down_payment_amount)); ?> đ</span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tổng kỳ:</span>
                        <span class="font-medium"><?php echo e($order->installments->count()); ?> kỳ</span>
                    </div>
                    <?php if($order->monthly_payment_amount): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Mỗi kỳ:</span>
                        <span class="font-medium text-blue-600"><?php echo e(number_format($order->monthly_payment_amount)); ?> đ</span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <?php
            $totalInstallments = $order->installments->count();
            $paidInstallments = $order->installments->where('status', 'paid')->count();
            $pendingInstallments = $order->installments->where('status', 'pending')->count();
            $overdueInstallments = $order->installments->where('status', 'overdue')->count();
            
            $paidAmount = $order->installments->where('status', 'paid')->sum('amount');
            $unpaidAmount = $order->installments->whereIn('status', ['pending', 'overdue'])->sum('amount');
        ?>

        <?php if (isset($component)) { $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Tổng kỳ','value' => $totalInstallments,'icon' => 'fas fa-list','color' => 'blue','description' => number_format($order->installments->sum('amount')) . ' đ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tổng kỳ','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalInstallments),'icon' => 'fas fa-list','color' => 'blue','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($order->installments->sum('amount')) . ' đ')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f)): ?>
<?php $attributes = $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f; ?>
<?php unset($__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14dadb7763529f6bc7d89e29f3674f2f)): ?>
<?php $component = $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f; ?>
<?php unset($__componentOriginal14dadb7763529f6bc7d89e29f3674f2f); ?>
<?php endif; ?>
            
        <?php if (isset($component)) { $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Đã thanh toán','value' => $paidInstallments,'icon' => 'fas fa-check-circle','color' => 'green','description' => number_format($paidAmount) . ' đ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Đã thanh toán','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($paidInstallments),'icon' => 'fas fa-check-circle','color' => 'green','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($paidAmount) . ' đ')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f)): ?>
<?php $attributes = $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f; ?>
<?php unset($__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14dadb7763529f6bc7d89e29f3674f2f)): ?>
<?php $component = $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f; ?>
<?php unset($__componentOriginal14dadb7763529f6bc7d89e29f3674f2f); ?>
<?php endif; ?>
            
        <?php if (isset($component)) { $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Chờ thanh toán','value' => $pendingInstallments,'icon' => 'fas fa-clock','color' => 'yellow','description' => $pendingInstallments . ' kỳ']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Chờ thanh toán','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pendingInstallments),'icon' => 'fas fa-clock','color' => 'yellow','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pendingInstallments . ' kỳ')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f)): ?>
<?php $attributes = $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f; ?>
<?php unset($__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14dadb7763529f6bc7d89e29f3674f2f)): ?>
<?php $component = $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f; ?>
<?php unset($__componentOriginal14dadb7763529f6bc7d89e29f3674f2f); ?>
<?php endif; ?>
            
        <?php if (isset($component)) { $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Quá hạn','value' => $overdueInstallments,'icon' => 'fas fa-exclamation-triangle','color' => 'red','description' => $overdueInstallments > 0 ? 'Cần xử lý!' : 'Không có']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Quá hạn','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($overdueInstallments),'icon' => 'fas fa-exclamation-triangle','color' => 'red','description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($overdueInstallments > 0 ? 'Cần xử lý!' : 'Không có')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f)): ?>
<?php $attributes = $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f; ?>
<?php unset($__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14dadb7763529f6bc7d89e29f3674f2f)): ?>
<?php $component = $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f; ?>
<?php unset($__componentOriginal14dadb7763529f6bc7d89e29f3674f2f); ?>
<?php endif; ?>
    </div>

    
    <?php
        $nextPayment = $order->installments
            ->whereIn('status', ['pending', 'overdue'])
            ->sortBy('installment_number')
            ->first();
    ?>
    <?php if($nextPayment): ?>
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-600 text-xl"></i>
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-semibold text-blue-800 mb-1">
                    <i class="fas fa-arrow-right mr-1"></i> Kỳ tiếp theo cần thanh toán (theo thứ tự):
                </h3>
                <div class="text-sm text-blue-700">
                    <strong>Kỳ <?php echo e($nextPayment->installment_number); ?></strong> - 
                    <strong><?php echo e(number_format($nextPayment->amount)); ?> đ</strong> - 
                    Đến hạn: <strong><?php echo e($nextPayment->due_date->format('d/m/Y')); ?></strong>
                    <?php if($nextPayment->status === 'overdue'): ?>
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Đã quá hạn <?php echo e($nextPayment->due_date->diffInDays(now())); ?> ngày
                        </span>
                    <?php endif; ?>
                </div>
                <div class="text-xs text-blue-600 mt-2">
                    <i class="fas fa-lock mr-1"></i> Phải thanh toán kỳ này trước khi thanh toán các kỳ tiếp theo
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">
                Danh sách <?php echo e($totalInstallments); ?> kỳ trả góp
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kỳ</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Số tiền</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đến hạn</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày thanh toán</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã GD</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ghi chú</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                        // Find the next installment that can be paid (sequential payment logic)
                        $nextPayableInstallment = $order->installments
                            ->whereIn('status', ['pending', 'overdue'])
                            ->sortBy('installment_number')
                            ->first();
                    ?>
                    <?php $__currentLoopData = $order->installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            // Can only pay if this is the next unpaid installment
                            $canPay = $nextPayableInstallment && $nextPayableInstallment->id === $installment->id;
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors <?php echo e($installment->status === 'overdue' ? 'bg-red-50' : ''); ?> <?php echo e($canPay ? 'bg-blue-50 border-l-4 border-blue-500' : ''); ?>">
                            
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($canPay ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'); ?>">
                                    Kỳ <?php echo e($installment->installment_number); ?>

                                    <?php if($canPay): ?>
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    <?php endif; ?>
                                </span>
                            </td>
                            
                            
                            <td class="px-4 py-3 text-right">
                                <span class="text-sm font-semibold text-gray-900"><?php echo e(number_format($installment->amount)); ?> đ</span>
                            </td>
                            
                            
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-900"><?php echo e($installment->due_date->format('d/m/Y')); ?></div>
                                <?php if($installment->status === 'overdue'): ?>
                                <div class="text-xs text-red-600 mt-1">
                                    Quá <?php echo e($installment->due_date->diffInDays(now())); ?> ngày
                                </div>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td class="px-4 py-3">
                                <?php if($installment->status === 'paid'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Đã thanh toán
                                    </span>
                                <?php elseif($installment->status === 'overdue'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Quá hạn
                                    </span>
                                <?php elseif($installment->status === 'cancelled'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times-circle mr-1"></i> Đã hủy
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Chờ thanh toán
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td class="px-4 py-3">
                                <?php if($installment->paid_at): ?>
                                    <div class="text-sm text-gray-900"><?php echo e($installment->paid_at->format('d/m/Y')); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($installment->paid_at->format('H:i')); ?></div>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td class="px-4 py-3">
                                <?php if($installment->paymentTransaction): ?>
                                    <span class="text-xs font-mono text-blue-600">
                                        <?php echo e($installment->paymentTransaction->transaction_number); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td class="px-4 py-3">
                                <?php if($installment->paymentTransaction && $installment->paymentTransaction->notes): ?>
                                    <div class="text-xs text-gray-700 max-w-xs truncate" title="<?php echo e($installment->paymentTransaction->notes); ?>">
                                        <?php echo e($installment->paymentTransaction->notes); ?>

                                    </div>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            
                            
                            <td class="px-4 py-3 text-center">
                                <?php if($installment->status === 'paid'): ?>
                                    <div class="flex flex-col items-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Đã xác nhận
                                        </span>
                                        <?php if($installment->paymentTransaction && $installment->paymentTransaction->paymentMethod): ?>
                                        <span class="text-xs text-gray-500 mt-1">
                                            <?php echo e($installment->paymentTransaction->paymentMethod->name); ?>

                                        </span>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif($installment->status === 'cancelled'): ?>
                                    <span class="text-gray-400 text-xs">Đã hủy</span>
                                <?php elseif($canPay): ?>
                                    <button type="button"
                                            onclick="showMarkAsPaidModal(<?php echo e($installment->id); ?>)"
                                            class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors">
                                        <i class="fas fa-check mr-1"></i> Xác nhận
                                    </button>
                                <?php else: ?>
                                    <div class="text-xs text-gray-400">
                                        <i class="fas fa-lock mr-1"></i>
                                        <div class="mt-1">Chưa đến lượt</div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-right font-semibold text-gray-700">
                            Tổng còn nợ:
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-red-600 text-lg">
                            <?php echo e(number_format($unpaidAmount)); ?> đ
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<div id="markAsPaidModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Xác nhận</h3>
                <button type="button" onclick="closeMarkAsPaidModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="markAsPaidForm" method="POST" onsubmit="return handleMarkAsPaid(event)">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phương thức thanh toán <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method_id" id="payment_method_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Chọn phương thức</option>
                        <?php
                            $paymentMethods = \App\Models\PaymentMethod::where('is_active', true)
                                ->where(function($q) {
                                    $q->where('name', 'like', '%Chuyển khoản%')
                                      ->orWhere('name', 'like', '%Tiền mặt%')
                                      ->orWhere('name', 'like', '%Ngân hàng%')
                                      ->orWhere('name', 'like', '%Cash%')
                                      ->orWhere('name', 'like', '%Bank%');
                                })
                                ->get();
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($method->id); ?>"><?php echo e($method->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            
                            <?php $__currentLoopData = \App\Models\PaymentMethod::where('is_active', true)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($method->id); ?>"><?php echo e($method->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ngày thanh toán <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="date" name="payment_date" id="payment_date" value="<?php echo e(date('Y-m-d')); ?>" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <input type="time" name="payment_time" id="payment_time" value="<?php echo e(date('H:i')); ?>" 
                               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                              placeholder="Ghi chú về giao dịch (tùy chọn)"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeMarkAsPaidModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Hủy
                    </button>
                    <button type="submit" id="submitMarkAsPaidBtn"
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-check mr-2"></i>
                        Xác nhận
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let markAsPaidInstallmentId = null;

// Show modal
function showMarkAsPaidModal(installmentId) {
    markAsPaidInstallmentId = installmentId;
    document.getElementById('markAsPaidModal').classList.remove('hidden');
}

// Close modal
function closeMarkAsPaidModal() {
    document.getElementById('markAsPaidModal').classList.add('hidden');
    document.getElementById('markAsPaidForm').reset();
}

// Handle form submit
function handleMarkAsPaid(event) {
    event.preventDefault();
    
    const form = event.target;
    const submitBtn = document.getElementById('submitMarkAsPaidBtn');
    const originalText = submitBtn.innerHTML;
    
    // Validate form fields
    const paymentMethodId = document.getElementById('payment_method_id').value;
    const paymentDate = document.getElementById('payment_date').value;
    const paymentTime = document.getElementById('payment_time').value;
    
    // Validation checks
    if (!paymentMethodId) {
        if (window.showMessage) {
            window.showMessage('Vui lòng chọn phương thức thanh toán.', 'error');
        }
        document.getElementById('payment_method_id').focus();
        return false;
    }
    
    if (!paymentDate) {
        if (window.showMessage) {
            window.showMessage('Vui lòng chọn ngày thanh toán.', 'error');
        }
        document.getElementById('payment_date').focus();
        return false;
    }
    
    if (!paymentTime) {
        if (window.showMessage) {
            window.showMessage('Vui lòng chọn giờ thanh toán.', 'error');
        }
        document.getElementById('payment_time').focus();
        return false;
    }
    
    // Disable and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
    
    const formData = new FormData(form);
    
    fetch(`/admin/installments/${markAsPaidInstallmentId}/mark-as-paid`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            closeMarkAsPaidModal();
            
            // Show success message
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xác nhận thanh toán thành công!', 'success');
            }
            
            // Reload page to update stats and table (for both regular and last installment)
            setTimeout(() => {
                window.location.reload();
            }, data.is_last_installment ? 2000 : 1000);
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/installments/show.blade.php ENDPATH**/ ?>