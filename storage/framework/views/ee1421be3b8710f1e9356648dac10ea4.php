<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Người đánh giá</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Sản phẩm</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Tiêu đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 12%;">Đánh giá</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 23%;">Nội dung</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 10%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 10%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50" data-review-id="<?php echo e($review->id); ?>">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        
                        <div class="flex-shrink-0">
                            <?php if($review->user->userProfile && $review->user->userProfile->avatar_path): ?>
                                <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                     src="<?php echo e(Storage::url($review->user->userProfile->avatar_path)); ?>" 
                                     alt="<?php echo e($review->user->userProfile->name ?? $review->user->email); ?>">
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">
                                        <?php echo e(strtoupper(mb_substr($review->user->userProfile->name ?? $review->user->email, 0, 2, 'UTF-8'))); ?>

                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">
                                <?php echo e($review->user->userProfile->name ?? $review->user->email); ?>

                            </div>
                            <div class="text-xs text-gray-500"><?php echo e($review->created_at->format('d/m/Y H:i')); ?></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900 whitespace-nowrap">
                        <?php echo e($review->reviewable->name ?? 'N/A'); ?>

                    </div>
                    <div class="text-xs text-gray-500"><?php echo e(class_basename($review->reviewable_type)); ?></div>
                </td>
                <td class="px-6 py-4">
                    <?php if($review->title): ?>
                        <p class="text-sm font-medium text-gray-900 truncate"><?php echo e($review->title); ?></p>
                    <?php else: ?>
                        <span class="text-sm text-gray-400 italic">Không có tiêu đề</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-300'); ?>"></i>
                        <?php endfor; ?>
                        <span class="ml-2 text-sm text-gray-600"><?php echo e($review->rating); ?>/5</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-900 truncate"><?php echo e($review->comment); ?></p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center status-cell">
                    <?php if($review->is_approved): ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Đã duyệt
                    </span>
                    <?php else: ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i>Chờ duyệt
                    </span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm actions-cell">
                    <div class="flex items-center justify-center gap-2">
                        <?php if(!$review->is_approved): ?>
                        <form action="<?php echo e(route('admin.reviews.approve', $review)); ?>" method="POST" class="inline approve-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="text-green-600 hover:text-green-900" title="Duyệt">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <?php else: ?>
                        <form action="<?php echo e(route('admin.reviews.reject', $review)); ?>" method="POST" class="inline reject-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="text-orange-600 hover:text-orange-900" title="Bỏ duyệt">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                        <button type="button" 
                                class="text-red-600 hover:text-red-900 delete-btn" 
                                data-review-id="<?php echo e($review->id); ?>"
                                data-review-name="<?php echo e(addslashes($review->user->userProfile->name ?? $review->user->email)); ?>"
                                title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-star text-4xl mb-2"></i>
                    <p>Chưa có đánh giá nào</p>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>

    
    <?php if($reviews->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $reviews]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($reviews)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/reviews/partials/table.blade.php ENDPATH**/ ?>