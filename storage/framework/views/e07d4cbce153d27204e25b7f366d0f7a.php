<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Yêu cầu hoàn tiền
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Khách hàng
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Số tiền
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Lý do
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày tạo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày xử lý
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                Refund #<?php echo e($refund->id); ?>

                            </div>
                            <div class="text-sm text-gray-500">
                                <?php if($refund->paymentTransaction && $refund->paymentTransaction->order): ?>
                                    Đơn hàng: <?php echo e($refund->paymentTransaction->order->order_number ?? '#' . $refund->paymentTransaction->order_id); ?>

                                <?php else: ?>
                                    Đơn hàng: #<?php echo e($refund->paymentTransaction->order_id ?? 'N/A'); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            <?php echo e($refund->paymentTransaction->user->userProfile->name ?? $refund->paymentTransaction->user->email ?? 'N/A'); ?>

                        </div>
                        <div class="text-sm text-gray-500">
                            <?php echo e($refund->paymentTransaction->user->email ?? ''); ?>

                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">
                            <?php echo e(number_format($refund->amount, 0, ',', '.')); ?> VNĐ
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 max-w-xs">
                            <?php echo e($refund->reason ? Str::limit($refund->reason, 100) : 'Không có lý do'); ?>

                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($refund->status === 'pending'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>
                                Chờ xử lý
                            </span>
                        <?php elseif($refund->status === 'processing'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-spinner mr-1"></i>
                                Đang xử lý
                            </span>
                        <?php elseif($refund->status === 'refunded'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>
                                Đã hoàn tiền
                            </span>
                        <?php elseif($refund->status === 'failed'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times mr-1"></i>
                                Thất bại
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($refund->created_at->format('d/m/Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php if($refund->processed_at): ?>
                            <?php echo e($refund->processed_at->format('d/m/Y H:i')); ?>

                        <?php else: ?>
                            <span class="text-gray-400">Chưa xử lý</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <?php if($refund->status === 'pending'): ?>
                            <!-- Pending: Workflow options -->
                            <div class="flex items-center gap-1">
                                <button onclick="updateRefundStatus(<?php echo e($refund->id); ?>, 'processing')" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors"
                                        title="Bắt đầu xử lý yêu cầu hoàn tiền">
                                    <i class="fas fa-arrow-right mr-1"></i>
                                    Xử lý
                                </button>
                                
                                <!-- Quick actions cho cases đơn giản -->
                                <div class="relative group">
                                    <button class="inline-flex items-center px-1 py-1 text-xs text-gray-500 hover:text-gray-700 rounded"
                                            title="Thao tác nhanh">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="absolute right-0 top-full mt-1 w-32 bg-white rounded-md shadow-lg border border-gray-200 z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                        <button onclick="updateRefundStatus(<?php echo e($refund->id); ?>, 'refunded')" 
                                                class="block w-full text-left px-3 py-2 text-xs text-green-700 hover:bg-green-50">
                                            <i class="fas fa-check mr-1"></i>Hoàn tiền ngay
                                        </button>
                                        <button onclick="updateRefundStatus(<?php echo e($refund->id); ?>, 'failed')" 
                                                class="block w-full text-left px-3 py-2 text-xs text-red-700 hover:bg-red-50">
                                            <i class="fas fa-times mr-1"></i>Từ chối ngay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php elseif($refund->status === 'processing'): ?>
                            <!-- Processing: Chọn Hoàn tiền hoặc Từ chối -->
                            <div class="flex items-center gap-1">
                                <button onclick="updateRefundStatus(<?php echo e($refund->id); ?>, 'refunded')" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 transition-colors"
                                        title="Chấp nhận và thực hiện hoàn tiền">
                                    <i class="fas fa-check mr-1"></i>
                                    Hoàn tiền
                                </button>
                                <button onclick="updateRefundStatus(<?php echo e($refund->id); ?>, 'failed')" 
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 transition-colors"
                                        title="Từ chối yêu cầu hoàn tiền">
                                    <i class="fas fa-times mr-1"></i>
                                    Từ chối
                                </button>
                            </div>
                        <?php else: ?>
                            <!-- Completed: Chỉ hiển thị trạng thái -->
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                <?php if($refund->status === 'refunded'): ?> bg-green-100 text-green-800
                                <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                                <?php if($refund->status === 'refunded'): ?>
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Hoàn thành
                                <?php else: ?>
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Đã từ chối
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có yêu cầu hoàn tiền</h3>
                            <p class="text-gray-500">Các yêu cầu hoàn tiền từ khách hàng sẽ hiển thị ở đây.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if($refunds->hasPages()): ?>
    <div class="bg-white px-4 py-3 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $refunds,'showInfo' => true,'showJumper' => true,'containerClass' => 'flex items-center justify-between']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($refunds),'showInfo' => true,'showJumper' => true,'containerClass' => 'flex items-center justify-between']); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/payments/partials/refunds-table.blade.php ENDPATH**/ ?>