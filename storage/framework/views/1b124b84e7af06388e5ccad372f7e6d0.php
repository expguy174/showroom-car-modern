<?php $__env->startSection('title', 'Quản lý showroom'); ?>

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
    
    <?php if (isset($component)) { $__componentOriginalcb19cb35a534439097b02b8af91726ee = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcb19cb35a534439097b02b8af91726ee = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Quản lý showroom','description' => 'Quản lý thông tin các showroom trưng bày xe','icon' => 'fas fa-building']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Quản lý showroom','description' => 'Quản lý thông tin các showroom trưng bày xe','icon' => 'fas fa-building']); ?>
        <a href="<?php echo e(route('admin.showrooms.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm showroom</span>
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

    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <?php if (isset($component)) { $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Tổng showroom','value' => $stats['total'],'icon' => 'fas fa-building','color' => 'blue','description' => 'Tất cả showroom','dataStat' => 'total']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tổng showroom','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['total']),'icon' => 'fas fa-building','color' => 'blue','description' => 'Tất cả showroom','dataStat' => 'total']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Hoạt động','value' => $stats['active'],'icon' => 'fas fa-check-circle','color' => 'green','description' => 'Showroom hoạt động','dataStat' => 'active']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Hoạt động','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['active']),'icon' => 'fas fa-check-circle','color' => 'green','description' => 'Showroom hoạt động','dataStat' => 'active']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Tạm dừng','value' => $stats['inactive'],'icon' => 'fas fa-pause-circle','color' => 'red','description' => 'Showroom tạm dừng','dataStat' => 'inactive']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tạm dừng','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($stats['inactive']),'icon' => 'fas fa-pause-circle','color' => 'red','description' => 'Showroom tạm dừng','dataStat' => 'inactive']); ?>
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

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end">

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <?php if (isset($component)) { $__componentOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.search-input','data' => ['name' => 'search','placeholder' => 'Tìm theo tên, địa chỉ, điện thoại...','value' => request('search'),'callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','placeholder' => 'Tìm theo tên, địa chỉ, điện thoại...','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('search')),'callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.custom-dropdown','data' => ['name' => 'is_active','options' => [
                        '1' => 'Hoạt động',
                        '0' => 'Tạm dừng'
                    ],'selected' => request('is_active'),'placeholder' => 'Tất cả','onchange' => 'submitFilterForm','searchable' => false,'width' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.custom-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'is_active','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        '1' => 'Hoạt động',
                        '0' => 'Tạm dừng'
                    ]),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('is_active')),'placeholder' => 'Tất cả','onchange' => 'submitFilterForm','searchable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'width' => 'w-full']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.reset-button','data' => ['formId' => '#filterForm','callback' => 'resetFilters']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.reset-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['formId' => '#filterForm','callback' => 'resetFilters']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.ajax-table','data' => ['tableId' => 'showrooms-content','loadingId' => 'loading-state','formId' => 'filterForm','baseUrl' => ''.e(route('admin.showrooms.index')).'','callbackName' => 'loadShowrooms','emptyMessage' => 'Không có showroom nào','emptyIcon' => 'fas fa-building','afterLoadCallback' => 'initializeEventListeners']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.ajax-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['table-id' => 'showrooms-content','loading-id' => 'loading-state','form-id' => 'filterForm','base-url' => ''.e(route('admin.showrooms.index')).'','callback-name' => 'loadShowrooms','empty-message' => 'Không có showroom nào','empty-icon' => 'fas fa-building','after-load-callback' => 'initializeEventListeners']); ?>
        <?php echo $__env->make('admin.showrooms.partials.table', ['showrooms' => $showrooms], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.delete-modal','data' => ['modalId' => 'deleteShowroomModal','title' => 'Xác nhận xóa showroom','confirmText' => 'Xóa','cancelText' => 'Hủy','deleteCallbackName' => 'confirmDeleteShowroom','entityType' => 'showroom']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.delete-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'deleteShowroomModal','title' => 'Xác nhận xóa showroom','confirm-text' => 'Xóa','cancel-text' => 'Hủy','delete-callback-name' => 'confirmDeleteShowroom','entity-type' => 'showroom']); ?>
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
            'total': 'total',
            'active': 'active',
            'inactive': 'inactive'
        };

        Object.entries(statsMapping).forEach(([serverKey, cardKey]) => {
            if (stats[serverKey] !== undefined) {
                const statElement = document.querySelector(`[data-stat="${cardKey}"]`);
                if (statElement) {
                    statElement.textContent = stats[serverKey];
                }
            }
        });
    };

    // Initialize event listeners
    function initializeEventListeners() {
        // Status toggle buttons
        document.querySelectorAll('.status-toggle').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const showroomId = this.dataset.showroomId;
                const newStatus = this.dataset.status === 'true';
                const buttonElement = this;

                // Show loading state
                const originalIcon = buttonElement.querySelector('i').className;
                buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
                buttonElement.disabled = true;

                try {
                    const response = await fetch(`/admin/showrooms/${showroomId}/toggle-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            is_active: newStatus ? 1 : 0
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Update button appearance
                        if (newStatus) {
                            buttonElement.className = 'text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center';
                            buttonElement.title = 'Tạm dừng';
                            buttonElement.dataset.status = 'false';
                            buttonElement.querySelector('i').className = 'fas fa-pause w-4 h-4';
                        } else {
                            buttonElement.className = 'text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center';
                            buttonElement.title = 'Kích hoạt';
                            buttonElement.dataset.status = 'true';
                            buttonElement.querySelector('i').className = 'fas fa-play w-4 h-4';
                        }

                        // Update status badge in table
                        const showroomId = buttonElement.dataset.showroomId;
                        const row = document.querySelector(`tr[data-showroom-id="${showroomId}"]`);
                        if (row) {
                            const statusCell = row.querySelector('td:nth-child(4)');
                            if (statusCell) {
                                const statusBadge = statusCell.querySelector('span');
                                if (statusBadge) {
                                    if (newStatus) {
                                        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                                        statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Hoạt động';
                                    } else {
                                        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                                        statusBadge.innerHTML = '<i class="fas fa-pause-circle mr-1"></i>Tạm dừng';
                                    }
                                }
                            }
                        }

                        // Update stats cards if provided
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
                    console.error('Error:', error);
                    buttonElement.querySelector('i').className = originalIcon;
                    if (window.showMessage) {
                        window.showMessage('Có lỗi xảy ra khi cập nhật trạng thái!', 'error');
                    }
                } finally {
                    buttonElement.disabled = false;
                }
            });
        });

        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const showroomId = this.dataset.showroomId;
                const showroomName = this.dataset.showroomName;

                if (window.deleteModalManager_deleteShowroomModal) {
                    window.deleteModalManager_deleteShowroomModal.show({
                        entityName: `showroom ${showroomName}`,
                        details: 'Hành động này không thể hoàn tác.',
                        deleteUrl: `/admin/showrooms/${showroomId}`
                    });
                }
            });
        });
    }

    // Dropdown callback
    window.submitFilterForm = function() {
        // Wait for ajax table to be ready
        setTimeout(() => {
            if (window.loadShowrooms) {
                const searchForm = document.getElementById('filterForm');
                if (searchForm) {
                    // Fix is_active value before creating FormData
                    const isActiveInput = searchForm.querySelector('input[name="is_active"]');
                    if (isActiveInput && isActiveInput.value) {
                        // Custom dropdown might set text value, need to map to actual value
                        const statusMap = {
                            'Hoạt động': '1',
                            'Tạm dừng': '0'
                        };

                        if (statusMap[isActiveInput.value]) {
                            isActiveInput.value = statusMap[isActiveInput.value];
                        }
                    }

                    const formData = new FormData(searchForm);
                    const url = '<?php echo e(route("admin.showrooms.index")); ?>?' + new URLSearchParams(formData).toString();
                    window.loadShowrooms(url);
                }
            }
        }, 100);
    };

    // Search callback
    window.handleSearch = function(searchTerm, inputElement) {
        // Wait for ajax table to be ready
        setTimeout(() => {
            if (window.loadShowrooms) {
                const searchForm = document.getElementById('filterForm');
                if (searchForm) {
                    const formData = new FormData(searchForm);
                    const url = '<?php echo e(route("admin.showrooms.index")); ?>?' + new URLSearchParams(formData).toString();
                    window.loadShowrooms(url);
                }
            }
        }, 100);
    };

    // Reset filters
    window.resetFilters = function() {
        // Wait for ajax table to be ready
        setTimeout(() => {
            if (window.loadShowrooms) {
                window.loadShowrooms('<?php echo e(route("admin.showrooms.index")); ?>');
            }
        }, 100);
    };

    // Delete confirmation
    window.confirmDeleteShowroom = function(data) {
        if (!data || !data.deleteUrl) return;

        if (window.deleteModalManager_deleteShowroomModal) {
            window.deleteModalManager_deleteShowroomModal.setLoading(true);
        }

        fetch(data.deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                return response.json().then(responseData => {
                    if (!response.ok) {
                        throw {
                            status: response.status,
                            data: responseData
                        };
                    }
                    return responseData;
                });
            })
            .then(responseData => {
                if (responseData.success) {
                    if (window.deleteModalManager_deleteShowroomModal) {
                        window.deleteModalManager_deleteShowroomModal.hide();
                    }

                    if (window.showMessage) {
                        window.showMessage(responseData.message || 'Đã xóa showroom thành công!', 'success');
                    }

                    // Update stats cards if provided
                    if (responseData.stats && window.updateStatsFromServer) {
                        window.updateStatsFromServer(responseData.stats);
                    }

                    if (window.loadShowrooms) {
                        window.loadShowrooms();
                    }
                } else {
                    throw new Error(responseData.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (window.deleteModalManager_deleteShowroomModal) {
                    window.deleteModalManager_deleteShowroomModal.setLoading(false);
                }

                const errorMessage = error.data?.message || error.message || 'Có lỗi xảy ra khi xóa showroom!';

                if (window.showMessage) {
                    window.showMessage(errorMessage, 'error');
                }
            });
    };

    // Make it globally accessible for ajax-table callback
    window.initializeEventListeners = initializeEventListeners;

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/showrooms/index.blade.php ENDPATH**/ ?>