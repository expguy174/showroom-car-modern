<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gói vay</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tiến độ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Còn nợ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $totalInstallments = $order->installments->count();
                        $paidInstallments = $order->installments->where('status', 'paid')->count();
                        $unpaidAmount = $order->installments->whereIn('status', ['pending', 'overdue'])->sum('amount');
                        $hasOverdue = $order->installments->where('status', 'overdue')->count() > 0;
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors <?php echo e($hasOverdue ? 'bg-red-50' : ''); ?>">
                        
                        <td class="px-6 py-3">
                            <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" 
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                #<?php echo e($order->order_number); ?>

                            </a>
                            <?php if($order->items->isNotEmpty()): ?>
                                <div class="text-xs text-gray-600 mt-1">
                                    <i class="fas fa-car text-gray-400"></i> <?php echo e($order->items->first()->item_name); ?>

                                    <?php if($order->items->count() > 1): ?>
                                        <span class="text-gray-400">+<?php echo e($order->items->count() - 1); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="text-xs font-semibold text-gray-700 mt-1">
                                <?php echo e(number_format($order->grand_total ?? $order->total_price, 0)); ?> đ
                            </div>
                        </td>
                        
                        
                        <td class="px-6 py-3">
                            <?php if($order->user): ?>
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($order->user->userProfile->name ?? $order->user->email); ?>

                                </div>
                                <?php if($order->user->userProfile && $order->user->userProfile->phone): ?>
                                <div class="text-xs text-gray-500"><?php echo e($order->user->userProfile->phone); ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-sm text-gray-400">N/A</span>
                            <?php endif; ?>
                        </td>
                        
                        
                        <td class="px-6 py-3">
                            <?php if($order->financeOption): ?>
                                <div class="text-sm font-medium text-gray-900"><?php echo e($order->financeOption->name); ?></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-university text-gray-400"></i> <?php echo e($order->financeOption->bank_name); ?>

                                </div>
                                <?php if($order->tenure_months): ?>
                                <div class="text-xs text-gray-600 mt-1">
                                    <i class="fas fa-calendar-alt text-gray-400"></i> <?php echo e($order->tenure_months); ?> tháng • <?php echo e($order->financeOption->interest_rate); ?>%
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-sm text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        
                        
                        <td class="px-6 py-3 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-semibold <?php echo e($paidInstallments === $totalInstallments ? 'text-green-600' : 'text-gray-900'); ?>">
                                    <?php echo e($paidInstallments); ?>/<?php echo e($totalInstallments); ?>

                                </span>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1 max-w-[60px]">
                                    <div class="bg-green-600 h-1.5 rounded-full" style="width: <?php echo e($totalInstallments > 0 ? ($paidInstallments / $totalInstallments * 100) : 0); ?>%"></div>
                                </div>
                            </div>
                        </td>
                        
                        
                        <td class="px-6 py-3 text-right">
                            <span class="text-sm font-semibold <?php echo e($unpaidAmount > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                                <?php echo e(number_format($unpaidAmount, 0)); ?> đ
                            </span>
                            <?php if($hasOverdue): ?>
                            <div class="text-xs text-red-600 mt-1">
                                <i class="fas fa-exclamation-triangle"></i> Có quá hạn
                            </div>
                            <?php endif; ?>
                        </td>
                        
                        
                        <td class="px-6 py-3">
                            <div class="text-sm text-gray-900"><?php echo e($order->created_at->format('d/m/Y')); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($order->created_at->format('H:i')); ?></div>
                        </td>
                        
                        
                        <td class="px-6 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('admin.installments.show', $order->id)); ?>"
                                   class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                   title="Xem chi tiết lịch trả góp">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg font-medium">Không có đơn hàng trả góp nào</p>
                                <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    
    <?php if($orders->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $orders]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($orders)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5)): ?>
<?php $attributes = $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5; ?>
<?php unset($__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f9437379ffbb940ff05ba93353d3cd5)): ?>
<?php $component = $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5; ?>
<?php unset($__componentOriginal1f9437379ffbb940ff05ba93353d3cd5); ?>
<?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/installments/partials/table.blade.php ENDPATH**/ ?>