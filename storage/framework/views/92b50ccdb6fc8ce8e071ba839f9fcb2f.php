
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">
                        Thông tin người dùng
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">
                        Vai trò
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 20%;">
                        Thông tin nhân viên
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">
                        Trạng thái
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 10%;">
                        Ngày tạo
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap" style="width: 15%;">
                        Thao tác
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            
                            <div class="flex-shrink-0">
                                <?php if($user->userProfile && $user->userProfile->avatar_path): ?>
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                         src="<?php echo e(Storage::url($user->userProfile->avatar_path)); ?>" 
                                         alt="<?php echo e($user->userProfile->name ?? $user->email); ?>">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">
                                            <?php echo e(strtoupper(mb_substr($user->userProfile->name ?? $user->email, 0, 2, 'UTF-8'))); ?>

                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">
                                    <?php echo e($user->userProfile->name ?? 'Chưa có tên'); ?>

                                </div>
                                <div class="text-sm text-gray-500 truncate">
                                    <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                    <?php echo e($user->email); ?>

                                </div>
                                <?php if($user->userProfile && $user->userProfile->phone): ?>
                                <div class="text-sm text-gray-500 mt-1 truncate">
                                    <i class="fas fa-phone text-gray-400 mr-1"></i>
                                    <?php echo e($user->userProfile->phone); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>

                    
                    <td class="px-6 py-4 whitespace-nowrap text-left">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($user->getRoleColor()); ?>">
                            <?php echo e($user->getRoleLabel()); ?>

                        </span>
                    </td>

                    
                    <td class="px-6 py-4">
                        <?php if($user->role === 'user'): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600">
                                <i class="fas fa-user mr-1.5"></i>
                                Khách hàng
                            </span>
                        <?php else: ?>
                            <?php if($user->employee_id): ?>
                                <div class="text-sm text-gray-900">
                                    <i class="fas fa-id-card text-gray-400 mr-1"></i>
                                    <?php echo e($user->employee_id); ?>

                                </div>
                            <?php endif; ?>
                            <?php if($user->department): ?>
                                <div class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-building text-gray-400 mr-1"></i>
                                    <?php echo e($user->department); ?>

                                </div>
                            <?php endif; ?>
                            <?php if($user->position): ?>
                                <div class="text-sm text-gray-500 mt-1">
                                    <i class="fas fa-user-tie text-gray-400 mr-1"></i>
                                    <?php echo e($user->position); ?>

                                </div>
                            <?php endif; ?>
                            <?php if(!$user->employee_id && !$user->department && !$user->position): ?>
                                <span class="text-sm text-gray-400 italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>

                    
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex flex-col items-center gap-1">
                            <?php if (isset($component)) { $__componentOriginal34999d704fb4480704a28cb78ec57cce = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal34999d704fb4480704a28cb78ec57cce = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.status-toggle','data' => ['itemId' => $user->id,'currentStatus' => $user->is_active,'entityType' => 'user']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.status-toggle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item-id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->id),'current-status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user->is_active),'entity-type' => 'user']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal34999d704fb4480704a28cb78ec57cce)): ?>
<?php $attributes = $__attributesOriginal34999d704fb4480704a28cb78ec57cce; ?>
<?php unset($__attributesOriginal34999d704fb4480704a28cb78ec57cce); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal34999d704fb4480704a28cb78ec57cce)): ?>
<?php $component = $__componentOriginal34999d704fb4480704a28cb78ec57cce; ?>
<?php unset($__componentOriginal34999d704fb4480704a28cb78ec57cce); ?>
<?php endif; ?>
                            
                            <?php if($user->email_verified || $user->email_verified_at): ?>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-check-circle mr-1"></i>Đã xác thực
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Chưa xác thực
                                </span>
                            <?php endif; ?>
                        </div>
                    </td>

                    
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm text-gray-900"><?php echo e($user->created_at->format('d/m/Y')); ?></div>
                        <div class="text-xs text-gray-400"><?php echo e($user->created_at->format('H:i')); ?></div>
                    </td>

                    
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <?php if (isset($component)) { $__componentOriginal2cf8d150d764feb90655ba7ed73d9171 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2cf8d150d764feb90655ba7ed73d9171 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.table-actions','data' => ['item' => $user,'showRoute' => 'admin.users.show','editRoute' => 'admin.users.edit','deleteRoute' => 'admin.users.destroy','hasToggle' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.table-actions'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['item' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'show-route' => 'admin.users.show','edit-route' => 'admin.users.edit','delete-route' => 'admin.users.destroy','has-toggle' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2cf8d150d764feb90655ba7ed73d9171)): ?>
<?php $attributes = $__attributesOriginal2cf8d150d764feb90655ba7ed73d9171; ?>
<?php unset($__attributesOriginal2cf8d150d764feb90655ba7ed73d9171); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2cf8d150d764feb90655ba7ed73d9171)): ?>
<?php $component = $__componentOriginal2cf8d150d764feb90655ba7ed73d9171; ?>
<?php unset($__componentOriginal2cf8d150d764feb90655ba7ed73d9171); ?>
<?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-medium">Không tìm thấy người dùng nào</p>
                            <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc thêm người dùng mới</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($users->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-200">
        <?php if (isset($component)) { $__componentOriginal1f9437379ffbb940ff05ba93353d3cd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f9437379ffbb940ff05ba93353d3cd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.pagination','data' => ['paginator' => $users]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.pagination'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['paginator' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($users)]); ?>
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
<?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/users/partials/table.blade.php ENDPATH**/ ?>