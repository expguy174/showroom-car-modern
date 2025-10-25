<?php $__env->startSection('title', 'Danh sách yêu thích'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-30"></div>
        
        <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                    <i class="fas fa-heart text-white text-3xl"></i>
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                    Danh sách yêu thích
                </h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto leading-relaxed">
                    Lưu trữ và quản lý những sản phẩm bạn yêu thích để mua sau
                </p>
                
                <?php if($wishlistItems->count() > 0): ?>
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                        <div class="flex items-center bg-white/20 backdrop-blur-sm rounded-full px-6 py-3">
                            <i class="fas fa-gift text-white mr-3"></i>
                            <span class="text-white font-semibold"><?php echo e($wishlistItems->count()); ?> sản phẩm yêu thích</span>
                        </div>
                        <button id="clear-all-btn"
                                class="group bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 border border-white/30 hover:border-white/50">
                            <i class="fas fa-trash mr-2 group-hover:scale-110 transition-transform"></i>
                            Xóa tất cả
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Decorative elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-yellow-400/20 rounded-full blur-xl"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-pink-400/20 rounded-full blur-xl"></div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <?php if($wishlistItems->count() > 0): ?>
            <!-- Filters, Search and Sort (server-driven) -->
            <div class="mb-2" id="filter-section">
                <form id="filter-form" class="inventory-filter-form flex items-center gap-3 p-4 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/50" method="GET">
                    <div class="flex-none">
                        <select name="type" class="px-3 pr-8 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="" <?php echo e(empty($type ?? '') ? 'selected' : ''); ?>>Tất cả</option>
                            <option value="car_variant" <?php echo e(($type ?? '') === 'car_variant' ? 'selected' : ''); ?>>Xe hơi</option>
                            <option value="accessory" <?php echo e(($type ?? '') === 'accessory' ? 'selected' : ''); ?>>Phụ kiện</option>
                        </select>
                    </div>
                    <div class="flex-1"></div>
                    <div class="relative w-56 sm:w-64 md:w-80 mx-auto">
                        <input type="text" name="q" value="<?php echo e($q ?? ''); ?>" placeholder="Tìm theo tên..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    <div class="flex-1"></div>
                    <div class="flex-none">
                        <select name="sort" class="px-3 pr-8 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="newest" <?php echo e(($sort ?? 'newest') === 'newest' ? 'selected' : ''); ?>>Mới nhất</option>
                            <option value="oldest" <?php echo e(($sort ?? 'newest') === 'oldest' ? 'selected' : ''); ?>>Cũ nhất</option>
                        </select>
                    </div>
                    <input type="hidden" name="per_page" value="8" />
                </form>
                <div id="filter-progress" class="filter-progress-wrap w-full mt-2"><div class="filter-loading hidden" aria-live="polite" aria-busy="true"><div class="filter-loading-bar"></div></div></div>
            </div>

            <!-- Results wrapper to match product page behavior -->
            <div id="inv-results" class="inv-smooth ready">
            <!-- Wishlist Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8" id="wishlist-grid">
                <?php $__empty_1 = true; $__currentLoopData = $wishlistItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $product = $item->item ?? $item->product ?? null;
                        if (!$product) continue;
                        
                        // Normalize item type
                        $itemType = $item->item_type;
                        if ($itemType === 'App\\Models\\CarVariant') {
                            $itemType = 'car_variant';
                        }
                        
                        // Get proper item ID
                        $itemId = $item->item_id ?? $product->id ?? null;
                    ?>
                    
                    <?php if($itemType === 'car_variant' || $itemType === 'variant'): ?>
                        <div class="wishlist-item variant-card" 
                             data-type="car_variant" 
                             data-item-id="<?php echo e($itemId); ?>"
                             data-price="<?php echo e($product->price ?? 0); ?>" 
                             data-name="<?php echo e(strtolower($product->name ?? '')); ?>" 
                             data-date="<?php echo e($item->created_at ?? now()); ?>"
                             data-item-type="car_variant">
                            <?php if (isset($component)) { $__componentOriginal824738b82715670207de5c5e6da73d2b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal824738b82715670207de5c5e6da73d2b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.variant-card','data' => ['variant' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('variant-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal824738b82715670207de5c5e6da73d2b)): ?>
<?php $attributes = $__attributesOriginal824738b82715670207de5c5e6da73d2b; ?>
<?php unset($__attributesOriginal824738b82715670207de5c5e6da73d2b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal824738b82715670207de5c5e6da73d2b)): ?>
<?php $component = $__componentOriginal824738b82715670207de5c5e6da73d2b; ?>
<?php unset($__componentOriginal824738b82715670207de5c5e6da73d2b); ?>
<?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="wishlist-item accessory-card" 
                             data-type="accessory" 
                             data-item-id="<?php echo e($itemId); ?>"
                             data-price="<?php echo e($product->price ?? 0); ?>" 
                             data-name="<?php echo e(strtolower($product->name ?? '')); ?>" 
                             data-date="<?php echo e($item->created_at ?? now()); ?>"
                             data-item-type="accessory">
                            <?php if (isset($component)) { $__componentOriginal6cd074735567f427de5c0f1675008e14 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6cd074735567f427de5c0f1675008e14 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.accessory-card','data' => ['accessory' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('accessory-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['accessory' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6cd074735567f427de5c0f1675008e14)): ?>
<?php $attributes = $__attributesOriginal6cd074735567f427de5c0f1675008e14; ?>
<?php unset($__attributesOriginal6cd074735567f427de5c0f1675008e14); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6cd074735567f427de5c0f1675008e14)): ?>
<?php $component = $__componentOriginal6cd074735567f427de5c0f1675008e14; ?>
<?php unset($__componentOriginal6cd074735567f427de5c0f1675008e14); ?>
<?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full">
                        <div class="text-center py-16 w-full max-w-md mx-auto">
                            <i class="fas fa-search text-3xl text-gray-300"></i>
                            <?php $typeLabel = ($type ?? '') === 'accessory' ? 'Phụ kiện' : ((($type ?? '') === 'car_variant') ? 'Xe hơi' : 'Sản phẩm'); ?>
                            <div class="mt-3 font-semibold text-gray-900">Không có <?php echo e(strtolower($typeLabel)); ?> phù hợp</div>
                            <div class="text-sm text-gray-600">Không tìm thấy <?php echo e(strtolower($typeLabel)); ?> phù hợp. Hãy thử thay đổi bộ lọc hoặc từ khóa.</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if($wishlistItems->hasPages()): ?>
                <div id="wishlist-pagination" class="mb-6">
                    <?php echo $__env->make('components.pagination-modern', ['paginator' => $wishlistItems], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            <?php else: ?>
                <div id="wishlist-pagination" class="mb-6"></div>
            <?php endif; ?>
            </div>

            <!-- Empty State (Hidden by default) -->
            <div id="empty-state" class="hidden text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <i class="fas fa-heart text-gray-400 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!
                </p>
                <a href="<?php echo e(route('home')); ?>" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Khám phá sản phẩm
                </a>
            </div>

        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full mx-auto mb-8 flex items-center justify-center">
                    <i class="fas fa-heart text-gray-400 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-600 mb-4">Danh sách yêu thích trống</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Bạn chưa có sản phẩm nào trong danh sách yêu thích. Hãy khám phá các sản phẩm và thêm vào yêu thích!
                </p>
                <a href="<?php echo e(route('home')); ?>" 
                   class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Khám phá sản phẩm
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="message-container" class="fixed top-4 right-4 z-50"></div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Wishlist page DOM loaded');
    
    // Wishlist functionality is now handled by wishlist-manager.js
    // No need for WishlistPage class
    console.log('Wishlist page ready - using wishlist-manager.js');
});
</script>
<script>
// Auto-submit filter form: change selects immediately; search with debounce
(function(){
    const form = document.getElementById('filter-form') || document.getElementById('wishlist-filter-form');
    if (!form) return;
    const onChangeSubmit = (e) => { form.requestSubmit(); };
    const selects = form.querySelectorAll('select[name="type"], select[name="sort"], select[name="per_page"]');
    selects.forEach(sel => sel.addEventListener('change', onChangeSubmit));
    const search = form.querySelector('input[name="q"]');
    if (search) {
        let t = null;
        search.addEventListener('input', function(){
            clearTimeout(t);
            t = setTimeout(()=>{ form.requestSubmit(); }, 400);
        });
    }
})();

// AJAX filter + pagination (progressive enhancement)
(function(){
    const form = document.getElementById('filter-form') || document.getElementById('wishlist-filter-form');
    const grid = document.getElementById('wishlist-grid');
    const pager = document.getElementById('wishlist-pagination');
    if (!form || !grid || !pager || !window.history || !window.fetch) return;

    // Subtle top progress bar
    let progressEl = null, progressTimer = null;
    function startProgress(){
        if (progressEl) return;
        progressEl = document.createElement('div');
        progressEl.style.cssText = 'position:fixed;left:0;top:0;height:3px;width:0;background:#2563eb;z-index:9999;box-shadow:0 0 8px rgba(37,99,235,.6);transition:width .3s ease, opacity .2s ease';
        document.body.appendChild(progressEl);
        let w = 10;
        progressEl.style.width = w + '%';
        progressTimer = setInterval(()=>{
            w = Math.min(90, w + Math.max(1, (90 - w) * 0.1));
            progressEl.style.width = w + '%';
        }, 200);
    }
    function stopProgress(){
        if (!progressEl) return;
        clearInterval(progressTimer); progressTimer = null;
        progressEl.style.width = '100%';
        setTimeout(()=>{ if (progressEl){ progressEl.style.opacity = '0'; setTimeout(()=>{ progressEl?.remove(); progressEl=null; }, 200);} }, 200);
    }

    // Skeleton grid like product page
    function renderSkeleton(count){
        const frag = document.createDocumentFragment();
        for (let i=0;i<count;i++){
            const card = document.createElement('div');
            card.className = 'rounded-2xl bg-white shadow-md border border-white/60 overflow-hidden animate-pulse';
            card.innerHTML = `
                <div class="h-40 bg-gray-200"></div>
                <div class="p-4 space-y-3">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                    <div class="h-10 bg-gray-200 rounded"></div>
                </div>`;
            frag.appendChild(card);
        }
        return frag;
    }

    function startLoading(){
        // Match product: just dim wrapper and show small bar
        const wrap = document.getElementById('inv-results');
        if (wrap) wrap.classList.add('inv-smooth','dim');
        const barWrap = document.querySelector('#filter-progress');
        const bar = document.querySelector('#filter-progress .filter-loading');
        // Wrapper stays visible to preserve spacing; only show the bar
        if (bar) bar.classList.remove('hidden');
    }
    function endLoading(){
        const wrap = document.getElementById('inv-results');
        if (wrap) {
            wrap.classList.remove('dim');
            wrap.classList.add('ready');
            setTimeout(()=> wrap.classList.remove('inv-smooth','ready'), 240);
        }
        const barWrap = document.querySelector('#filter-progress');
        const bar = document.querySelector('#filter-progress .filter-loading');
        // Keep wrapper visible for spacing; hide only the bar
        if (bar) bar.classList.add('hidden');
        stopProgress();
    }

    function bindPager(container){
        container.querySelectorAll('a[href]')?.forEach(a => {
            a.addEventListener('click', function(ev){
                const url = a.getAttribute('href');
                if (!url) return;
                ev.preventDefault();
                navigate(url);
            });
        });
    }

    async function navigate(url){
        try {
            startLoading();
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newGrid = doc.getElementById('wishlist-grid');
            const newPager = doc.getElementById('wishlist-pagination');
            if (newGrid && newPager) {
                const newItemCount = newGrid.querySelectorAll('.wishlist-item').length;
                grid.innerHTML = newGrid.innerHTML;
                pager.innerHTML = newPager.innerHTML;
                // Update URL without full reload
                window.history.pushState({}, '', url);
                // Rebind pager links
                bindPager(pager);
                // Reinitialize any wishlist button bindings
                try { if (window.wishlistManager && typeof window.wishlistManager.checkWishlistStatus === 'function') window.wishlistManager.checkWishlistStatus(); } catch(_) {}
                // Fade-in effect
                grid.classList.add('fade-in'); setTimeout(()=> grid.classList.remove('fade-in'), 260);
                // If no items in the NEW grid, force empty state
                if (newItemCount === 0) {
                    const typeSel = (new URL(url, location.origin)).searchParams.get('type') || '';
                    const label = typeSel === 'accessory' ? 'Phụ kiện' : (typeSel === 'car_variant' ? 'Xe hơi' : 'Sản phẩm');
                    const emptyHtml = `
                        <div class="text-center py-16 w-full max-w-md mx-auto">
                            <i class="fas fa-search text-3xl text-gray-300"></i>
                            <div class="mt-3 font-semibold text-gray-900">Không có ${label.toLowerCase()} phù hợp</div>
                            <div class="text-sm text-gray-600">Không tìm thấy ${label.toLowerCase()} phù hợp. Hãy thử thay đổi bộ lọc hoặc từ khóa.</div>
                        </div>`;
                    grid.innerHTML = `<div class="col-span-full">${emptyHtml}</div>`;
                }
            } else {
                // If server did not render a grid (count==0), force empty state on client
                const paramsUrl = new URL(url, location.origin);
                const typeSel = paramsUrl.searchParams.get('type') || (form.querySelector('select[name="type"]')?.value || '');
                const label = typeSel === 'accessory' ? 'Phụ kiện' : (typeSel === 'car_variant' ? 'Xe hơi' : 'Sản phẩm');
                grid.innerHTML = `<div class="col-span-full"><div class="text-center py-16 w-full max-w-md mx-auto">
                        <i class="fas fa-search text-3xl text-gray-300"></i>
                        <div class="mt-3 font-semibold text-gray-900">Không có ${label.toLowerCase()} phù hợp</div>
                        <div class="text-sm text-gray-600">Không tìm thấy ${label.toLowerCase()} phù hợp. Hãy thử thay đổi bộ lọc hoặc từ khóa.</div>
                    </div></div>`;
                if (pager) { pager.innerHTML = ''; }
            }
        } catch(_){ } finally { endLoading(); }
    }

    // Intercept form submit to use AJAX
    form.addEventListener('submit', function(ev){
        ev.preventDefault();
        const params = new URLSearchParams(new FormData(form));
        const url = `${form.getAttribute('action') || window.location.pathname}?${params.toString()}`;
        navigate(url);
    });

    // Bind initial pager links
    bindPager(pager);
})();
</script>
<?php $__env->stopPush(); ?> 
<?php $__env->startPush('styles'); ?>
<style>
/* Hiệu ứng giống trang product */
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
@keyframes fadeSlideIn { from { opacity: 0; transform: translateY(6px);} to { opacity: 1; transform: translateY(0);} }
.fade-in { animation: fadeSlideIn .25s ease-out; }
.inv-smooth { transition: opacity .22s ease; will-change: opacity; }
.inv-smooth.dim { opacity: .6; }
.inv-smooth.ready { opacity: 1; }
@media (prefers-reduced-motion: reduce) {
  .fade-in { animation: none; }
  .inv-smooth { transition: none; }
}
.filter-loading { position: relative; height: 3px; margin-top: 8px; }
.filter-loading-bar { position: absolute; left: 0; top: 0; height: 100%; width: 30%; background: linear-gradient(90deg, #6366f1, #a78bfa); border-radius: 9999px; animation: slide 1s infinite ease-in-out; box-shadow: 0 0 12px rgba(99,102,241,.35); }
@keyframes slide { 0% { transform: translateX(0); } 50% { transform: translateX(230%); } 100% { transform: translateX(0); } }
/* Giữ khoảng cách cố định giữa filter và grid bằng chiều cao placeholder khi không có progress */
.filter-progress-wrap { min-height: 11px; }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/user/wishlist/index.blade.php ENDPATH**/ ?>