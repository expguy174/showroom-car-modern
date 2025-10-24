<?php $__env->startSection('title', 'Thêm dịch vụ mới'); ?>

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

<?php if(session('success')): ?>
<script>
    // Auto redirect after 2 seconds
    setTimeout(function() {
        window.location.href = "<?php echo e(route('admin.services.index')); ?>";
    }, 2000);
</script>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-7xl mx-auto">
    
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                    Thêm dịch vụ mới
                </h1>
                <p class="text-sm text-gray-600 mt-1">Tạo dịch vụ mới cho hệ thống</p>
            </div>
            <a href="<?php echo e(route('admin.services.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>
        </div>
    </div>

    
    <form id="serviceForm" action="<?php echo e(route('admin.services.store')); ?>" method="POST" class="p-6">
        <?php echo csrf_field(); ?>

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
                                Tên dịch vụ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('name')); ?>" 
                                   placeholder="VD: Bảo dưỡng định kỳ 10,000 km">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                                Mã dịch vụ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="code" id="code"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('code')); ?>" 
                                   placeholder="VD: BD-10K, SC-BRAKE">
                            <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Danh mục dịch vụ <span class="text-red-500">*</span>
                            </label>
                            <select name="category" id="category" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Chọn danh mục</option>
                                <option value="maintenance" <?php echo e(old('category') == 'maintenance' ? 'selected' : ''); ?>>Bảo dưỡng</option>
                                <option value="repair" <?php echo e(old('category') == 'repair' ? 'selected' : ''); ?>>Sửa chữa</option>
                                <option value="diagnostic" <?php echo e(old('category') == 'diagnostic' ? 'selected' : ''); ?>>Chẩn đoán</option>
                                <option value="cosmetic" <?php echo e(old('category') == 'cosmetic' ? 'selected' : ''); ?>>Làm đẹp</option>
                                <option value="emergency" <?php echo e(old('category') == 'emergency' ? 'selected' : ''); ?>>Khẩn cấp</option>
                            </select>
                            <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Giá dịch vụ (VNĐ) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="price" id="price"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       value="<?php echo e(old('price')); ?>" 
                                       placeholder="500000">
                                <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Thời gian (phút)
                                </label>
                                <input type="number" name="duration_minutes" id="duration_minutes"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       value="<?php echo e(old('duration_minutes')); ?>" 
                                       placeholder="60">
                                <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Mô tả dịch vụ
                            </label>
                            <textarea name="description" id="description" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      placeholder="Mô tả chi tiết về dịch vụ..."><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Thông tin bổ sung
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                                Yêu cầu thực hiện
                            </label>
                            <textarea name="requirements" id="requirements" rows="4" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['requirements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      placeholder="Các yêu cầu trước khi thực hiện dịch vụ..."><?php echo e(old('requirements')); ?></textarea>
                            <?php $__errorArgs = ['requirements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Ghi chú
                            </label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      placeholder="Ghi chú thêm về dịch vụ..."><?php echo e(old('notes')); ?></textarea>
                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                Thứ tự sắp xếp
                            </label>
                            <input type="number" name="sort_order" id="sort_order"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('sort_order', 0)); ?>">
                            <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-5">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-toggle-on text-blue-600 mr-2"></i>
                        Cài đặt
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                   <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                            <label for="is_active" class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Hoạt động</span>
                                <span class="text-xs text-gray-500 block">Cho phép khách hàng đặt lịch dịch vụ này</span>
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounde"
                                   <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                            <label for="is_featured" class="ml-3">
                                <span class="text-sm font-medium text-gray-700">Nổi bật</span>
                                <span class="text-xs text-gray-500 block">Hiển thị ưu tiên trên trang chủ</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
            <a href="<?php echo e(route('admin.services.index')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>
                Hủy
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Thêm
            </button>
        </div>
    </form>
</div>

<script>
// AJAX form submission
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('serviceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous errors
    clearErrors();
    
    // Client-side validation first
    const validationResult = validateServiceForm();
    if (!validationResult.isValid) {
        // Focus the field with error
        if (validationResult.element) {
            validationResult.element.focus();
            validationResult.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Add error styling
            validationResult.element.classList.add('border-red-300');
            validationResult.element.classList.remove('border-gray-300');
        }
        
        // Show specific flash message
        if (window.showMessage) {
            window.showMessage(validationResult.message, 'error');
        }
        return; // Stop submission
    }
    
    // Show loading state - target the form's submit button specifically
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...';
    
    // Prepare form data
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Server response:', text);
                try {
                    const data = JSON.parse(text);
                    throw { status: response.status, data: data };
                } catch (e) {
                    throw { status: response.status, data: { message: 'Server error: ' + text.substring(0, 100) } };
                }
            });
        }
        return response.text().then(text => {
            console.log('Success response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response:', text);
                throw { status: 200, data: { message: 'Invalid JSON response from server' } };
            }
        });
    })
    .then(data => {
        if (data.success) {
            // Reset button to original state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Thêm dịch vụ thành công!', 'success');
            }
            
            setTimeout(() => {
                window.location.href = data.redirect || '<?php echo e(route("admin.services.index")); ?>';
            }, 1500);
        } else {
            // Reset button on error
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        // Always reset button first
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
        
        // Handle validation errors
        if (error.status === 422 && error.data && error.data.errors) {
            handleValidationErrors(error.data.errors);
        } else if (error.data && error.data.message) {
            if (window.showMessage) {
                window.showMessage(error.data.message, 'error');
            }
        } else {
            if (window.showMessage) {
                window.showMessage(error.message || 'Có lỗi xảy ra khi thêm dịch vụ', 'error');
            }
        }
    })
    .finally(() => {
        // Ensure button is always reset if not success
        if (submitBtn.innerHTML.includes('spinner')) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
});

// Client-side validation function
function validateServiceForm() {
    // 1. Tên dịch vụ
    const nameField = document.getElementById('name');
    if (!nameField) {
        console.error('Name field not found');
        return { isValid: true };
    }
    if (!nameField.value.trim()) {
        return {
            isValid: false,
            element: nameField,
            message: 'Vui lòng nhập tên dịch vụ.'
        };
    }
    
    if (nameField.value.trim().length < 3) {
        return {
            isValid: false,
            element: nameField,
            message: 'Tên dịch vụ phải có ít nhất 3 ký tự.'
        };
    }
    
    // 2. Mã dịch vụ
    const codeField = document.getElementById('code');
    if (!codeField) {
        console.error('Code field not found');
        return { isValid: true };
    }
    if (!codeField.value.trim()) {
        return {
            isValid: false,
            element: codeField,
            message: 'Vui lòng nhập mã dịch vụ.'
        };
    }
    
    // 3. Danh mục
    const categoryField = document.getElementById('category');
    if (!categoryField) {
        console.error('Category field not found');
        return { isValid: true };
    }
    if (!categoryField.value) {
        return {
            isValid: false,
            element: categoryField,
            message: 'Vui lòng chọn danh mục dịch vụ.'
        };
    }
    
    // 4. Giá dịch vụ (required)
    const priceField = document.getElementById('price');
    if (!priceField) {
        console.error('Price field not found');
        return { isValid: true };
    }
    if (!priceField.value.trim()) {
        return {
            isValid: false,
            element: priceField,
            message: 'Vui lòng nhập giá dịch vụ.'
        };
    }
    const price = parseFloat(priceField.value);
    if (isNaN(price) || price < 0) {
        return {
            isValid: false,
            element: priceField,
            message: 'Giá dịch vụ phải là số không âm.'
        };
    }
    
    // 5. Thời gian (optional nhưng phải hợp lệ nếu nhập)
    const durationField = document.getElementById('duration_minutes');
    if (durationField && durationField.value.trim()) {
        const duration = parseInt(durationField.value);
        if (isNaN(duration) || duration < 0) {
            return {
                isValid: false,
                element: durationField,
                message: 'Thời gian thực hiện phải là số không âm.'
            };
        }
    }
    
    // 6. Thứ tự sắp xếp (optional nhưng phải hợp lệ nếu nhập)
    const sortOrderField = document.getElementById('sort_order');
    if (sortOrderField && sortOrderField.value.trim()) {
        const sortOrder = parseInt(sortOrderField.value);
        if (isNaN(sortOrder) || sortOrder < 0) {
            return {
                isValid: false,
                element: sortOrderField,
                message: 'Thứ tự sắp xếp phải là số không âm.'
            };
        }
    }
    
    return { isValid: true };
}

// Handle validation errors
function handleValidationErrors(errors) {
    // Define field priority order (top to bottom, left to right)
    const fieldOrder = [
        'name', 'code', 'category', 'price', 'duration_minutes',
        'description', 'requirements', 'notes', 'sort_order', 'is_active', 'is_featured'
    ];
    
    let firstErrorField = null;
    let firstErrorMessage = '';
    
    // Find first error field based on priority order
    for (const field of fieldOrder) {
        if (errors[field]) {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                firstErrorField = input;
                firstErrorMessage = translateError(errors[field][0]);
                break;
            }
        }
    }
    
    // Focus on first error field only
    if (firstErrorField) {
        firstErrorField.focus();
        firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Add error styling to first field only
        firstErrorField.classList.add('border-red-300');
        firstErrorField.classList.remove('border-gray-300');
        
        // Show flash message for first error
        if (window.showMessage) {
            window.showMessage(firstErrorMessage, 'error');
        }
    }
}

// Clear all errors
function clearErrors() {
    document.querySelectorAll('.border-red-300').forEach(input => {
        input.classList.remove('border-red-300');
        input.classList.add('border-gray-300');
    });
}

// Translate error messages to Vietnamese
function translateError(message) {
    const translations = {
        'The name field is required.': 'Tên dịch vụ là bắt buộc.',
        'The name must be at least 3 characters.': 'Tên dịch vụ phải có ít nhất 3 ký tự.',
        'The code field is required.': 'Mã dịch vụ là bắt buộc.',
        'The code has already been taken.': 'Mã dịch vụ đã tồn tại.',
        'The category field is required.': 'Danh mục dịch vụ là bắt buộc.',
        'The price must be a number.': 'Giá dịch vụ phải là số.',
        'The price must be at least 0.': 'Giá dịch vụ phải lớn hơn hoặc bằng 0.',
        'The duration minutes must be a number.': 'Thời gian thực hiện phải là số.',
        'The duration minutes must be at least 0.': 'Thời gian thực hiện phải lớn hơn hoặc bằng 0.',
        'The sort order must be a number.': 'Thứ tự sắp xếp phải là số.',
        'The sort order must be at least 0.': 'Thứ tự sắp xếp phải lớn hơn hoặc bằng 0.',
        'The selected category is invalid.': 'Danh mục được chọn không hợp lệ.'
    };
    
    return translations[message] || message;
}
}); // End DOMContentLoaded
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/admin/services/create.blade.php ENDPATH**/ ?>