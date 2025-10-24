<?php $__env->startSection('title', 'Quản lý Liên hệ'); ?>

<?php $__env->startSection('content'); ?>

<?php if (isset($component)) { $__componentOriginaldb1b157d84f8f63332f3508c9e385c0a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldb1b157d84f8f63332f3508c9e385c0a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.flash-messages','data' => ['showIcons' => true,'dismissible' => true,'position' => 'top-right','autoHide' => 5000]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.flash-messages'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['show-icons' => true,'dismissible' => true,'position' => 'top-right','auto-hide' => 5000]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.page-header','data' => ['title' => 'Tin nhắn Liên hệ','description' => 'Quản lý tin nhắn từ khách hàng','icon' => 'fas fa-envelope']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tin nhắn Liên hệ','description' => 'Quản lý tin nhắn từ khách hàng','icon' => 'fas fa-envelope']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Tổng tin nhắn','value' => $messages->total(),'icon' => 'fas fa-envelope','color' => 'blue','description' => 'Tất cả tin nhắn','dataStat' => 'total']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Tổng tin nhắn','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($messages->total()),'icon' => 'fas fa-envelope','color' => 'blue','description' => 'Tất cả tin nhắn','dataStat' => 'total']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Mới','value' => \App\Models\ContactMessage::where('status', 'new')->count(),'icon' => 'fas fa-envelope','color' => 'orange','description' => 'Tin nhắn mới','dataStat' => 'new']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Mới','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\ContactMessage::where('status', 'new')->count()),'icon' => 'fas fa-envelope','color' => 'orange','description' => 'Tin nhắn mới','dataStat' => 'new']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Đang xử lý','value' => \App\Models\ContactMessage::where('status', 'in_progress')->count(),'icon' => 'fas fa-spinner','color' => 'blue','description' => 'Đang xử lý','dataStat' => 'in_progress']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Đang xử lý','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\ContactMessage::where('status', 'in_progress')->count()),'icon' => 'fas fa-spinner','color' => 'blue','description' => 'Đang xử lý','dataStat' => 'in_progress']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Đã giải quyết','value' => \App\Models\ContactMessage::where('status', 'resolved')->count(),'icon' => 'fas fa-check-circle','color' => 'green','description' => 'Hoàn thành','dataStat' => 'resolved']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Đã giải quyết','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\ContactMessage::where('status', 'resolved')->count()),'icon' => 'fas fa-check-circle','color' => 'green','description' => 'Hoàn thành','dataStat' => 'resolved']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stats-card','data' => ['title' => 'Đã đóng','value' => \App\Models\ContactMessage::where('status', 'closed')->count(),'icon' => 'fas fa-times-circle','color' => 'gray','description' => 'Đã đóng','dataStat' => 'closed']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stats-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Đã đóng','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\ContactMessage::where('status', 'closed')->count()),'icon' => 'fas fa-times-circle','color' => 'gray','description' => 'Đã đóng','dataStat' => 'closed']); ?>
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
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="<?php echo e(route('admin.contact-messages.index')); ?>">
            
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <?php if (isset($component)) { $__componentOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5cb383ddee3a6dc44b6e82e90e14b261 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.search-input','data' => ['name' => 'search','placeholder' => 'Tìm theo tên, email, tiêu đề...','value' => request('search'),'callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.search-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','placeholder' => 'Tìm theo tên, email, tiêu đề...','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('search')),'callbackName' => 'handleSearch','debounceTime' => 500,'size' => 'small','showIcon' => true,'showClearButton' => true]); ?>
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
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'resolved' => 'Đã giải quyết',
                        'closed' => 'Đã đóng'
                    ],'selected' => request('status'),'placeholder' => 'Tất cả','onchange' => 'loadMessagesFromDropdown','searchable' => false,'width' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.custom-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'status','options' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'resolved' => 'Đã giải quyết',
                        'closed' => 'Đã đóng'
                    ]),'selected' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(request('status')),'placeholder' => 'Tất cả','onchange' => 'loadMessagesFromDropdown','searchable' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(false),'width' => 'w-full']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.reset-button','data' => ['formId' => '#filterForm','callback' => 'loadMessages']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.reset-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['formId' => '#filterForm','callback' => 'loadMessages']); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.ajax-table','data' => ['tableId' => 'messages-content','loadingId' => 'loading-state','formId' => '#filterForm','baseUrl' => ''.e(route('admin.contact-messages.index')).'','callbackName' => 'loadMessages','afterLoadCallback' => 'initializeEventListeners']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.ajax-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['table-id' => 'messages-content','loading-id' => 'loading-state','form-id' => '#filterForm','base-url' => ''.e(route('admin.contact-messages.index')).'','callback-name' => 'loadMessages','after-load-callback' => 'initializeEventListeners']); ?>
        <?php echo $__env->make('admin.contact-messages.partials.table', ['messages' => $messages], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.delete-modal','data' => ['modalId' => 'deleteMessageModal','title' => 'Xác nhận xóa tin nhắn','confirmText' => 'Xóa','cancelText' => 'Hủy','deleteCallbackName' => 'confirmDeleteMessage','entityType' => 'message']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.delete-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['modal-id' => 'deleteMessageModal','title' => 'Xác nhận xóa tin nhắn','confirm-text' => 'Xóa','cancel-text' => 'Hủy','delete-callback-name' => 'confirmDeleteMessage','entity-type' => 'message']); ?>
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
// Initialize event listeners
function initializeEventListeners() {
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const messageId = this.dataset.messageId;
            const senderName = this.dataset.messageName;
            const subject = this.dataset.messageSubject;
            
            if (window.deleteModalManager_deleteMessageModal) {
                window.deleteModalManager_deleteMessageModal.show({
                    entityName: `tin nhắn từ ${senderName}`,
                    details: `<strong>Tiêu đề:</strong> ${subject}<br>Hành động này không thể hoàn tác.`,
                    deleteUrl: `/admin/contact-messages/${messageId}`
                });
            }
        });
    });
    
    // Status update forms
    document.querySelectorAll('.status-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button');
            const originalHtml = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    if (window.loadMessages) {
                        window.loadMessages();
                    }
                    
                    const data = await response.json();
                    if (data.message && window.showMessage) {
                        window.showMessage(data.message, 'success');
                    }
                } else {
                    throw new Error('Request failed');
                }
            } catch (error) {
                console.error('Error:', error);
                if (window.showMessage) {
                    window.showMessage('Có lỗi xảy ra!', 'error');
                }
                button.innerHTML = originalHtml;
                button.disabled = false;
            }
        });
    });
}

// Dropdown callback
window.loadMessagesFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadMessages) {
        const formData = new FormData(searchForm);
        const url = '<?php echo e(route("admin.contact-messages.index")); ?>?' + new URLSearchParams(formData).toString();
        window.loadMessages(url);
    }
};

// Search callback
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadMessages) {
        const formData = new FormData(searchForm);
        const url = '<?php echo e(route("admin.contact-messages.index")); ?>?' + new URLSearchParams(formData).toString();
        window.loadMessages(url);
    }
};

// Delete confirmation
window.confirmDeleteMessage = function(data) {
    if (!data || !data.deleteUrl) return;
    
    if (window.deleteModalManager_deleteMessageModal) {
        window.deleteModalManager_deleteMessageModal.setLoading(true);
    }
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.deleteModalManager_deleteMessageModal) {
                window.deleteModalManager_deleteMessageModal.hide();
            }
            
            if (window.loadMessages) {
                window.loadMessages();
            }
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa tin nhắn thành công!', 'success');
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deleteMessageModal) {
            window.deleteModalManager_deleteMessageModal.setLoading(false);
        }
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra khi xóa tin nhắn!', 'error');
        }
    });
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/contact-messages/index.blade.php ENDPATH**/ ?>