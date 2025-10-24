<?php $__env->startSection('title', 'Chi tiết Tin nhắn'); ?>

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


<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
        <div class="flex items-start space-x-4">
            
            <div class="flex-shrink-0">
                <?php if($contactMessage->user && $contactMessage->user->userProfile && $contactMessage->user->userProfile->avatar_path): ?>
                    <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-200" 
                         src="<?php echo e(Storage::url($contactMessage->user->userProfile->avatar_path)); ?>" 
                         alt="<?php echo e($contactMessage->name); ?>">
                <?php else: ?>
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        <span class="text-white font-bold text-2xl">
                            <?php echo e(strtoupper(substr($contactMessage->name, 0, 1))); ?>

                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?php echo e($contactMessage->name); ?></h1>
                <div class="mt-1 space-y-1">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-envelope mr-1"></i><?php echo e($contactMessage->email); ?>

                    </p>
                    <?php if($contactMessage->phone): ?>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-phone mr-1"></i><?php echo e($contactMessage->phone); ?>

                    </p>
                    <?php endif; ?>
                </div>
                <div class="flex items-center mt-3 space-x-2 flex-wrap gap-2">
                    
                    <?php if($contactMessage->contact_type === 'user'): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-user mr-1"></i>Người dùng
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-user-plus mr-1"></i>Khách
                        </span>
                    <?php endif; ?>
                    
                    
                    <?php switch($contactMessage->status):
                        case ('new'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-envelope mr-1"></i>Mới
                            </span>
                            <?php break; ?>
                        <?php case ('in_progress'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-spinner mr-1"></i>Đang xử lý
                            </span>
                            <?php break; ?>
                        <?php case ('resolved'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Đã giải quyết
                            </span>
                            <?php break; ?>
                        <?php case ('closed'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times-circle mr-1"></i>Đã đóng
                            </span>
                            <?php break; ?>
                    <?php endswitch; ?>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.contact-messages.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 space-y-6">
        
        <?php if($contactMessage->subject): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">
                <i class="fas fa-heading text-blue-600 mr-2"></i>
                Tiêu đề
            </h2>
            <p class="text-gray-900 text-lg"><?php echo e($contactMessage->subject); ?></p>
        </div>
        <?php endif; ?>
        
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-comment-dots text-blue-600 mr-2"></i>
                Nội dung tin nhắn
            </h2>
            <div class="prose max-w-none">
                <p class="text-gray-900 whitespace-pre-line"><?php echo e($contactMessage->message); ?></p>
            </div>
        </div>
        
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Thông tin liên hệ
            </h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php if($contactMessage->topic): ?>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Chủ đề</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($contactMessage->topic_display); ?></dd>
                </div>
                <?php endif; ?>
                
                <?php if($contactMessage->source): ?>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nguồn</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?php echo e(ucfirst($contactMessage->source)); ?></dd>
                </div>
                <?php endif; ?>
                
                <?php if($contactMessage->showroom): ?>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Showroom</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div class="font-medium"><?php echo e($contactMessage->showroom->name); ?></div>
                        <?php if($contactMessage->showroom->address): ?>
                            <div class="text-xs text-gray-500 mt-0.5"><?php echo e($contactMessage->showroom->address); ?></div>
                        <?php endif; ?>
                        <?php if($contactMessage->showroom->phone): ?>
                            <div class="text-xs text-gray-500 mt-0.5">
                                <i class="fas fa-phone mr-1"></i><?php echo e($contactMessage->showroom->phone); ?>

                            </div>
                        <?php endif; ?>
                    </dd>
                </div>
                <?php endif; ?>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Thời gian gửi</dt>
                    <dd class="mt-1 text-sm text-gray-900"><?php echo e($contactMessage->created_at->format('d/m/Y H:i')); ?></dd>
                </div>
            </dl>
        </div>
        
        
        <?php if($contactMessage->metadata): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-database text-blue-600 mr-2"></i>
                Metadata
            </h2>
            <pre class="bg-gray-50 rounded-lg p-4 overflow-x-auto text-sm"><?php echo e(json_encode($contactMessage->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
        </div>
        <?php endif; ?>
    </div>
    
    
    <div class="space-y-6">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-tasks text-blue-600 mr-2"></i>
                Thao tác
            </h2>
            <div class="space-y-3">
                
                <?php switch($contactMessage->status):
                    case ('new'): ?>
                        
                        <form action="<?php echo e(route('admin.contact-messages.update-status', $contactMessage)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-arrow-right mr-2"></i>Bắt đầu xử lý
                            </button>
                        </form>
                        <?php break; ?>
                        
                    <?php case ('in_progress'): ?>
                        
                        <form action="<?php echo e(route('admin.contact-messages.update-status', $contactMessage)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check-circle mr-2"></i>Đánh dấu đã giải quyết
                            </button>
                        </form>
                        <?php break; ?>
                        
                    <?php case ('resolved'): ?>
                        
                        <form action="<?php echo e(route('admin.contact-messages.update-status', $contactMessage)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-archive mr-2"></i>Đóng tin nhắn
                            </button>
                        </form>
                        <?php break; ?>
                        
                    <?php case ('closed'): ?>
                        
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-check-circle text-3xl mb-2"></i>
                            <p class="text-sm">Tin nhắn đã được đóng</p>
                        </div>
                        <?php break; ?>
                <?php endswitch; ?>
                
                
                <div class="pt-3 border-t border-gray-200">
                    <form action="<?php echo e(route('admin.contact-messages.destroy', $contactMessage)); ?>" method="POST" 
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-trash mr-2"></i>Xóa tin nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Thông tin hệ thống
            </h2>
            
            
            <div class="mb-4 pb-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-clock mr-2"></i>Thời gian
                </h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-xs text-gray-500">Tạo lúc</dt>
                        <dd class="text-sm text-gray-900"><?php echo e($contactMessage->created_at->format('d/m/Y H:i:s')); ?></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Cập nhật</dt>
                        <dd class="text-sm text-gray-900"><?php echo e($contactMessage->updated_at->format('d/m/Y H:i:s')); ?></dd>
                    </div>
                    <?php if($contactMessage->handled_at): ?>
                    <div>
                        <dt class="text-xs text-gray-500">Xử lý lúc</dt>
                        <dd class="text-sm text-gray-900"><?php echo e($contactMessage->handled_at->format('d/m/Y H:i:s')); ?></dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>
            
            
            <?php if($contactMessage->handledBy): ?>
            <div class="mb-4 pb-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-user-check mr-2"></i>Người xử lý
                </h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-xs text-gray-500">Tên</dt>
                        <dd class="text-sm text-gray-900"><?php echo e($contactMessage->handledBy->name); ?></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900"><?php echo e($contactMessage->handledBy->email); ?></dd>
                    </div>
                </dl>
            </div>
            <?php endif; ?>
            
            
            <?php if($contactMessage->ip_address || $contactMessage->user_agent): ?>
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-chart-line mr-2"></i>Thông tin theo dõi
                </h3>
                <dl class="space-y-2">
                    <?php if($contactMessage->ip_address): ?>
                    <div>
                        <dt class="text-xs text-gray-500">Địa chỉ IP</dt>
                        <dd class="text-sm text-gray-900 font-mono"><?php echo e($contactMessage->ip_address); ?></dd>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($contactMessage->user_agent): ?>
                    <div>
                        <dt class="text-xs text-gray-500">User Agent</dt>
                        <dd class="text-sm text-gray-900 break-all"><?php echo e($contactMessage->user_agent); ?></dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/contact-messages/show.blade.php ENDPATH**/ ?>