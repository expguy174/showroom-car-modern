<?php $__env->startSection('title', 'Quản lý khuyến mãi'); ?>

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

<div class="space-y-3 sm:space-y-4 lg:space-y-6 px-2 sm:px-0">
    
    <?php if (isset($component)) { $__componentOriginalcb19cb35a534439097b02b8af91726ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb19cb35a534439097b02b8af91726ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Quản lý khuyến mãi','description' => 'Quản lý các chương trình khuyến mãi và ưu đãi','icon' => 'fas fa-tags']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Quản lý khuyến mãi','description' => 'Quản lý các chương trình khuyến mãi và ưu đãi','icon' => 'fas fa-tags']); ?>
        <a href="<?php echo e(route('admin.promotions.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Thêm khuyến mãi
        </a>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcb19cb35a534439097b02b8af91726ee)): ?>
<?php $attributes = $__attributesOriginalcb19cb35a534439097b02b8af91726ee; ?>
<?php unset($__attributesOriginalcb19cb35a534439097b02b8af91726ee); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcb19cb35a534439097b02b8af91726ee)): ?>
<?php $component = $__componentOriginalcb19cb35a534439097b02b8af91726ee; ?>
<?php unset($__componentOriginalcb19cb35a534439097b02b8af91726ee); ?>
<?php endif; ?>

    
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
        <?php if (isset($component)) { $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Tổng khuyến mãi','value' => $totalPromotions ?? 0,'icon' => 'fas fa-tags','color' => 'blue','description' => 'Tất cả chương trình','dataStat' => 'total']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tổng khuyến mãi','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalPromotions ?? 0),'icon' => 'fas fa-tags','color' => 'blue','description' => 'Tất cả chương trình','dataStat' => 'total']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Hoạt động','value' => $activePromotions ?? 0,'icon' => 'fas fa-play-circle','color' => 'green','description' => 'Đang áp dụng','dataStat' => 'active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Hoạt động','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($activePromotions ?? 0),'icon' => 'fas fa-play-circle','color' => 'green','description' => 'Đang áp dụng','dataStat' => 'active']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Tạm dừng','value' => $inactivePromotions ?? 0,'icon' => 'fas fa-pause-circle','color' => 'orange','description' => 'Không hoạt động','dataStat' => 'inactive']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tạm dừng','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inactivePromotions ?? 0),'icon' => 'fas fa-pause-circle','color' => 'orange','description' => 'Không hoạt động','dataStat' => 'inactive']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Hết hạn','value' => $expiredPromotions ?? 0,'icon' => 'fas fa-exclamation-triangle','color' => 'red','description' => 'Đã kết thúc','dataStat' => 'expired']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Hết hạn','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($expiredPromotions ?? 0),'icon' => 'fas fa-exclamation-triangle','color' => 'red','description' => 'Đã kết thúc','dataStat' => 'expired']); ?>
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

    
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_auto_auto] gap-4 items-end"
              data-base-url="<?php echo e(route('admin.promotions.index')); ?>">
            
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <?php if (isset($component)) { $__componentOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.search-input','data' => ['name' => 'search','placeholder' => 'Tên khuyến mãi, mã code...','value' => request('search'),'callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','placeholder' => 'Tên khuyến mãi, mã code...','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('search')),'callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5cb383ddee3a6dc44b6e82e90e14b261)): ?>
<?php $attributes = $__attributesOriginal5cb383ddee3a6dc44b6e82e90e14b261; ?>
<?php unset($__attributesOriginal5cb383ddee3a6dc44b6e82e90e14b261); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5cb383ddee3a6dc44b6e82e90e14b261)): ?>
<?php $component = $__componentOriginal5cb383ddee3a6dc44b6e82e90e14b261; ?>
<?php unset($__componentOriginal5cb383ddee3a6dc44b6e82e90e14b261); ?>
<?php endif; ?>
            </div>
            
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <?php if (isset($component)) { $__componentOriginal42eccf6ae0cbd0d224265b5df2422179 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42eccf6ae0cbd0d224265b5df2422179 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.custom-dropdown','data' => ['name' => 'status','options' => [
                        ['value' => 'active', 'text' => 'Hoạt động'],
                        ['value' => 'inactive', 'text' => 'Tạm dừng'],
                        ['value' => 'expired', 'text' => 'Hết hạn']
                    ],'placeholder' => 'Tất cả','optionValue' => 'value','optionText' => 'text','selected' => request('status'),'onchange' => 'loadPromotionsFromDropdown','maxVisible' => 4,'searchable' => false,'width' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.custom-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        ['value' => 'active', 'text' => 'Hoạt động'],
                        ['value' => 'inactive', 'text' => 'Tạm dừng'],
                        ['value' => 'expired', 'text' => 'Hết hạn']
                    ]),'placeholder' => 'Tất cả','optionValue' => 'value','optionText' => 'text','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('status')),'onchange' => 'loadPromotionsFromDropdown','maxVisible' => 4,'searchable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'width' => 'w-full']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal42eccf6ae0cbd0d224265b5df2422179)): ?>
<?php $attributes = $__attributesOriginal42eccf6ae0cbd0d224265b5df2422179; ?>
<?php unset($__attributesOriginal42eccf6ae0cbd0d224265b5df2422179); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal42eccf6ae0cbd0d224265b5df2422179)): ?>
<?php $component = $__componentOriginal42eccf6ae0cbd0d224265b5df2422179; ?>
<?php unset($__componentOriginal42eccf6ae0cbd0d224265b5df2422179); ?>
<?php endif; ?>
            </div>
            
            
            <div>
                <?php if (isset($component)) { $__componentOriginal35d15732c183da7413f992a7a23872b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal35d15732c183da7413f992a7a23872b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.reset-button','data' => ['formId' => '#filterForm','callback' => 'loadPromotionsWithStats']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.reset-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['formId' => '#filterForm','callback' => 'loadPromotionsWithStats']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal35d15732c183da7413f992a7a23872b6)): ?>
<?php $attributes = $__attributesOriginal35d15732c183da7413f992a7a23872b6; ?>
<?php unset($__attributesOriginal35d15732c183da7413f992a7a23872b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal35d15732c183da7413f992a7a23872b6)): ?>
<?php $component = $__componentOriginal35d15732c183da7413f992a7a23872b6; ?>
<?php unset($__componentOriginal35d15732c183da7413f992a7a23872b6); ?>
<?php endif; ?>
            </div>
        </form>
    </div>

    
    <?php if (isset($component)) { $__componentOriginal119bc853f16f4c649986104253e7a999 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal119bc853f16f4c649986104253e7a999 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.ajax-table','data' => ['tableId' => 'promotions-content','loadingId' => 'loading-state','formId' => '#filterForm','baseUrl' => ''.e(route('admin.promotions.index')).'','callbackName' => 'loadPromotions','afterLoadCallback' => 'initializeEventListeners','updateStats' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.ajax-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['table-id' => 'promotions-content','loading-id' => 'loading-state','form-id' => '#filterForm','base-url' => ''.e(route('admin.promotions.index')).'','callback-name' => 'loadPromotions','after-load-callback' => 'initializeEventListeners','update-stats' => true]); ?>
        <?php echo $__env->make('admin.promotions.partials.table', ['promotions' => $promotions], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal119bc853f16f4c649986104253e7a999)): ?>
<?php $attributes = $__attributesOriginal119bc853f16f4c649986104253e7a999; ?>
<?php unset($__attributesOriginal119bc853f16f4c649986104253e7a999); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal119bc853f16f4c649986104253e7a999)): ?>
<?php $component = $__componentOriginal119bc853f16f4c649986104253e7a999; ?>
<?php unset($__componentOriginal119bc853f16f4c649986104253e7a999); ?>
<?php endif; ?>
</div>


<?php if (isset($component)) { $__componentOriginalaa3b824e4662c5ae30529397669d1c1d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaa3b824e4662c5ae30529397669d1c1d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.delete-modal','data' => ['modalId' => 'deleteModal','title' => 'Xác nhận xóa khuyến mãi','entityName' => 'khuyến mãi','warningText' => 'Bạn có chắc chắn muốn xóa','confirmText' => 'Xóa','cancelText' => 'Hủy']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.delete-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'deleteModal','title' => 'Xác nhận xóa khuyến mãi','entity-name' => 'khuyến mãi','warning-text' => 'Bạn có chắc chắn muốn xóa','confirm-text' => 'Xóa','cancel-text' => 'Hủy']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaa3b824e4662c5ae30529397669d1c1d)): ?>
<?php $attributes = $__attributesOriginalaa3b824e4662c5ae30529397669d1c1d; ?>
<?php unset($__attributesOriginalaa3b824e4662c5ae30529397669d1c1d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaa3b824e4662c5ae30529397669d1c1d)): ?>
<?php $component = $__componentOriginalaa3b824e4662c5ae30529397669d1c1d; ?>
<?php unset($__componentOriginalaa3b824e4662c5ae30529397669d1c1d); ?>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Update stats cards from toggle response
window.updateStatsFromServer = function(stats) {
    const statsMapping = {
        'totalPromotions': 'total',
        'activePromotions': 'active',
        'inactivePromotions': 'inactive',
        'expiredPromotions': 'expired'
    };
    
    Object.entries(statsMapping).forEach(([serverKey, cardKey]) => {
        if (stats[serverKey] !== undefined) {
            const statElement = document.querySelector(`p[data-stat="${cardKey}"]`);
            if (statElement) {
                statElement.textContent = stats[serverKey];
            }
        }
    });
};

// Initialize event listeners
function initializeEventListeners() {
    initializeStatusToggle();
    initializeDeleteButtons();
}

// Status toggle functionality for promotions
function initializeStatusToggle() {
    document.querySelectorAll('.status-toggle').forEach(button => {
        // Remove existing listener to prevent duplicates
        button.removeEventListener('click', handleStatusToggle);
        // Add new listener
        button.addEventListener('click', handleStatusToggle);
    });
}

async function handleStatusToggle(e) {
    e.preventDefault();
    const promotionId = this.dataset.promotionId || this.getAttribute('data-promotion-id');
    const newStatus = this.dataset.status === 'true';
    const buttonElement = this;
        
    // Show loading state
    const originalIcon = buttonElement.querySelector('i').className;
    buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
    buttonElement.disabled = true;
    
    try {
        const response = await fetch(`/admin/promotions/${promotionId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ is_active: newStatus })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            // Update button appearance based on NEW status
            if (newStatus) {
                // Now active -> show pause button
                buttonElement.className = 'text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center';
                buttonElement.title = 'Tạm dừng';
                buttonElement.dataset.status = 'false'; // Next click will deactivate
                buttonElement.querySelector('i').className = 'fas fa-pause w-4 h-4';
            } else {
                // Now inactive -> show play button
                buttonElement.className = 'text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center';
                buttonElement.title = 'Kích hoạt';
                buttonElement.dataset.status = 'true'; // Next click will activate
                buttonElement.querySelector('i').className = 'fas fa-play w-4 h-4';
            }
            
            // Update status badge if exists
            if (window.updateStatusBadge) {
                window.updateStatusBadge(promotionId, newStatus, 'promotion');
            }
            
            // Update stats cards from response
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            // Show message
            if (data.message && window.showMessage) {
                window.showMessage(data.message, 'success');
            }
            
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
        
    } catch (error) {
        console.error('Toggle error:', error);
        if (window.showMessage) {
            window.showMessage(error.message || 'Có lỗi xảy ra khi cập nhật trạng thái', 'error');
        }
        
        // Restore original state
        buttonElement.querySelector('i').className = originalIcon;
    } finally {
        buttonElement.disabled = false;
    }
}

// Initialize delete button event listeners
function initializeDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach((btn) => {
        // Remove existing listeners to prevent duplicates
        btn.removeEventListener('click', handleDeleteClick);
        // Add new listener
        btn.addEventListener('click', handleDeleteClick);
    });
}

// Handle delete button click
function handleDeleteClick(e) {
    e.preventDefault();
    
    const promotionId = this.dataset.promotionId;
    const promotionName = this.dataset.promotionName;
    const promotionCode = this.dataset.promotionCode;
    const promotionType = this.dataset.promotionType;
    const promotionValue = this.dataset.promotionValue;
    const usageCount = parseInt(this.dataset.usageCount) || 0;
    
    // Show delete modal with promotion info
    if (window.deleteModalManager_deleteModal) {
        // Clear any previous modal content first
        window.deleteModalManager_deleteModal.reset();
        
        // Determine type display name
        const typeNames = {
            'percentage': 'Giảm theo %',
            'fixed_amount': 'Giảm cố định',
            'free_shipping': 'Miễn phí ship',
            'brand_specific': 'Theo thương hiệu'
        };
        const typeDisplay = typeNames[promotionType] || 'Khác';
        
        // Format value
        let valueDisplay = '';
        if (promotionType === 'percentage') {
            valueDisplay = promotionValue + '%';
        } else if (promotionType === 'fixed_amount') {
            valueDisplay = new Intl.NumberFormat('vi-VN').format(promotionValue) + 'đ';
        } else {
            valueDisplay = '-';
        }
        
        // Usage status
        const hasUsage = usageCount > 0;
        const usageText = hasUsage ? `Đã được sử dụng ${usageCount} lần` : 'Chưa được sử dụng';
        const usageColor = hasUsage ? 'text-orange-600' : 'text-green-600';
        
        window.deleteModalManager_deleteModal.show({
            entityName: `${promotionName}`,
            details: `<div class="text-sm space-y-2">
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="font-medium text-gray-600">Mã:</span>
                            <span class="text-gray-900 font-mono">${promotionCode}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Loại:</span>
                            <span class="text-gray-900">${typeDisplay}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Giá trị:</span>
                            <span class="text-gray-900 font-semibold">${valueDisplay}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Sử dụng:</span>
                            <span class="${usageColor} font-medium">${usageText}</span>
                        </div>
                    </div>
                </div>
            </div>` + (hasUsage ? `
            <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded-md">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-orange-500 mt-0.5 mr-2"></i>
                    <div class="text-sm text-orange-700">
                        <strong>Cảnh báo:</strong> Khuyến mãi này đã được sử dụng. Việc xóa có thể ảnh hưởng đến lịch sử đơn hàng.
                    </div>
                </div>
            </div>` : ''),
            deleteUrl: `/admin/promotions/${promotionId}`
        });
    }
}

// Delete confirmation function
window.confirmDelete = function(data) {
    if (!data || !data.deleteUrl) {
        return;
    }
    
    // Show loading state on delete button
    if (window.deleteModalManager_deleteModal) {
        window.deleteModalManager_deleteModal.setLoading(true);
    }
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 1. Close modal immediately
            if (window.deleteModalManager_deleteModal) {
                window.deleteModalManager_deleteModal.hide();
            }
            
            // 2. Update stats cards and reload table
            if (window.loadPromotionsWithStats) {
                const searchForm = document.getElementById('filterForm');
                const formData = new FormData(searchForm);
                const url = '<?php echo e(route("admin.promotions.index")); ?>?' + new URLSearchParams(formData).toString();
                window.loadPromotionsWithStats(url);
            }
            
            // 3. Show success message
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa khuyến mãi thành công!', 'success');
            }
        } else {
            // Handle error response
            if (window.deleteModalManager_deleteModal) {
                window.deleteModalManager_deleteModal.setLoading(false);
            }
            
            if (data.message && window.showMessage) {
                window.showMessage(data.message, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Hide loading state
        if (window.deleteModalManager_deleteModal) {
            window.deleteModalManager_deleteModal.setLoading(false);
        }
        
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra khi xóa khuyến mãi', 'error');
        }
    });
};


// Custom load function with stats update
window.loadPromotionsWithStats = function(url) {
    // First load the table
    if (window.loadPromotions) {
        window.loadPromotions(url);
    }
    
    // Then fetch stats separately
    fetch(url + (url.includes('?') ? '&' : '?') + 'stats_only=1', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(stats => {
        if (window.updateStatsFromServer) {
            window.updateStatsFromServer(stats);
        }
    })
    .catch(error => console.log('Stats update failed:', error));
};

// Dropdown callback
window.loadPromotionsFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '<?php echo e(route("admin.promotions.index")); ?>?' + new URLSearchParams(formData).toString();
        window.loadPromotionsWithStats(url);
    }
};

// Search callback
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '<?php echo e(route("admin.promotions.index")); ?>?' + new URLSearchParams(formData).toString();
        window.loadPromotionsWithStats(url);
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/promotions/index.blade.php ENDPATH**/ ?>