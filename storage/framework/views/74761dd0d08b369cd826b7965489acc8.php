<?php $__env->startSection('title', 'Trang quản trị hệ thống'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $user = Auth::user();
    $profile = $user->userProfile;
?>


<div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl p-4 sm:p-6 text-white mb-6">
    <div class="flex items-start sm:items-center justify-between flex-col sm:flex-row gap-4 sm:gap-0">
        <div class="flex-1">
            <h1 class="text-xl sm:text-2xl font-bold mb-2">
                👋 Xin chào, <?php echo e($profile->name ?? 'Admin'); ?>!
            </h1>
            <p class="text-blue-100 mb-3 text-sm sm:text-base">
                Chào mừng bạn đến với hệ thống quản trị Showroom
            </p>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 <?php echo e($user->getRoleColor()); ?> rounded-full text-sm font-medium">
                    <?php echo e($user->getRoleLabel()); ?>

                </span>
                <?php if($user->department): ?>
                <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                    <?php echo e($user->department); ?>

                </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="text-left sm:text-right w-full sm:w-auto">
            <div class="text-blue-100 text-xs sm:text-sm">Lần đăng nhập cuối</div>
            <div class="font-semibold text-sm sm:text-base"><?php echo e($user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Chưa có'); ?></div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600 text-sm font-medium">Người dùng</p>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="text-blue-600 hover:text-blue-800 text-xs">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2"><?php echo e(\App\Models\User::count()); ?></p>
                
                
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Khách hàng:</span>
                        <span class="font-medium"><?php echo e(\App\Models\User::where('role', 'user')->count()); ?></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Nhân viên:</span>
                        <span class="font-medium"><?php echo e(\App\Models\User::whereIn('role', ['admin', 'manager', 'sales_person', 'technician'])->count()); ?></span>
                    </div>
                </div>
                
                
                <?php
                    $newUsersThisWeek = \App\Models\User::where('created_at', '>=', now()->subDays(7))->count();
                    $newUsersLastWeek = \App\Models\User::whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])->count();
                    $userTrend = $newUsersLastWeek > 0 ? (($newUsersThisWeek - $newUsersLastWeek) / $newUsersLastWeek) * 100 : 0;
                ?>
                <div class="flex items-center mt-2">
                    <?php if($userTrend > 0): ?>
                        <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                        <span class="text-green-600 text-xs font-medium">+<?php echo e(number_format($userTrend, 1)); ?>%</span>
                    <?php elseif($userTrend < 0): ?>
                        <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                        <span class="text-red-600 text-xs font-medium"><?php echo e(number_format($userTrend, 1)); ?>%</span>
                    <?php else: ?>
                        <i class="fas fa-minus text-gray-400 text-xs mr-1"></i>
                        <span class="text-gray-500 text-xs">Không đổi</span>
                    <?php endif; ?>
                    <span class="text-gray-400 text-xs ml-1">so với tuần trước</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-4">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600 text-sm font-medium">Đơn hàng</p>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-green-600 hover:text-green-800 text-xs">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <p class="text-3xl font-bold text-gray-900 mb-2"><?php echo e(\App\Models\Order::count()); ?></p>
                
                
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Chờ xử lý:</span>
                        <span class="font-medium text-yellow-600"><?php echo e(\App\Models\Order::where('status', 'pending')->count()); ?></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Hoàn thành:</span>
                        <span class="font-medium text-green-600"><?php echo e(\App\Models\Order::where('status', 'delivered')->count()); ?></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Đang xử lý:</span>
                        <span class="font-medium text-blue-600"><?php echo e(\App\Models\Order::whereIn('status', ['confirmed', 'shipping'])->count()); ?></span>
                    </div>
                </div>
                
                
                <?php
                    $newOrdersThisWeek = \App\Models\Order::where('created_at', '>=', now()->subDays(7))->count();
                    $newOrdersLastWeek = \App\Models\Order::whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])->count();
                    $orderTrend = $newOrdersLastWeek > 0 ? (($newOrdersThisWeek - $newOrdersLastWeek) / $newOrdersLastWeek) * 100 : 0;
                ?>
                <div class="flex items-center mt-2">
                    <?php if($orderTrend > 0): ?>
                        <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                        <span class="text-green-600 text-xs font-medium">+<?php echo e(number_format($orderTrend, 1)); ?>%</span>
                    <?php elseif($orderTrend < 0): ?>
                        <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                        <span class="text-red-600 text-xs font-medium"><?php echo e(number_format($orderTrend, 1)); ?>%</span>
                    <?php else: ?>
                        <i class="fas fa-minus text-gray-400 text-xs mr-1"></i>
                        <span class="text-gray-500 text-xs">Không đổi</span>
                    <?php endif; ?>
                    <span class="text-gray-400 text-xs ml-1">so với tuần trước</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-4">
                <i class="fas fa-receipt text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600 text-sm font-medium">Doanh thu tháng</p>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-purple-600 hover:text-purple-800 text-xs">
                        <i class="fas fa-chart-line"></i>
                    </a>
                </div>
                <?php
                    $monthlyRevenueValue = $monthlyRevenue ?? 0;
                    if ($monthlyRevenueValue >= 1000000000) {
                        $displayRevenue = number_format($monthlyRevenueValue / 1000000000, 1) . ' Tỷ Đ';
                    } elseif ($monthlyRevenueValue >= 1000000) {
                        $displayRevenue = number_format($monthlyRevenueValue / 1000000, 0) . ' Tr Đ';
                    } else {
                        $displayRevenue = number_format($monthlyRevenueValue) . ' Đ';
                    }
                ?>
                <p class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($displayRevenue); ?></p>
                
                
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Tuần này:</span>
                        <?php
                            $weeklyRevenue = \App\Models\Order::where('created_at', '>=', now()->subDays(7))->where('status', 'delivered')->sum('grand_total');
                            if ($weeklyRevenue >= 1000000000) {
                                $weeklyDisplay = number_format($weeklyRevenue / 1000000000, 1) . ' Tỷ Đ';
                            } elseif ($weeklyRevenue >= 1000000) {
                                $weeklyDisplay = number_format($weeklyRevenue / 1000000, 0) . ' Tr Đ';
                            } else {
                                $weeklyDisplay = number_format($weeklyRevenue) . ' Đ';
                            }
                        ?>
                        <span class="font-medium"><?php echo e($weeklyDisplay); ?></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Hôm nay:</span>
                        <?php
                            $dailyRevenue = \App\Models\Order::whereDate('created_at', today())->where('status', 'delivered')->sum('grand_total');
                            if ($dailyRevenue >= 1000000000) {
                                $dailyDisplay = number_format($dailyRevenue / 1000000000, 1) . ' Tỷ Đ';
                            } elseif ($dailyRevenue >= 1000000) {
                                $dailyDisplay = number_format($dailyRevenue / 1000000, 0) . ' Tr Đ';
                            } else {
                                $dailyDisplay = number_format($dailyRevenue) . ' Đ';
                            }
                        ?>
                        <span class="font-medium"><?php echo e($dailyDisplay); ?></span>
                    </div>
                </div>
                
                
                <?php
                    $thisMonthRevenue = \App\Models\Order::whereMonth('created_at', now()->month)->where('status', 'delivered')->sum('grand_total');
                    $lastMonthRevenue = \App\Models\Order::whereMonth('created_at', now()->subMonth()->month)->where('status', 'delivered')->sum('grand_total');
                    $revenueTrend = $lastMonthRevenue > 0 ? (($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
                ?>
                <div class="flex items-center mt-2">
                    <?php if($revenueTrend > 0): ?>
                        <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                        <span class="text-green-600 text-xs font-medium">+<?php echo e(number_format($revenueTrend, 1)); ?>%</span>
                    <?php elseif($revenueTrend < 0): ?>
                        <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                        <span class="text-red-600 text-xs font-medium"><?php echo e(number_format($revenueTrend, 1)); ?>%</span>
                    <?php else: ?>
                        <i class="fas fa-minus text-gray-400 text-xs mr-1"></i>
                        <span class="text-gray-500 text-xs">Không đổi</span>
                    <?php endif; ?>
                    <span class="text-gray-400 text-xs ml-1">so với tháng trước</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center ml-4">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600 text-sm font-medium">Sản phẩm</p>
                    <a href="<?php echo e(route('admin.carvariants.index')); ?>" class="text-orange-600 hover:text-orange-800 text-xs">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <?php
                    $totalCarVariants = \App\Models\CarVariant::count();
                    $totalAccessories = \App\Models\Accessory::count() ?? 0;
                    $totalProducts = $totalCarVariants + $totalAccessories;
                ?>
                <p class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($totalProducts); ?></p>
                
                
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Phiên bản xe:</span>
                        <span class="font-medium"><?php echo e($totalCarVariants); ?></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-500">Phụ kiện:</span>
                        <span class="font-medium"><?php echo e($totalAccessories); ?></span>
                    </div>
                </div>
                
                
                <?php
                    $activeCarVariants = \App\Models\CarVariant::where('is_active', true)->count();
                    $inactiveCarVariants = \App\Models\CarVariant::where('is_active', false)->count();
                    $activeAccessories = \App\Models\Accessory::where('is_active', true)->count() ?? 0;
                    $inactiveAccessories = \App\Models\Accessory::where('is_active', false)->count() ?? 0;
                    
                    $totalActive = $activeCarVariants + $activeAccessories;
                    $totalInactive = $inactiveCarVariants + $inactiveAccessories;
                ?>
                <div class="flex items-center mt-2">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                        <span class="text-green-600 text-xs font-medium"><?php echo e($totalActive); ?> hoạt động</span>
                    </div>
                    <div class="flex items-center ml-3">
                        <div class="w-2 h-2 bg-gray-400 rounded-full mr-1"></div>
                        <span class="text-gray-500 text-xs"><?php echo e($totalInactive); ?> tạm dừng</span>
                    </div>
                </div>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center ml-4">
                <i class="fas fa-car text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 mb-6">
    
    <?php if($user->hasRole(['admin', 'manager'])): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-cogs text-purple-500 mr-2"></i>
            Quản lý hệ thống
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="<?php echo e(route('admin.carvariants.index')); ?>" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                <i class="fas fa-car text-purple-600 mr-3 sm:mr-4"></i>
                <span class="text-sm font-medium text-purple-900">Xe hơi</span>
            </a>
            <a href="<?php echo e(route('admin.accessories.index')); ?>" class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                <i class="fas fa-puzzle-piece text-orange-600 mr-3 sm:mr-4"></i>
                <span class="text-sm font-medium text-orange-900">Phụ kiện</span>
            </a>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                <i class="fas fa-users text-blue-600 mr-3 sm:mr-4"></i>
                <span class="text-sm font-medium text-blue-900">Người dùng</span>
            </a>
            <a href="<?php echo e(route('admin.analytics.dashboard')); ?>" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <i class="fas fa-chart-bar text-green-600 mr-3 sm:mr-4"></i>
                <span class="text-sm font-medium text-green-900">Báo cáo</span>
            </a>
        </div>
    </div>
    <?php endif; ?>

    
    <?php if($user->hasRole(['admin', 'manager', 'sales_person'])): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-handshake text-blue-500 mr-2"></i>
            Dịch vụ khách hàng
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                <i class="fas fa-receipt text-indigo-600 mr-3 sm:mr-4"></i>
                <span class="text-sm font-medium text-indigo-900">Đơn hàng</span>
            </a>
            <a href="<?php echo e(route('admin.test-drives.index')); ?>" class="flex items-center p-3 bg-teal-50 hover:bg-teal-100 rounded-lg transition-colors">
                <i class="fas fa-car-side text-teal-600 mr-3 sm:mr-4"></i>
                <span class="text-sm font-medium text-teal-900">Lái thử</span>
            </a>
            <a href="<?php echo e(route('admin.service-appointments.index')); ?>" class="flex items-center p-3 bg-pink-50 hover:bg-pink-100 rounded-lg transition-colors">
                <i class="fas fa-calendar-check text-pink-600 mr-3 sm:mr-4"></i>
                <span class="text-sm font-medium text-pink-900">Lịch hẹn</span>
            </a>
            <a href="<?php echo e(route('admin.service-appointments.index')); ?>" class="flex items-center p-3 bg-cyan-50 hover:bg-cyan-100 rounded-lg transition-colors">
                <i class="fas fa-tools text-cyan-600 mr-3 sm:mr-4"></i>
                <span class="text-sm font-medium text-cyan-900">Dịch vụ</span>
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>


<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">
                <i class="fas fa-shopping-cart text-blue-600 mr-2"></i>
                <span class="hidden sm:inline">Đơn hàng gần đây</span>
                <span class="sm:hidden">Đơn hàng</span>
            </h3>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
                <span class="hidden sm:inline">Xem tất cả</span>
                <span class="sm:hidden">Tất cả</span>
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <div class="space-y-4">
            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:bg-gray-50 transition-colors">
                
                <div class="block sm:hidden">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center">
                            <?php
                                $statusConfig = [
                                    'pending' => ['color' => 'bg-yellow-100 text-yellow-600', 'icon' => 'fas fa-clock'],
                                    'confirmed' => ['color' => 'bg-blue-100 text-blue-600', 'icon' => 'fas fa-check'],
                                    'shipping' => ['color' => 'bg-purple-100 text-purple-600', 'icon' => 'fas fa-truck'],
                                    'delivered' => ['color' => 'bg-green-100 text-green-600', 'icon' => 'fas fa-check-circle'],
                                    'cancelled' => ['color' => 'bg-red-100 text-red-600', 'icon' => 'fas fa-times-circle']
                                ];
                                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                $statusLabels = [
                                    'pending' => 'Chờ xử lý',
                                    'confirmed' => 'Đã xác nhận',
                                    'shipping' => 'Đang giao',
                                    'delivered' => 'Đã giao',
                                    'cancelled' => 'Đã hủy'
                                ];
                            ?>
                            <div class="w-8 h-8 <?php echo e($config['color']); ?> rounded-full flex items-center justify-center mr-3">
                                <i class="<?php echo e($config['icon']); ?> text-xs"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">#<?php echo e($order->order_number ?? $order->id); ?></p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($config['color']); ?>">
                                    <?php echo e($statusLabels[$order->status] ?? ucfirst($order->status)); ?>

                                </span>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-base text-gray-900"><?php echo e(number_format($order->grand_total ?: $order->total_price ?: 0)); ?> Đ</p>
                            <p class="text-xs text-gray-500"><?php echo e($order->created_at->format('d/m H:i')); ?></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user mr-2"></i>
                            <span class="truncate"><?php echo e(optional($order->user->userProfile)->name ?? optional($order->billingAddress)->contact_name ?? 'Khách vãng lai'); ?></span>
                        </div>
                        <?php if($order->payment_status): ?>
                            <?php
                                $paymentConfig = [
                                    'pending' => ['label' => 'Chờ TT', 'color' => 'text-yellow-600 bg-yellow-50'],
                                    'processing' => ['label' => 'Xử lý', 'color' => 'text-blue-600 bg-blue-50'],
                                    'completed' => ['label' => 'Đã TT', 'color' => 'text-green-600 bg-green-50'],
                                    'failed' => ['label' => 'Lỗi', 'color' => 'text-red-600 bg-red-50'],
                                    'cancelled' => ['label' => 'Hủy', 'color' => 'text-gray-600 bg-gray-50']
                                ];
                                $payConfig = $paymentConfig[$order->payment_status] ?? ['label' => 'N/A', 'color' => 'text-gray-600 bg-gray-50'];
                            ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($payConfig['color']); ?>">
                                <?php echo e($payConfig['label']); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex items-center justify-between mt-3">
                        <?php if($order->items && $order->items->count() > 0): ?>
                            <p class="text-xs text-gray-400"><?php echo e($order->items->count()); ?> sản phẩm</p>
                        <?php else: ?>
                            <span></span>
                        <?php endif; ?>
                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>" 
                           class="inline-flex items-center px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-medium hover:bg-blue-100 transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            Xem
                        </a>
                    </div>
                </div>

                
                <div class="hidden sm:flex items-center justify-between">
                    <div class="flex items-center flex-1">
                        
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-10 h-10 <?php echo e($config['color']); ?> rounded-full flex items-center justify-center">
                                <i class="<?php echo e($config['icon']); ?>"></i>
                            </div>
                        </div>
                        
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center mb-1">
                                <p class="font-semibold text-gray-900 mr-2">#<?php echo e($order->order_number ?? $order->id); ?></p>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($config['color']); ?>">
                                    <?php echo e($statusLabels[$order->status] ?? ucfirst($order->status)); ?>

                                </span>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-600 mb-1">
                                <i class="fas fa-user mr-2"></i>
                                <span class="truncate"><?php echo e(optional($order->user->userProfile)->name ?? optional($order->billingAddress)->contact_name ?? 'Khách vãng lai'); ?></span>
                            </div>
                            
                            <?php if($order->user && $order->user->userProfile && $order->user->userProfile->phone): ?>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-phone mr-2"></i>
                                <span><?php echo e($order->user->userProfile->phone); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="font-bold text-lg text-gray-900"><?php echo e(number_format($order->grand_total ?: $order->total_price ?: 0)); ?> Đ</p>
                            
                            <?php if($order->payment_status): ?>
                                <?php
                                    $paymentConfig = [
                                        'pending' => ['label' => 'Chờ thanh toán', 'color' => 'text-yellow-600 bg-yellow-50'],
                                        'processing' => ['label' => 'Đang xử lý', 'color' => 'text-blue-600 bg-blue-50'],
                                        'completed' => ['label' => 'Đã thanh toán', 'color' => 'text-green-600 bg-green-50'],
                                        'failed' => ['label' => 'Thất bại', 'color' => 'text-red-600 bg-red-50'],
                                        'cancelled' => ['label' => 'Đã hủy', 'color' => 'text-gray-600 bg-gray-50']
                                    ];
                                    $payConfig = $paymentConfig[$order->payment_status] ?? ['label' => ucfirst($order->payment_status), 'color' => 'text-gray-600 bg-gray-50'];
                                ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($payConfig['color']); ?> mt-1">
                                    <?php echo e($payConfig['label']); ?>

                                </span>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($order->created_at->format('d/m/Y H:i')); ?></p>
                            <?php if($order->items && $order->items->count() > 0): ?>
                                <p class="text-xs text-gray-400"><?php echo e($order->items->count()); ?> sản phẩm</p>
                            <?php endif; ?>
                        </div>
                        
                        
                        <div class="flex items-center space-x-2">
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" 
                               class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-full transition-colors"
                               title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-cart text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">Chưa có đơn hàng nào</h4>
                <p class="text-gray-500">Các đơn hàng mới sẽ hiển thị ở đây</p>
            </div>
            <?php endif; ?>
        </div>
        
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-info-circle text-gray-500 mr-2"></i>
            <span class="hidden sm:inline">Thông tin hệ thống</span>
            <span class="sm:hidden">Hệ thống</span>
        </h3>
        <div class="space-y-4">
            
            
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm sm:text-base">Phiên bản hệ thống</span>
                    <span class="font-medium text-sm sm:text-base">v1.0.0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm sm:text-base">Môi trường</span>
                    <span class="font-medium text-sm sm:text-base <?php echo e(config('app.env') === 'production' ? 'text-green-600' : 'text-yellow-600'); ?>">
                        <?php echo e(config('app.env') === 'production' ? 'Sản xuất' : 'Phát triển'); ?>

                    </span>
                </div>
                
                
                <?php if($user->employee_id): ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Mã nhân viên</span>
                    <span class="font-medium"><?php echo e($user->employee_id); ?></span>
                </div>
                <?php endif; ?>
                <?php if($user->hire_date): ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ngày bắt đầu làm việc</span>
                    <span class="font-medium"><?php echo e(\Carbon\Carbon::parse($user->hire_date)->format('d/m/Y')); ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h4 class="font-medium text-gray-900 mb-3">Tổng quan kinh doanh</h4>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Showroom hoạt động</span>
                        <span class="font-medium text-green-600">
                            <i class="fas fa-store text-green-500 text-xs mr-1"></i>
                            <?php echo e(\App\Models\Showroom::where('is_active', true)->count() ?? 3); ?> chi nhánh
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nhân viên hệ thống</span>
                        <span class="font-medium text-blue-600">
                            <i class="fas fa-users text-blue-500 text-xs mr-1"></i>
                            <?php echo e(\App\Models\User::whereIn('role', ['admin', 'manager', 'sales_person', 'technician'])->count() ?? 6); ?> người
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Xe sẵn sàng</span>
                        <span class="font-medium text-purple-600">
                            <i class="fas fa-car text-purple-500 text-xs mr-1"></i>
                            <?php echo e(\App\Models\CarVariant::where('is_active', true)->count() ?? 45); ?> phiên bản
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Dịch vụ bảo dưỡng</span>
                        <span class="font-medium text-orange-600">
                            <i class="fas fa-tools text-orange-500 text-xs mr-1"></i>
                            <?php echo e(\App\Models\Service::where('is_active', true)->where('category', 'maintenance')->count() ?? 5); ?> dịch vụ
                        </span>
                    </div>
                </div>
            </div>
            
            
            <div class="mt-6 pt-4 border-t border-gray-200">
                <h4 class="font-medium text-gray-900 mb-3">Phiên làm việc</h4>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="font-medium text-green-600">
                            <i class="fas fa-circle text-green-500 text-xs mr-1"></i>
                            Đang hoạt động
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Quyền truy cập:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($user->getRoleColor()); ?>">
                            <?php echo e($user->getRoleLabel()); ?>

                        </span>
                    </div>
                    <?php if($user->department): ?>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Phòng ban:</span>
                        <span class="font-medium"><?php echo e($user->department); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Dashboard real-time updates
function updateDashboardStats() {
    fetch('<?php echo e(route("admin.dashboard.stats")); ?>')
        .then(response => response.json())
        .then(data => {
            console.log('Dashboard stats updated:', data);
            // Update stats elements here if needed
        })
        .catch(error => console.error('Error updating stats:', error));
}

// Update stats every 5 minutes
setInterval(updateDashboardStats, 300000);

// Add loading states for better UX
document.addEventListener('DOMContentLoaded', function() {
    // Add shimmer effect to stats cards while loading
    const statsCards = document.querySelectorAll('.bg-white.rounded-xl.shadow-sm');
    statsCards.forEach(card => {
        card.classList.add('transition-all', 'duration-200', 'hover:shadow-md');
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>