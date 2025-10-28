
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">#<?php echo e($order->order_number ?? $order->id); ?></div>
                                <div class="text-sm text-gray-500"><?php echo e($order->items->count()); ?> sản phẩm</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                
                                <div class="flex-shrink-0">
                                    <?php if($order->user && $order->user->userProfile && $order->user->userProfile->avatar_path): ?>
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                             src="<?php echo e(Storage::url($order->user->userProfile->avatar_path)); ?>" 
                                             alt="<?php echo e($order->user->userProfile->name ?? $order->user->email); ?>">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                <?php echo e(strtoupper(mb_substr(optional($order->user->userProfile)->name ?? optional($order->user)->email ?? 'KH', 0, 2, 'UTF-8'))); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">
                                        <?php echo e(optional($order->user->userProfile)->name ?? 'Khách vãng lai'); ?>

                                    </div>
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                        <?php echo e(optional($order->user)->email ?? '-'); ?>

                                    </div>
                                    <?php if(optional($order->user->userProfile)->phone): ?>
                                        <div class="text-sm text-gray-500 truncate">
                                            <i class="fas fa-phone text-gray-400 mr-1"></i>
                                            <?php echo e($order->user->userProfile->phone); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-left">
                            <div class="text-sm text-gray-900">
                                <div class="font-semibold text-lg"><?php echo e(number_format($order->grand_total ?? $order->total_price, 0, ',', '.')); ?>đ</div>
                                <?php if($order->discount_total > 0): ?>
                                    <div class="text-green-600 text-xs">
                                        <i class="fas fa-tag mr-1"></i>
                                        Giảm <?php echo e(number_format($order->discount_total)); ?>đ
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php
                                $statusConfig = [
                                    'pending' => ['label' => 'Chờ xử lý', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-clock'],
                                    'confirmed' => ['label' => 'Đã xác nhận', 'color' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-check'],
                                    'shipping' => ['label' => 'Đang giao', 'color' => 'bg-purple-100 text-purple-800', 'icon' => 'fas fa-truck'],
                                    'delivered' => ['label' => 'Đã giao', 'color' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
                                    'cancelled' => ['label' => 'Đã hủy', 'color' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-times-circle']
                                ];
                                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($config['color']); ?>">
                                <i class="<?php echo e($config['icon']); ?> mr-1"></i>
                                <?php echo e($config['label']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php
                                $paymentConfig = [
                                    'pending' => ['label' => 'Chờ thanh toán', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-clock'],
                                    'processing' => ['label' => 'Đang xử lý', 'color' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-spinner'],
                                    'partial' => ['label' => 'Một phần', 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-hourglass-half'],
                                    'completed' => ['label' => 'Đã thanh toán', 'color' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
                                    'refunded' => ['label' => 'Đã hoàn tiền', 'color' => 'bg-purple-100 text-purple-800', 'icon' => 'fas fa-undo'],
                                    'failed' => ['label' => 'Thất bại', 'color' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-exclamation-circle'],
                                    'cancelled' => ['label' => 'Đã hủy', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'fas fa-ban']
                                ];
                                $payConfig = $paymentConfig[$order->payment_status] ?? $paymentConfig['pending'];
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?php echo e($payConfig['color']); ?>">
                                <i class="<?php echo e($payConfig['icon']); ?> mr-1"></i>
                                <?php echo e($payConfig['label']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div><?php echo e($order->created_at->format('d/m/Y')); ?></div>
                            <div class="text-xs"><?php echo e($order->created_at->format('H:i')); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-4"></i>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Không có đơn hàng nào</h3>
                                <p class="text-gray-500">Hệ thống chưa có đơn hàng nào được tạo.</p>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/orders/partials/table.blade.php ENDPATH**/ ?>