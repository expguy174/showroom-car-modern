<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 20%;">Người gửi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 20%;">Tiêu đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 30%;">Nội dung</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50" data-message-id="<?php echo e($message->id); ?>">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-3">
                        
                        <div class="flex-shrink-0">
                            <?php if($message->user && $message->user->userProfile && $message->user->userProfile->avatar_path): ?>
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="<?php echo e(Storage::url($message->user->userProfile->avatar_path)); ?>" 
                                     alt="<?php echo e($message->name); ?>">
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
                                    <?php echo e(strtoupper(substr($message->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                        
                        
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate"><?php echo e($message->name); ?></div>
                            <div class="text-xs text-gray-500 truncate">
                                <i class="fas fa-envelope mr-1"></i><?php echo e($message->email); ?>

                            </div>
                            <?php if($message->phone): ?>
                            <div class="text-xs text-gray-500 truncate">
                                <i class="fas fa-phone mr-1"></i><?php echo e($message->phone); ?>

                            </div>
                            <?php endif; ?>
                            <div class="text-xs text-gray-500">
                                <i class="far fa-clock mr-1"></i><?php echo e($message->created_at->format('d/m/Y H:i')); ?>

                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900"><?php echo e($message->subject); ?></div>
                </td>
                <td class="px-6 py-4 max-w-md whitespace-nowrap">
                    <p class="text-sm text-gray-900 truncate"><?php echo e($message->message); ?></p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <?php switch($message->status):
                        case ('new'): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-envelope mr-1"></i>Mới
                            </span>
                            <?php break; ?>
                        <?php case ('in_progress'): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-spinner mr-1"></i>Đang xử lý
                            </span>
                            <?php break; ?>
                        <?php case ('resolved'): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Đã giải quyết
                            </span>
                            <?php break; ?>
                        <?php case ('closed'): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times-circle mr-1"></i>Đã đóng
                            </span>
                            <?php break; ?>
                    <?php endswitch; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                    <div class="flex items-center justify-center gap-2">
                        
                        <a href="<?php echo e(route('admin.contact-messages.show', $message)); ?>" 
                           class="text-blue-600 hover:text-blue-900" 
                           title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        
                        <?php switch($message->status):
                            case ('new'): ?>
                                <form action="<?php echo e(route('admin.contact-messages.update-status', $message)); ?>" method="POST" class="inline status-form">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" 
                                            class="text-blue-600 hover:text-blue-900" 
                                            title="Bắt đầu xử lý">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </form>
                                <?php break; ?>
                                
                            <?php case ('in_progress'): ?>
                                <form action="<?php echo e(route('admin.contact-messages.update-status', $message)); ?>" method="POST" class="inline status-form">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="resolved">
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-900" 
                                            title="Đánh dấu đã giải quyết">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                <?php break; ?>
                                
                            <?php case ('resolved'): ?>
                                <form action="<?php echo e(route('admin.contact-messages.update-status', $message)); ?>" method="POST" class="inline status-form">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="closed">
                                    <button type="submit" 
                                            class="text-purple-600 hover:text-purple-900" 
                                            title="Đóng tin nhắn">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
                                <?php break; ?>
                        <?php endswitch; ?>
                        
                        
                        <button type="button" 
                                class="text-red-600 hover:text-red-900 delete-btn" 
                                data-message-id="<?php echo e($message->id); ?>"
                                data-message-name="<?php echo e(addslashes($message->name)); ?>"
                                data-message-subject="<?php echo e(addslashes($message->subject)); ?>"
                                title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-envelope text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">Không tìm thấy tin nhắn nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm khác</p>
                    </div>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    
    <?php if($messages->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $messages]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($messages)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/contact-messages/partials/table.blade.php ENDPATH**/ ?>