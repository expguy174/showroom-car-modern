<?php $__env->startSection('title', 'Khuyến mãi & Ưu đãi'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-3 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 mb-3">🎉 Khuyến mãi đặc biệt</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-4">Khám phá các ưu đãi hấp dẫn dành riêng cho bạn. Sao chép mã và sử dụng khi mua xe!</p>
        <?php if($promotions->total() > 0): ?>
            <p class="text-sm text-gray-500">
                Có <span class="font-semibold text-orange-600"><?php echo e($promotions->total()); ?></span> mã khuyến mãi đang có sẵn
            </p>
        <?php endif; ?>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-10">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <form method="GET">
                <!-- Single Row Layout -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                    <!-- Search Field -->
                    <div class="lg:col-span-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-2 text-orange-500"></i>Tìm kiếm khuyến mãi
                        </label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               placeholder="Nhập tên khuyến mãi, mã giảm giá..." 
                               class="w-full pl-4 pr-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:ring-orange-500 text-gray-900 placeholder-gray-500">
                    </div>
                    
                    <!-- Filter Field -->
                    <div class="lg:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-filter mr-2 text-orange-500"></i>Loại khuyến mãi
                        </label>
                        <select name="type" 
                                class="w-full py-3 px-4 rounded-xl border border-gray-300 focus:border-orange-500 focus:ring-orange-500 focus:ring-2 text-gray-900">
                            <option value="">🏷️ Tất cả loại</option>
                            <option value="percentage" <?php if(request('type') === 'percentage'): echo 'selected'; endif; ?>>📊 Giảm theo %</option>
                            <option value="fixed_amount" <?php if(request('type') === 'fixed_amount'): echo 'selected'; endif; ?>>💰 Giảm cố định</option>
                            <option value="free_shipping" <?php if(request('type') === 'free_shipping'): echo 'selected'; endif; ?>>🚚 Miễn phí ship</option>
                            <option value="brand_specific" <?php if(request('type') === 'brand_specific'): echo 'selected'; endif; ?>>🚗 Theo thương hiệu</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="lg:col-span-3">
                        <div class="flex gap-2">
                            <button type="submit" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-orange-600 text-white rounded-xl font-semibold hover:bg-orange-700 transition-colors duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-search mr-2"></i>Tìm kiếm
                            </button>
                            <?php if(request()->hasAny(['search', 'type'])): ?>
                                <a href="<?php echo e(route('user.promotions.index')); ?>" 
                                   class="inline-flex items-center justify-center px-3 py-3 bg-gray-100 text-gray-600 rounded-xl font-medium hover:bg-gray-200 transition-colors duration-200">
                                    <i class="fas fa-refresh"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Active Filters Display -->
        <?php if(request()->hasAny(['search', 'type'])): ?>
            <div class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-sm text-gray-600">Bộ lọc đang áp dụng:</span>
                <?php if(request('search')): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        <i class="fas fa-search mr-1"></i>"<?php echo e(request('search')); ?>"
                    </span>
                <?php endif; ?>
                <?php if(request('type')): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-filter mr-1"></i>
                        <?php switch(request('type')):
                            case ('percentage'): ?> Giảm theo % <?php break; ?>
                            <?php case ('fixed_amount'): ?> Giảm cố định <?php break; ?>
                            <?php case ('free_shipping'): ?> Miễn phí ship <?php break; ?>
                            <?php case ('brand_specific'): ?> Theo thương hiệu <?php break; ?>
                            <?php default: ?> <?php echo e(request('type')); ?>

                        <?php endswitch; ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Promotions Grid -->
    <?php if($promotions->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promotion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="pattern-<?php echo e($promotion->id); ?>" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                                    <circle cx="10" cy="10" r="2" fill="currentColor"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#pattern-<?php echo e($promotion->id); ?>)"/>
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <!-- Type Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs font-medium">
                                <?php switch($promotion->type):
                                    case ('percentage'): ?> 
                                        Giảm <?php echo e($promotion->discount_value); ?>%
                                        <?php if($promotion->max_discount_amount): ?>
                                            <span class="block text-[10px] opacity-75">Tối đa <?php echo e(number_format($promotion->max_discount_amount, 0, ',', '.')); ?>đ</span>
                                        <?php endif; ?>
                                        <?php break; ?>
                                    <?php case ('fixed_amount'): ?> Giảm <?php echo e(number_format($promotion->discount_value, 0, ',', '.')); ?>đ <?php break; ?>
                                    <?php case ('free_shipping'): ?> Miễn phí ship <?php break; ?>
                                    <?php case ('brand_specific'): ?>
                                        Giảm <?php echo e($promotion->discount_value); ?>%
                                        <?php if($promotion->max_discount_amount): ?>
                                            <span class="block text-[10px] opacity-75">Tối đa <?php echo e(number_format($promotion->max_discount_amount, 0, ',', '.')); ?>đ</span>
                                        <?php endif; ?>
                                        <?php break; ?>
                                <?php endswitch; ?>
                            </span>
                            <?php if($promotion->usage_limit): ?>
                                <span class="text-xs opacity-75">
                                    <?php echo e($promotion->usage_limit - $promotion->usage_count); ?> lượt còn lại
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-bold mb-2"><?php echo e($promotion->name); ?></h3>
                        
                        <!-- Description -->
                        <?php if($promotion->description): ?>
                            <p class="text-sm opacity-90 mb-4 line-clamp-2"><?php echo e($promotion->description); ?></p>
                        <?php endif; ?>

                        <!-- Code -->
                        <div class="bg-white bg-opacity-20 rounded-lg p-3 mb-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs opacity-75 mb-1">Mã khuyến mãi</p>
                                    <p class="font-mono font-bold text-lg"><?php echo e($promotion->code); ?></p>
                                </div>
                                <button onclick="copyCode('<?php echo e($promotion->code); ?>')" 
                                        class="p-2 bg-white bg-opacity-20 rounded-lg hover:bg-opacity-30 transition-colors">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Min Order & Expiry -->
                        <div class="space-y-2 text-sm opacity-90">
                            <?php if($promotion->min_order_amount): ?>
                                <p><i class="fas fa-shopping-cart w-4"></i> Đơn tối thiểu: <?php echo e(number_format($promotion->min_order_amount, 0, ',', '.')); ?>đ</p>
                            <?php endif; ?>
                            <?php if($promotion->end_date): ?>
                                <p><i class="fas fa-clock w-4"></i> Hết hạn: <?php echo e($promotion->end_date->format('d/m/Y')); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-4 pt-4 border-t border-white border-opacity-20">
                            <div class="flex items-center justify-between text-xs">
                                <span class="px-2 py-1 bg-white bg-opacity-20 rounded-full">
                                    <?php echo e($promotion->status_text); ?>

                                </span>
                                <?php if($promotion->usage_limit): ?>
                                    <span class="opacity-75">
                                        Còn <?php echo e($promotion->usage_limit - $promotion->usage_count); ?> lượt
                                    </span>
                                <?php else: ?>
                                    <span class="opacity-75">Không giới hạn</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <?php if($promotions->hasPages()): ?>
            <div class="mt-16 flex justify-center">
                <div class="flex items-center space-x-2">
                    <?php if($promotions->onFirstPage()): ?>
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left mr-2"></i>Trước
                        </span>
                    <?php else: ?>
                        <a href="<?php echo e($promotions->appends(request()->query())->previousPageUrl()); ?>" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-chevron-left mr-2"></i>Trước
                        </a>
                    <?php endif; ?>

                    <?php $__currentLoopData = $promotions->appends(request()->query())->getUrlRange(1, $promotions->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $promotions->currentPage()): ?>
                            <span class="px-4 py-2 bg-orange-600 text-white rounded-lg font-semibold"><?php echo e($page); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200"><?php echo e($page); ?></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if($promotions->hasMorePages()): ?>
                        <a href="<?php echo e($promotions->appends(request()->query())->nextPageUrl()); ?>" class="px-4 py-2 text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Sau<i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    <?php else: ?>
                        <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                            Sau<i class="fas fa-chevron-right ml-2"></i>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Pagination Info -->
            <div class="mt-4 text-center text-sm text-gray-500">
                Hiển thị <?php echo e($promotions->firstItem()); ?>-<?php echo e($promotions->lastItem()); ?> 
                trong tổng số <?php echo e($promotions->total()); ?> mã khuyến mãi
            </div>
        <?php endif; ?>

        <!-- How to use -->
        <div class="mt-12 bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6 text-center">
            <h3 class="text-lg font-bold text-gray-900 mb-2">💡 Cách sử dụng mã khuyến mãi</h3>
            <p class="text-gray-600 mb-4">Nhấn vào nút <i class="fas fa-copy mx-1"></i> để sao chép mã, sau đó sử dụng khi thanh toán đơn hàng</p>
            <div class="flex flex-wrap justify-center gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span>Áp dụng tự động khi thanh toán</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-blue-500"></i>
                    <span>Có thời hạn sử dụng</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-purple-500"></i>
                    <span>Có điều kiện đơn tối thiểu</span>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có khuyến mãi nào</h3>
            <p class="text-gray-500 mb-6">Hiện tại chưa có chương trình khuyến mãi nào phù hợp với tìm kiếm của bạn.</p>
            <a href="<?php echo e(route('user.promotions.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-medium">
                <i class="fas fa-refresh"></i> Xem tất cả
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-full opacity-0 transition-all duration-300 z-50">
    <div class="flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span id="toast-message">Đã sao chép mã khuyến mãi!</span>
    </div>
</div>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        showToast('Đã sao chép mã: ' + code);
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = code;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Đã sao chép mã: ' + code);
    });
}

function showToast(message) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    
    toastMessage.textContent = message;
    toast.classList.remove('translate-y-full', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');
    
    setTimeout(() => {
        toast.classList.add('translate-y-full', 'opacity-0');
        toast.classList.remove('translate-y-0', 'opacity-100');
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/promotions/index.blade.php ENDPATH**/ ?>