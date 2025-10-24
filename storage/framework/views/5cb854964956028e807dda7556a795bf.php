<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Chào mừng trở lại</h2>
        <p class="mt-1 text-sm text-gray-600">Đăng nhập để tiếp tục quản lý showroom của bạn.</p>
    </div>

    <!-- Session Status -->
    <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

    <form x-data="{ show: false }" @submit="handleLoginSubmit($event)" method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5" novalidate>
        <?php echo csrf_field(); ?>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <input id="email" type="text" name="email" value="<?php echo e(old('email')); ?>" autofocus autocomplete="username" placeholder="you@example.com"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('email'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('email')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                <?php if(Route::has('password.request')): ?>
                    <a class="text-sm text-indigo-600 hover:text-indigo-500" href="<?php echo e(route('password.request')); ?>">Quên mật khẩu?</a>
                <?php endif; ?>
            </div>
            <div class="mt-1 relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" autocomplete="current-password" placeholder="••••••••"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 pr-12" />
                <div class="absolute inset-y-0 right-3 flex items-center">
                    <button type="button" @click="show = !show" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
            </div>
            <?php if (isset($component)) { $__componentOriginalf94ed9c5393ef72725d159fe01139746 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf94ed9c5393ef72725d159fe01139746 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-error','data' => ['messages' => $errors->get('password'),'class' => 'mt-2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['messages' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($errors->get('password')),'class' => 'mt-2']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $attributes = $__attributesOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__attributesOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf94ed9c5393ef72725d159fe01139746)): ?>
<?php $component = $__componentOriginalf94ed9c5393ef72725d159fe01139746; ?>
<?php unset($__componentOriginalf94ed9c5393ef72725d159fe01139746); ?>
<?php endif; ?>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ghi nhớ đăng nhập</span>
            </label>
            <a class="text-sm text-gray-600 hover:text-gray-900" href="<?php echo e(route('register')); ?>">Tạo tài khoản</a>
        </div>

        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-white font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-sign-in-alt"></i>
            Đăng nhập
        </button>
        <script>
            // Simple validation - only JavaScript
            function showError(field, message) {
                // For password field with icon, use parentNode.parentNode
                const container = field.parentNode.classList.contains('relative') ? field.parentNode.parentNode : field.parentNode;
                const existingError = container.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                // Add error styling
                field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                
                // Add error message
                if (message) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-sm text-red-600 mt-1';
                    errorDiv.textContent = message;
                    container.appendChild(errorDiv);
                }
            }
            
            function clearError(field) {
                // For password field with icon, use parentNode.parentNode
                const container = field.parentNode.classList.contains('relative') ? field.parentNode.parentNode : field.parentNode;
                const existingError = container.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                // Remove error styling
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            }
            
            // Clear errors on input
            document.addEventListener('DOMContentLoaded', function() {
                const emailField = document.getElementById('email');
                const passwordField = document.getElementById('password');
                
                if (emailField) {
                    emailField.addEventListener('input', function() {
                        clearError(this);
                    });
                }
                
                if (passwordField) {
                    passwordField.addEventListener('input', function() {
                        clearError(this);
                    });
                }
            });
            
            // Handle login form submission with loading state
            function handleLoginSubmit(event) {
                const form = event.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                const emailField = form.querySelector('#email');
                const passwordField = form.querySelector('#password');
                
                // Validate fields before showing spinner
                let hasErrors = false;
                
                // Clear previous errors
                clearError(emailField);
                clearError(passwordField);
                
                // Check email
                if (!emailField.value.trim()) {
                    showError(emailField, 'Vui lòng nhập email.');
                    hasErrors = true;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
                    showError(emailField, 'Email không đúng định dạng.');
                    hasErrors = true;
                }
                
                // Check password
                if (!passwordField.value.trim()) {
                    showError(passwordField, 'Vui lòng nhập mật khẩu.');
                    hasErrors = true;
                }
                
                // If validation fails, prevent submit and focus first error
                if (hasErrors) {
                    event.preventDefault();
                    // Focus first field with error
                    if (!emailField.value.trim()) {
                        emailField.focus();
                    } else if (!passwordField.value.trim()) {
                        passwordField.focus();
                    }
                    return false;
                }
                
                // Show loading state only if validation passes
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đăng nhập...';
                    
                    // Reset after 5 seconds as fallback (reduced from 10s)
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 5000);
                }
                
                // Let form submit normally
                return true;
            }
        </script>
    </form>

    
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?><?php /**PATH C:\Users\forev\showroom-car-modern\resources\views/auth/login.blade.php ENDPATH**/ ?>