
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bài viết</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tác giả</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10">
                                <?php $img = $blog->image_url ?? null; ?>
                                <?php if($img): ?>
                                <img class="h-10 w-10 rounded-lg object-cover" src="<?php echo e($img); ?>" alt="<?php echo e($blog->title); ?>">
                                <?php else: ?>
                                <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-gray-400 text-sm"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-900 truncate"><?php echo e($blog->title); ?></div>
                                <?php if($blog->content): ?>
                                <div class="text-xs text-gray-500 truncate"><?php echo e(Str::limit(strip_tags($blog->content), 60)); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            
                            <div class="flex-shrink-0">
                                <?php if($blog->user && $blog->user->userProfile && $blog->user->userProfile->avatar_path): ?>
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                         src="<?php echo e(Storage::url($blog->user->userProfile->avatar_path)); ?>" 
                                         alt="<?php echo e($blog->user->userProfile->name ?? $blog->user->email); ?>">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            <?php echo e(strtoupper(mb_substr(optional($blog->user)->userProfile->name ?? optional($blog->user)->email ?? 'U', 0, 2, 'UTF-8'))); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    <?php echo e(optional($blog->user)->userProfile->name ?? 'Chưa có tên'); ?>

                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    <?php echo e(optional($blog->user)->email ?? 'user@example.com'); ?>

                                </div>
                                <?php if($blog->user && $blog->user->userProfile && $blog->user->userProfile->phone): ?>
                                <div class="text-sm text-gray-500 mt-1 truncate">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    <?php echo e($blog->user->userProfile->phone); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex flex-col items-center space-y-1">
                            
                            <?php if($blog->is_active): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-eye mr-1"></i>Hiển thị
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-eye-slash mr-1"></i>Ẩn
                            </span>
                            <?php endif; ?>
                            
                            
                            <?php if($blog->is_featured): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>Nổi bật
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-star-o mr-1"></i>Thường
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo e($blog->created_at->format('d/m/Y H:i')); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <div class="flex items-center justify-center gap-2">
                            
                            <button type="button" 
                                    class="status-toggle <?php echo e($blog->is_active ? 'text-green-600 hover:text-green-900' : 'text-orange-600 hover:text-orange-900'); ?> w-4 h-4 flex items-center justify-center" 
                                    data-blog-id="<?php echo e($blog->id); ?>"
                                    data-status="<?php echo e($blog->is_active ? 'true' : 'false'); ?>"
                                    title="<?php echo e($blog->is_active ? 'Tạm dừng' : 'Kích hoạt'); ?>">
                                <i class="fas <?php echo e($blog->is_active ? 'fa-play' : 'fa-pause'); ?> w-4 h-4"></i>
                            </button>
                            
                            <a href="<?php echo e(route('admin.blogs.edit', $blog)); ?>" 
                               class="text-blue-600 hover:text-blue-900" 
                               title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="text-red-600 hover:text-red-900 delete-btn" 
                                    data-blog-id="<?php echo e($blog->id); ?>"
                                    data-blog-title="<?php echo e(addslashes($blog->title)); ?>"
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
                            <i class="fas fa-newspaper text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-medium">Không tìm thấy bài viết nào</p>
                            <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tạo bài viết mới</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($blogs->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $blogs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($blogs)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/blogs/partials/table.blade.php ENDPATH**/ ?>