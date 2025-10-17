

<?php $__env->startSection('title', 'Danh sách đơn hàng'); ?>

<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginaldb1b157d84f8f63332f3508c9e385c0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.flash-messages','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Danh sách đơn hàng','description' => 'Quản lý tất cả đơn hàng trong hệ thống','icon' => 'fas fa-shopping-cart']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Danh sách đơn hàng','description' => 'Quản lý tất cả đơn hàng trong hệ thống','icon' => 'fas fa-shopping-cart']); ?>
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>
                Xuất Excel
            </button>
        </div>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Tổng đơn hàng','value' => $totalOrders,'icon' => 'fas fa-shopping-cart','color' => 'blue','description' => 'Tất cả đơn hàng','dataStat' => 'total']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tổng đơn hàng','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalOrders),'icon' => 'fas fa-shopping-cart','color' => 'blue','description' => 'Tất cả đơn hàng','dataStat' => 'total']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Chờ xử lý','value' => $pendingOrders,'icon' => 'fas fa-clock','color' => 'yellow','description' => 'Cần xử lý ngay','dataStat' => 'pending']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Chờ xử lý','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pendingOrders),'icon' => 'fas fa-clock','color' => 'yellow','description' => 'Cần xử lý ngay','dataStat' => 'pending']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Đã hủy','value' => $cancelledOrders,'icon' => 'fas fa-times-circle','color' => 'red','description' => 'Đơn bị hủy','dataStat' => 'cancelled']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Đã hủy','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($cancelledOrders),'icon' => 'fas fa-times-circle','color' => 'red','description' => 'Đơn bị hủy','dataStat' => 'cancelled']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Hoàn thành','value' => $deliveredOrders,'icon' => 'fas fa-check-circle','color' => 'green','description' => 'Đã giao thành công','dataStat' => 'delivered']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Hoàn thành','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($deliveredOrders),'icon' => 'fas fa-check-circle','color' => 'green','description' => 'Đã giao thành công','dataStat' => 'delivered']); ?>
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
        <form method="GET" action="<?php echo e(route('admin.orders.index')); ?>" id="filterForm" data-base-url="<?php echo e(route('admin.orders.index')); ?>" class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <?php if (isset($component)) { $__componentOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.search-input','data' => ['name' => 'search','value' => request('search', ''),'placeholder' => 'Tìm kiếm đơn hàng, khách hàng...','callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('search', '')),'placeholder' => 'Tìm kiếm đơn hàng, khách hàng...','callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]); ?>
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
                        ['value' => 'pending', 'label' => 'Chờ xử lý'],
                        ['value' => 'confirmed', 'label' => 'Đã xác nhận'],
                        ['value' => 'shipping', 'label' => 'Đang giao'],
                        ['value' => 'delivered', 'label' => 'Đã giao'],
                        ['value' => 'cancelled', 'label' => 'Đã hủy']
                    ],'selected' => request('status', ''),'placeholder' => 'Tất cả','optionValue' => 'value','optionText' => 'label','searchable' => false,'onchange' => 'autoSubmitForm','width' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.custom-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        ['value' => 'pending', 'label' => 'Chờ xử lý'],
                        ['value' => 'confirmed', 'label' => 'Đã xác nhận'],
                        ['value' => 'shipping', 'label' => 'Đang giao'],
                        ['value' => 'delivered', 'label' => 'Đã giao'],
                        ['value' => 'cancelled', 'label' => 'Đã hủy']
                    ]),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('status', '')),'placeholder' => 'Tất cả','option-value' => 'value','option-text' => 'label','searchable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'onchange' => 'autoSubmitForm','width' => 'w-full']); ?>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Thanh toán</label>
                <?php if (isset($component)) { $__componentOriginal42eccf6ae0cbd0d224265b5df2422179 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42eccf6ae0cbd0d224265b5df2422179 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.custom-dropdown','data' => ['name' => 'payment_status','options' => [
                        ['value' => 'pending', 'label' => 'Chờ thanh toán'],
                        ['value' => 'processing', 'label' => 'Đang xử lý'],
                        ['value' => 'partial', 'label' => 'Thanh toán một phần'],
                        ['value' => 'completed', 'label' => 'Đã thanh toán'],
                        ['value' => 'refunded', 'label' => 'Đã hoàn tiền'],
                        ['value' => 'failed', 'label' => 'Thất bại'],
                        ['value' => 'cancelled', 'label' => 'Đã hủy']
                    ],'selected' => request('payment_status', ''),'placeholder' => 'Tất cả','optionValue' => 'value','optionText' => 'label','searchable' => false,'onchange' => 'autoSubmitForm','width' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.custom-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'payment_status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        ['value' => 'pending', 'label' => 'Chờ thanh toán'],
                        ['value' => 'processing', 'label' => 'Đang xử lý'],
                        ['value' => 'partial', 'label' => 'Thanh toán một phần'],
                        ['value' => 'completed', 'label' => 'Đã thanh toán'],
                        ['value' => 'refunded', 'label' => 'Đã hoàn tiền'],
                        ['value' => 'failed', 'label' => 'Thất bại'],
                        ['value' => 'cancelled', 'label' => 'Đã hủy']
                    ]),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('payment_status', '')),'placeholder' => 'Tất cả','option-value' => 'value','option-text' => 'label','searchable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'onchange' => 'autoSubmitForm','width' => 'w-full']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.reset-button','data' => ['formId' => '#filterForm','callback' => 'handleFormReset']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.reset-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['form-id' => '#filterForm','callback' => 'handleFormReset']); ?>
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
        
        
        <?php if(request('search') || request('status') || request('payment_status')): ?>
        <div class="mt-3 flex items-center gap-2 flex-wrap">
            <span class="text-sm text-gray-600">Bộ lọc đang áp dụng:</span>
            <?php if(request('search')): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <i class="fas fa-search mr-1"></i>
                <?php echo e(request('search')); ?>

                <button type="button" onclick="removeFilter('search')" class="ml-2 hover:text-blue-900">
                    <i class="fas fa-times"></i>
                </button>
            </span>
            <?php endif; ?>
            <?php if(request('status')): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                <?php echo e(['pending' => 'Chờ xử lý', 'confirmed' => 'Đã xác nhận', 'shipping' => 'Đang giao', 'delivered' => 'Đã giao', 'cancelled' => 'Đã hủy'][request('status')] ?? request('status')); ?>

                <button type="button" onclick="removeFilter('status')" class="ml-2 hover:text-purple-900">
                    <i class="fas fa-times"></i>
                </button>
            </span>
            <?php endif; ?>
            <?php if(request('payment_status')): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                <?php echo e(['pending' => 'Chờ thanh toán', 'processing' => 'Đang xử lý', 'partial' => 'Thanh toán một phần', 'completed' => 'Đã thanh toán', 'refunded' => 'Đã hoàn tiền', 'failed' => 'Thất bại', 'cancelled' => 'Đã hủy'][request('payment_status')] ?? request('payment_status')); ?>

                <button type="button" onclick="removeFilter('payment_status')" class="ml-2 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </span>
            <?php endif; ?>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-600 hover:text-red-800 transition-colors">
                <i class="fas fa-times-circle mr-1"></i>
                Xóa tất cả
            </a>
        </div>
        <?php endif; ?>
    </div>

    
    <?php if (isset($component)) { $__componentOriginal119bc853f16f4c649986104253e7a999 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal119bc853f16f4c649986104253e7a999 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.ajax-table','data' => ['tableId' => 'orders-content','loadingId' => 'loading-state','formId' => '#filterForm','baseUrl' => ''.e(route('admin.orders.index')).'','callbackName' => 'loadTableData']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.ajax-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['table-id' => 'orders-content','loading-id' => 'loading-state','form-id' => '#filterForm','base-url' => ''.e(route('admin.orders.index')).'','callback-name' => 'loadTableData']); ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <?php echo $__env->make('admin.orders.partials.table', ['orders' => $orders], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
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

<?php $__env->startPush('scripts'); ?>
<script>
// Search input callback
window.handleSearch = function(searchTerm, inputElement) {
    const form = document.getElementById('filterForm');
    if (form && window.loadTableData) {
        const formData = new FormData(form);
        const url = '<?php echo e(route("admin.orders.index")); ?>?' + new URLSearchParams(formData).toString();
        window.loadTableData(url);
    }
};

// Dropdown callback
window.autoSubmitForm = function() {
    const form = document.getElementById('filterForm');
    if (form && window.loadTableData) {
        const formData = new FormData(form);
        const url = '<?php echo e(route("admin.orders.index")); ?>?' + new URLSearchParams(formData).toString();
        window.loadTableData(url);
    }
};

// Reset button callback
window.handleFormReset = function() {
    if (window.loadTableData) {
        window.loadTableData('<?php echo e(route("admin.orders.index")); ?>');
    }
};
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>