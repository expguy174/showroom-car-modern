<?php $__env->startSection('title', 'Quản lý lịch hẹn dịch vụ'); ?>

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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Quản lý lịch hẹn dịch vụ','description' => 'Quản lý các lịch hẹn dịch vụ từ khách hàng','icon' => 'fas fa-calendar-alt']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Quản lý lịch hẹn dịch vụ','description' => 'Quản lý các lịch hẹn dịch vụ từ khách hàng','icon' => 'fas fa-calendar-alt']); ?>
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

    
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-4 mb-6">
        <?php if (isset($component)) { $__componentOriginal14dadb7763529f6bc7d89e29f3674f2f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14dadb7763529f6bc7d89e29f3674f2f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Tổng lịch hẹn','value' => $totalAppointments ?? 0,'icon' => 'fas fa-calendar-alt','color' => 'blue','description' => 'Tất cả lịch hẹn','dataStat' => 'total']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tổng lịch hẹn','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($totalAppointments ?? 0),'icon' => 'fas fa-calendar-alt','color' => 'blue','description' => 'Tất cả lịch hẹn','dataStat' => 'total']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Đã đặt lịch','value' => $pendingAppointments ?? 0,'icon' => 'fas fa-clock','color' => 'yellow','description' => 'Chờ xác nhận','dataStat' => 'pending']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Đã đặt lịch','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($pendingAppointments ?? 0),'icon' => 'fas fa-clock','color' => 'yellow','description' => 'Chờ xác nhận','dataStat' => 'pending']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Đã xác nhận','value' => $confirmedAppointments ?? 0,'icon' => 'fas fa-check-circle','color' => 'green','description' => 'Đã xác nhận','dataStat' => 'confirmed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Đã xác nhận','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($confirmedAppointments ?? 0),'icon' => 'fas fa-check-circle','color' => 'green','description' => 'Đã xác nhận','dataStat' => 'confirmed']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Đang thực hiện','value' => $inProgressAppointments ?? 0,'icon' => 'fas fa-cog','color' => 'purple','description' => 'Đang xử lý','dataStat' => 'in_progress']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Đang thực hiện','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($inProgressAppointments ?? 0),'icon' => 'fas fa-cog','color' => 'purple','description' => 'Đang xử lý','dataStat' => 'in_progress']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Hoàn thành','value' => $completedAppointments ?? 0,'icon' => 'fas fa-flag-checkered','color' => 'indigo','description' => 'Đã hoàn thành','dataStat' => 'completed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Hoàn thành','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($completedAppointments ?? 0),'icon' => 'fas fa-flag-checkered','color' => 'indigo','description' => 'Đã hoàn thành','dataStat' => 'completed']); ?>
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
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="<?php echo e(route('admin.service-appointments.index')); ?>">
            
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <?php if (isset($component)) { $__componentOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.search-input','data' => ['name' => 'search','placeholder' => 'Tên, email, biển số xe...','value' => request('search'),'callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','placeholder' => 'Tên, email, biển số xe...','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('search')),'callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]); ?>
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
                        ['value' => 'scheduled', 'text' => 'Đã đặt lịch'],
                        ['value' => 'confirmed', 'text' => 'Đã xác nhận'],
                        ['value' => 'in_progress', 'text' => 'Đang thực hiện'],
                        ['value' => 'completed', 'text' => 'Hoàn thành'],
                        ['value' => 'cancelled', 'text' => 'Đã hủy']
                    ],'placeholder' => 'Tất cả','optionValue' => 'value','optionText' => 'text','selected' => request('status'),'onchange' => 'loadAppointmentsFromDropdown','maxVisible' => 6,'searchable' => false,'width' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.custom-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        ['value' => 'scheduled', 'text' => 'Đã đặt lịch'],
                        ['value' => 'confirmed', 'text' => 'Đã xác nhận'],
                        ['value' => 'in_progress', 'text' => 'Đang thực hiện'],
                        ['value' => 'completed', 'text' => 'Hoàn thành'],
                        ['value' => 'cancelled', 'text' => 'Đã hủy']
                    ]),'placeholder' => 'Tất cả','optionValue' => 'value','optionText' => 'text','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('status')),'onchange' => 'loadAppointmentsFromDropdown','maxVisible' => 6,'searchable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'width' => 'w-full']); ?>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Dịch vụ</label>
                <?php if (isset($component)) { $__componentOriginal42eccf6ae0cbd0d224265b5df2422179 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal42eccf6ae0cbd0d224265b5df2422179 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.custom-dropdown','data' => ['name' => 'service_id','options' => $services ?? [],'placeholder' => 'Tất cả','optionValue' => 'id','optionText' => 'name','selected' => request('service_id'),'onchange' => 'loadAppointmentsFromDropdown','maxVisible' => 6,'searchable' => true,'width' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.custom-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'service_id','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($services ?? []),'placeholder' => 'Tất cả','optionValue' => 'id','optionText' => 'name','selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('service_id')),'onchange' => 'loadAppointmentsFromDropdown','maxVisible' => 6,'searchable' => true,'width' => 'w-full']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.reset-button','data' => ['formId' => '#filterForm','callback' => 'loadAppointmentsWithStats']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.reset-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['formId' => '#filterForm','callback' => 'loadAppointmentsWithStats']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.ajax-table','data' => ['tableId' => 'appointments-content','loadingId' => 'loading-state','formId' => '#filterForm','baseUrl' => ''.e(route('admin.service-appointments.index')).'','callbackName' => 'loadAppointments','afterLoadCallback' => 'initializeEventListeners']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.ajax-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['table-id' => 'appointments-content','loading-id' => 'loading-state','form-id' => '#filterForm','base-url' => ''.e(route('admin.service-appointments.index')).'','callback-name' => 'loadAppointments','after-load-callback' => 'initializeEventListeners']); ?>
        <?php echo $__env->make('admin.service-appointments.partials.table', ['appointments' => $appointments], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.delete-modal','data' => ['modalId' => 'deleteModal','title' => 'Xác nhận xóa lịch hẹn','entityName' => 'lịch hẹn','warningText' => 'Bạn có chắc chắn muốn xóa','confirmText' => 'Xóa','cancelText' => 'Hủy']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.delete-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'deleteModal','title' => 'Xác nhận xóa lịch hẹn','entity-name' => 'lịch hẹn','warning-text' => 'Bạn có chắc chắn muốn xóa','confirm-text' => 'Xóa','cancel-text' => 'Hủy']); ?>
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


<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Xác nhận lịch hẹn</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600" id="confirmModalMessage"></p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeConfirmModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="confirmModalButton"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <span id="confirmModalBtnText">Xác nhận</span>
            </button>
        </div>
    </div>
</div>


<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" id="statusModalIconWrapper">
                <i id="statusModalIcon"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900" id="statusModalTitle"></h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600" id="statusModalMessage"></p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeStatusModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="statusModalButton"
                    class="flex-1 px-4 py-2 text-white rounded-lg font-medium transition-colors">
                <span id="statusModalBtnText"></span>
            </button>
        </div>
    </div>
</div>


<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-ban text-orange-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Hủy lịch hẹn</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600 mb-3" id="cancelModalMessage"></p>
            <div id="cancelModalDetails" class="bg-gray-50 rounded-lg p-3 text-sm"></div>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeCancelModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Không
            </button>
            <button type="button" id="cancelModalButton"
                    class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <span id="cancelModalBtnText">Hủy lịch hẹn</span>
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function initializeEventListeners() {
    initializeConfirmButtons();
    initializeCancelButtons();
    initializeDeleteButtons();
    initializeStartServiceButtons();
    initializeCompleteServiceButtons();
}

function initializeConfirmButtons() {
    document.querySelectorAll('.confirm-btn').forEach(button => {
        button.removeEventListener('click', handleConfirmClick);
        button.addEventListener('click', handleConfirmClick);
    });
}

function handleConfirmClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    const customerName = this.dataset.customerName;
    
    // Show modal
    document.getElementById('confirmModalMessage').textContent = `Bạn có chắc chắn muốn xác nhận lịch hẹn của ${customerName}?`;
    document.getElementById('confirmModal').classList.remove('hidden');
    
    // Set confirm action
    document.getElementById('confirmModalButton').onclick = () => executeConfirm(appointmentId);
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

async function executeConfirm(appointmentId) {
    const button = document.getElementById('confirmModalButton');
    const btnText = document.getElementById('confirmModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/confirm`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeConfirmModal();
            // Update badge and buttons without reloading table
            updateAppointmentStatus(appointmentId, 'confirmed');
            updateStatsCards();
            if (window.showMessage) window.showMessage(data.message || 'Đã xác nhận lịch hẹn!', 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Confirm error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi xác nhận', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

function initializeCancelButtons() {
    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.removeEventListener('click', handleCancelClick);
        button.addEventListener('click', handleCancelClick);
    });
}

function handleCancelClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    const customerName = this.dataset.customerName;
    const service = this.dataset.service;
    const date = this.dataset.date;
    
    // Show cancel modal
    document.getElementById('cancelModalMessage').textContent = `Bạn có chắc chắn muốn hủy lịch hẹn của ${customerName}?`;
    document.getElementById('cancelModalDetails').innerHTML = `
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Dịch vụ:</span>
                <span class="font-medium text-gray-900">${service}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Ngày hẹn:</span>
                <span class="font-medium text-gray-900">${date}</span>
            </div>
        </div>
    `;
    document.getElementById('cancelModal').classList.remove('hidden');
    
    // Set cancel action
    document.getElementById('cancelModalButton').onclick = () => executeCancel(appointmentId);
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

async function executeCancel(appointmentId) {
    const button = document.getElementById('cancelModalButton');
    const btnText = document.getElementById('cancelModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/cancel`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeCancelModal();
            // Update badge and buttons without reloading table
            updateAppointmentStatus(appointmentId, 'cancelled');
            updateStatsCards();
            if (window.showMessage) window.showMessage(data.message || 'Đã hủy lịch hẹn!', 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Cancel error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi hủy', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

function initializeDeleteButtons() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.removeEventListener('click', handleDeleteClick);
        btn.addEventListener('click', handleDeleteClick);
    });
}

function handleDeleteClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    const customerName = this.dataset.customerName;
    const service = this.dataset.service;
    const date = this.dataset.date;
    
    if (window.deleteModalManager_deleteModal) {
        window.deleteModalManager_deleteModal.reset();
        window.deleteModalManager_deleteModal.show({
            entityName: `lịch hẹn của ${customerName}`,
            details: `<div class="text-sm space-y-2">
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div><span class="font-medium text-gray-600">Dịch vụ:</span> <span class="text-gray-900">${service}</span></div>
                        <div><span class="font-medium text-gray-600">Ngày hẹn:</span> <span class="text-gray-900">${date}</span></div>
                    </div>
                </div>
            </div>`,
            deleteUrl: `/admin/service-appointments/${appointmentId}`
        });
    }
}

window.confirmDelete = function(data) {
    if (!data || !data.deleteUrl) return;
    if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(true);
    
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
            if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.hide();
            if (window.loadAppointmentsWithStats) loadAppointmentsWithStats('<?php echo e(route("admin.service-appointments.index")); ?>');
            if (window.showMessage) window.showMessage(data.message || 'Đã xóa lịch hẹn!', 'success');
        } else {
            if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(false);
            if (data.message && window.showMessage) window.showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(false);
        if (window.showMessage) window.showMessage('Có lỗi xảy ra khi xóa lịch hẹn', 'error');
    });
};

// Start Service handlers
function initializeStartServiceButtons() {
    document.querySelectorAll('.start-service-btn').forEach(button => {
        button.removeEventListener('click', handleStartServiceClick);
        button.addEventListener('click', handleStartServiceClick);
    });
}

function handleStartServiceClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    
    showStatusModal({
        title: 'Bắt đầu thực hiện dịch vụ',
        message: 'Bạn có chắc chắn muốn bắt đầu thực hiện dịch vụ cho lịch hẹn này?',
        icon: 'fas fa-play-circle text-purple-600',
        iconBg: 'bg-purple-100',
        buttonClass: 'bg-purple-600 hover:bg-purple-700',
        buttonText: 'Bắt đầu',
        action: () => executeStatusUpdate(appointmentId, 'in_progress', 'Đã bắt đầu thực hiện dịch vụ!')
    });
}

// Complete Service handlers
function initializeCompleteServiceButtons() {
    document.querySelectorAll('.complete-service-btn').forEach(button => {
        button.removeEventListener('click', handleCompleteServiceClick);
        button.addEventListener('click', handleCompleteServiceClick);
    });
}

function handleCompleteServiceClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    
    showStatusModal({
        title: 'Hoàn thành dịch vụ',
        message: 'Bạn có chắc chắn đã hoàn thành dịch vụ cho lịch hẹn này?',
        icon: 'fas fa-check-double text-green-600',
        iconBg: 'bg-green-100',
        buttonClass: 'bg-green-600 hover:bg-green-700',
        buttonText: 'Hoàn thành',
        action: () => executeStatusUpdate(appointmentId, 'completed', 'Đã hoàn thành dịch vụ!')
    });
}

function showStatusModal(config) {
    document.getElementById('statusModalTitle').textContent = config.title;
    document.getElementById('statusModalMessage').textContent = config.message;
    document.getElementById('statusModalIcon').className = config.icon;
    document.getElementById('statusModalIconWrapper').className = `flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center ${config.iconBg}`;
    document.getElementById('statusModalButton').className = `flex-1 px-4 py-2 text-white rounded-lg font-medium transition-colors ${config.buttonClass}`;
    document.getElementById('statusModalBtnText').textContent = config.buttonText;
    document.getElementById('statusModal').classList.remove('hidden');
    
    document.getElementById('statusModalButton').onclick = config.action;
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

async function executeStatusUpdate(appointmentId, newStatus, successMessage) {
    const button = document.getElementById('statusModalButton');
    const btnText = document.getElementById('statusModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/update-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeStatusModal();
            // Update badge and buttons without reloading table
            updateAppointmentStatus(appointmentId, newStatus);
            updateStatsCards();
            if (window.showMessage) window.showMessage(data.message || successMessage, 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Status update error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi cập nhật trạng thái', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

// Update appointment status badge and action buttons without reload
function updateAppointmentStatus(appointmentId, newStatus) {
    // Find the row for this appointment
    const rows = document.querySelectorAll('tbody tr');
    
    for (const row of rows) {
        const viewBtn = row.querySelector(`a[href*="/service-appointments/${appointmentId}"]`);
        if (!viewBtn) continue;
        
        // Update status badge
        const statusCell = row.querySelectorAll('td')[5]; // Status column (index 5)
        if (statusCell) {
            statusCell.innerHTML = getStatusBadgeHTML(newStatus);
        }
        
        // Update action buttons
        const actionsCell = row.querySelectorAll('td')[6]; // Actions column (index 6)
        if (actionsCell) {
            const actionsDiv = actionsCell.querySelector('.flex');
            if (actionsDiv) {
                actionsDiv.innerHTML = getActionButtonsHTML(appointmentId, newStatus, row);
                // Re-initialize event listeners for new buttons
                initializeEventListeners();
            }
        }
        
        break;
    }
}

// Get status badge HTML based on status
function getStatusBadgeHTML(status) {
    const badges = {
        'scheduled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fas fa-calendar mr-1"></i>Đã đặt lịch</span>',
        'confirmed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Đã xác nhận</span>',
        'in_progress': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800"><i class="fas fa-cog mr-1"></i>Đang thực hiện</span>',
        'completed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-flag-checkered mr-1"></i>Hoàn thành</span>',
        'cancelled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Đã hủy</span>'
    };
    return badges[status] || status;
}

// Get action buttons HTML based on status
function getActionButtonsHTML(appointmentId, status, row) {
    // Extract data attributes from row
    const customerName = row.querySelector('[data-customer-name]')?.dataset.customerName || 'Khách hàng';
    const service = row.querySelector('[data-service]')?.dataset.service || 'N/A';
    const date = row.querySelector('[data-date]')?.dataset.date || '';
    
    let html = `
        <a href="/admin/service-appointments/${appointmentId}" 
           class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
           title="Xem chi tiết">
            <i class="fas fa-eye w-4 h-4"></i>
        </a>
    `;
    
    // Confirm button - scheduled/rescheduled
    if (['scheduled', 'rescheduled'].includes(status)) {
        html += `
            <button type="button"
                    class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 confirm-btn"
                    data-appointment-id="${appointmentId}"
                    data-customer-name="${customerName}"
                    title="Xác nhận lịch hẹn">
                <i class="fas fa-check-circle w-4 h-4"></i>
            </button>
        `;
    }
    
    // Start button - confirmed
    if (status === 'confirmed') {
        html += `
            <button type="button"
                    class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50 start-service-btn"
                    data-appointment-id="${appointmentId}"
                    title="Bắt đầu thực hiện">
                <i class="fas fa-play-circle w-4 h-4"></i>
            </button>
        `;
    }
    
    // Complete button - in_progress
    if (status === 'in_progress') {
        html += `
            <button type="button"
                    class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 complete-service-btn"
                    data-appointment-id="${appointmentId}"
                    title="Hoàn thành">
                <i class="fas fa-check-double w-4 h-4"></i>
            </button>
        `;
    }
    
    // Cancel button - scheduled/confirmed
    if (['scheduled', 'confirmed'].includes(status)) {
        html += `
            <button type="button"
                    class="text-orange-600 hover:text-orange-900 transition-colors p-1 rounded hover:bg-orange-50 cancel-btn"
                    data-appointment-id="${appointmentId}"
                    data-customer-name="${customerName}"
                    data-service="${service}"
                    data-date="${date}"
                    title="Hủy lịch hẹn">
                <i class="fas fa-ban w-4 h-4"></i>
            </button>
        `;
    }
    
    // Delete button - always
    html += `
        <button type="button"
                class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50 delete-btn"
                data-appointment-id="${appointmentId}"
                data-customer-name="${customerName}"
                data-service="${service}"
                data-date="${date}"
                title="Xóa lịch hẹn">
            <i class="fas fa-trash w-4 h-4"></i>
        </button>
    `;
    
    return html;
}

// Update stats cards without reload
function updateStatsCards() {
    const url = '<?php echo e(route("admin.service-appointments.index")); ?>';
    fetch(url + (url.includes('?') ? '&' : '?') + 'stats_only=1', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update stats cards
        const stats = {
            'total': data.total || 0,
            'pending': data.pending || 0,
            'confirmed': data.confirmed || 0,
            'in_progress': data.in_progress || 0,
            'completed': data.completed || 0
        };
        
        // Update each stat card
        for (const [key, value] of Object.entries(stats)) {
            const statElement = document.querySelector(`[data-stat="${key}"]`);
            if (statElement) {
                statElement.textContent = value;
            }
        }
    })
    .catch(error => console.error('Error updating stats:', error));
}

// Note: window.loadAppointments is created by ajax-table component
// Load with stats update
window.loadAppointmentsWithStats = function(url) {
    // Load table
    window.loadAppointments(url);
    
    // Load stats
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

window.loadAppointmentsFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '<?php echo e(route("admin.service-appointments.index")); ?>?' + new URLSearchParams(formData).toString();
        window.loadAppointmentsWithStats(url);
    }
};

window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '<?php echo e(route("admin.service-appointments.index")); ?>?' + new URLSearchParams(formData).toString();
        window.loadAppointmentsWithStats(url);
    }
};

window.updateStatsFromServer = function(stats) {
    const totalCard = document.querySelector('[data-stat="total"] .text-2xl');
    const pendingCard = document.querySelector('[data-stat="pending"] .text-2xl');
    const confirmedCard = document.querySelector('[data-stat="confirmed"] .text-2xl');
    const inProgressCard = document.querySelector('[data-stat="in_progress"] .text-2xl');
    const completedCard = document.querySelector('[data-stat="completed"] .text-2xl');
    
    if (totalCard && stats.total !== undefined) totalCard.textContent = stats.total;
    if (pendingCard && stats.pending !== undefined) pendingCard.textContent = stats.pending;
    if (confirmedCard && stats.confirmed !== undefined) confirmedCard.textContent = stats.confirmed;
    if (inProgressCard && stats.in_progress !== undefined) inProgressCard.textContent = stats.in_progress;
    if (completedCard && stats.completed !== undefined) completedCard.textContent = stats.completed;
};

document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/service-appointments/index.blade.php ENDPATH**/ ?>