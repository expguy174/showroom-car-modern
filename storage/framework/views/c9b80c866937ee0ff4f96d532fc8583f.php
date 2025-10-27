<?php $__env->startSection('title', 'Chỉnh sửa showroom: ' . $showroom->name); ?>

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

<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-7xl mx-auto">
    
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-edit text-blue-600 mr-3"></i>
                    Chỉnh sửa showroom: <?php echo e($showroom->name); ?>

                </h1>
                <p class="text-sm text-gray-600 mt-1">Cập nhật thông tin showroom</p>
            </div>
            <a href="<?php echo e(route('admin.showrooms.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    
    <form id="showroomForm" action="<?php echo e(route('admin.showrooms.update', $showroom)); ?>" method="POST" class="p-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Thông tin cơ bản
                    </h3>

                    <div class="space-y-4">
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Tên showroom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo e(old('name', $showroom->name)); ?>"
                                placeholder="VD: Toyota Showroom Hà Nội">
                        </div>

                        
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã showroom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo e(old('code', $showroom->code)); ?>"
                                placeholder="VD: SR-HN-001">
                            <p class="mt-1 text-xs text-gray-500">Mã định danh duy nhất cho showroom</p>
                        </div>

                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Mô tả
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Nhập mô tả về showroom..."><?php echo e(old('description', $showroom->description)); ?></textarea>
                        </div>
                    </div>
                </div>

                
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-phone text-blue-600 mr-2"></i>
                        Thông tin liên hệ
                    </h3>

                    <div class="space-y-4">
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Số điện thoại <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone" id="phone"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo e(old('phone', $showroom->phone)); ?>"
                                placeholder="VD: 0243 1234 567">
                        </div>

                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="text" name="email" id="email"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo e(old('email', $showroom->email)); ?>"
                                placeholder="VD: showroom@example.com">
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-6">
                
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                        Địa chỉ
                    </h3>

                    <div class="space-y-4">
                        
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Địa chỉ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address" id="address"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo e(old('address', $showroom->address)); ?>"
                                placeholder="VD: 123 Đường ABC">
                        </div>

                        
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                Thành phố <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="city" id="city"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                value="<?php echo e(old('city', $showroom->city)); ?>"
                                placeholder="VD: Hà Nội">
                        </div>

                        
                        <div>
                            <label class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    <?php echo e(old('is_active', $showroom->is_active) ? 'checked' : ''); ?>>
                                <span class="ml-2 text-sm text-gray-700">
                                    Hoạt động
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
            <a href="<?php echo e(route('admin.showrooms.index')); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-times mr-2"></i>
                Hủy
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-save mr-2"></i>
                Cập nhật
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Form validation and submit
    document.getElementById('showroomForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Client-side validation first
        const validation = validateForm();
        if (!validation.isValid) {
            // Focus the field with error
            if (validation.element) {
                validation.element.focus();
                validation.element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }

            // Show specific flash message
            if (window.showMessage) {
                window.showMessage(validation.message, 'error');
            }
            return; // Stop submission
        }

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading ONLY after validation passes
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                if (window.showMessage) {
                    window.showMessage(data.message || 'Đã cập nhật showroom thành công!', 'success');
                }

                // Redirect
                setTimeout(() => {
                    window.location.href = '<?php echo e(route("admin.showrooms.index")); ?>';
                }, 1500);
            } else {
                // Handle validation errors
                if (response.status === 422 && data.errors) {
                    const firstError = Object.values(data.errors)[0][0];
                    const firstField = Object.keys(data.errors)[0];

                    // Show flash message
                    if (window.showMessage) {
                        window.showMessage(firstError, 'error');
                    }

                    // Focus first field with error
                    const field = document.querySelector(`[name="${firstField}"]`);
                    if (field) {
                        field.focus();
                        field.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            if (window.showMessage) {
                window.showMessage(error.message || 'Có lỗi xảy ra khi cập nhật showroom!', 'error');
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });

    function validateForm() {
        // 1. Tên showroom (required)
        const nameField = document.getElementById('name');
        if (!nameField || !nameField.value.trim()) {
            return {
                isValid: false,
                element: nameField,
                message: 'Vui lòng nhập tên showroom.'
            };
        }

        // 2. Mã showroom (required)
        const codeField = document.getElementById('code');
        if (!codeField || !codeField.value.trim()) {
            return {
                isValid: false,
                element: codeField,
                message: 'Vui lòng nhập mã showroom.'
            };
        }

        // 3. Số điện thoại (required)
        const phoneField = document.getElementById('phone');
        if (!phoneField || !phoneField.value.trim()) {
            return {
                isValid: false,
                element: phoneField,
                message: 'Vui lòng nhập số điện thoại.'
            };
        }

        // 4. Email (optional but must be valid if provided)
        const emailField = document.getElementById('email');
        if (emailField && emailField.value.trim()) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailField.value.trim())) {
                return {
                    isValid: false,
                    element: emailField,
                    message: 'Email không đúng định dạng.'
                };
            }
        }

        // 5. Địa chỉ (required)
        const addressField = document.getElementById('address');
        if (!addressField || !addressField.value.trim()) {
            return {
                isValid: false,
                element: addressField,
                message: 'Vui lòng nhập địa chỉ.'
            };
        }

        // 6. Thành phố (required)
        const cityField = document.getElementById('city');
        if (!cityField || !cityField.value.trim()) {
            return {
                isValid: false,
                element: cityField,
                message: 'Vui lòng nhập thành phố.'
            };
        }

        return {
            isValid: true
        };
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/showrooms/edit.blade.php ENDPATH**/ ?>