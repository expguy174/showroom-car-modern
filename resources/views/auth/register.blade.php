<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Tạo tài khoản</h2>
        <p class="mt-1 text-sm text-gray-600">Đăng ký để nhận ưu đãi, đặt lịch lái thử và quản lý đơn hàng dễ dàng.</p>
    </div>

    <form x-data="{ show1: false, show2: false }" @submit="handleRegisterSubmit($event)" method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
        @csrf

        <!-- Name (required) -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên <span class="text-red-500">*</span></label>
            <div class="mt-1 relative">
                <input id="name" type="text" name="name" value="{{ old('name') }}" autofocus autocomplete="name" placeholder="Nguyễn Văn A"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Phone (required) -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
            <div class="mt-1 relative">
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="0901 234 567"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-phone"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
            <div class="mt-1 relative">
                <input id="email" type="text" name="email" value="{{ old('email') }}" autocomplete="username" placeholder="you@example.com"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu <span class="text-red-500">*</span></label>
            <div class="mt-1 relative">
                <input :type="show1 ? 'text' : 'password'" id="password" name="password" autocomplete="new-password" placeholder="••••••••"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 pr-12" />
                <div class="absolute inset-y-0 right-3 flex items-center">
                    <button type="button" @click="show1 = !show1" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i :class="show1 ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu <span class="text-red-500">*</span></label>
            <div class="mt-1 relative">
                <input :type="show2 ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="••••••••"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 pr-12" />
                <div class="absolute inset-y-0 right-3 flex items-center">
                    <button type="button" @click="show2 = !show2" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i :class="show2 ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms -->
        <div class="flex items-start gap-3">
            <input id="terms" name="terms" type="checkbox" class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <div class="flex-1">
                <label for="terms" class="text-sm text-gray-600">Tôi đồng ý với <a href="#" class="text-indigo-600 hover:underline">Điều khoản sử dụng</a> và <a href="#" class="text-indigo-600 hover:underline">Chính sách bảo mật</a>.</label>
                <x-input-error :messages="$errors->get('terms')" class="mt-1" />
            </div>
        </div>

        <script>
            // Register validation functions (same style as login)
            function showError(field, message) {
                let container;
                
                // Special handling for terms checkbox
                if (field.type === 'checkbox' && field.id === 'terms') {
                    container = field.parentNode.querySelector('.flex-1');
                } else if (field.parentNode.classList.contains('relative')) {
                    // For password fields with icon
                    container = field.parentNode.parentNode;
                } else {
                    // For regular fields
                    container = field.parentNode;
                }
                
                const existingError = container.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                
                if (message) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-sm text-red-600 mt-1';
                    errorDiv.textContent = message;
                    container.appendChild(errorDiv);
                }
            }
            
            function clearError(field) {
                let container;
                
                // Special handling for terms checkbox
                if (field.type === 'checkbox' && field.id === 'terms') {
                    container = field.parentNode.querySelector('.flex-1');
                } else if (field.parentNode.classList.contains('relative')) {
                    // For password fields with icon
                    container = field.parentNode.parentNode;
                } else {
                    // For regular fields
                    container = field.parentNode;
                }
                
                const existingError = container.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            }
            
            // Clear errors on input
            document.addEventListener('DOMContentLoaded', function() {
                const fields = ['name', 'phone', 'email', 'password', 'password_confirmation'];
                fields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (field) {
                        field.addEventListener('input', function() {
                            clearError(this);
                        });
                    }
                });
                
                // Special handling for terms checkbox
                const termsField = document.getElementById('terms');
                if (termsField) {
                    termsField.addEventListener('change', function() {
                        clearError(this);
                    });
                }
            });
            
            // Handle register form submission
            function handleRegisterSubmit(event) {
                const form = event.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                const nameField = form.querySelector('#name');
                const phoneField = form.querySelector('#phone');
                const emailField = form.querySelector('#email');
                const passwordField = form.querySelector('#password');
                const confirmField = form.querySelector('#password_confirmation');
                const termsField = form.querySelector('#terms');
                
                let hasErrors = false;
                
                // Clear previous errors
                [nameField, phoneField, emailField, passwordField, confirmField].forEach(field => {
                    if (field) clearError(field);
                });
                
                // Validate name
                if (!nameField.value.trim()) {
                    showError(nameField, 'Vui lòng nhập họ và tên.');
                    hasErrors = true;
                }
                
                // Validate phone (Vietnamese format)
                if (!phoneField.value.trim()) {
                    showError(phoneField, 'Vui lòng nhập số điện thoại.');
                    hasErrors = true;
                } else if (!/^(0|\+84)[0-9]{9,10}$/.test(phoneField.value.replace(/\s/g, ''))) {
                    showError(phoneField, 'Số điện thoại không hợp lệ (VD: 0901234567).');
                    hasErrors = true;
                }
                
                // Validate email
                if (!emailField.value.trim()) {
                    showError(emailField, 'Vui lòng nhập email.');
                    hasErrors = true;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
                    showError(emailField, 'Email không đúng định dạng.');
                    hasErrors = true;
                }
                
                // Validate password
                if (!passwordField.value.trim()) {
                    showError(passwordField, 'Vui lòng nhập mật khẩu.');
                    hasErrors = true;
                } else if (passwordField.value.length < 8) {
                    showError(passwordField, 'Mật khẩu phải có ít nhất 8 ký tự.');
                    hasErrors = true;
                }
                
                // Validate password confirmation
                if (!confirmField.value.trim()) {
                    showError(confirmField, 'Vui lòng xác nhận mật khẩu.');
                    hasErrors = true;
                } else if (passwordField.value !== confirmField.value) {
                    showError(confirmField, 'Xác nhận mật khẩu không khớp.');
                    hasErrors = true;
                }
                
                // Validate terms
                if (!termsField.checked) {
                    showError(termsField, 'Bạn phải đồng ý với điều khoản sử dụng.');
                    hasErrors = true;
                }
                
                // If validation fails, prevent submit
                if (hasErrors) {
                    event.preventDefault();
                    // Focus first field with error
                    const firstErrorField = form.querySelector('.border-red-500');
                    if (firstErrorField) {
                        firstErrorField.focus();
                    }
                    return false;
                }
                
                // Show loading state only if validation passes
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo tài khoản...';
                    
                    // Reset after 5 seconds as fallback
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 5000);
                }
                
                return true;
            }
        </script>

        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-white font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-user-plus"></i>
            Tạo tài khoản
        </button>

        <div class="text-sm text-center text-gray-600">Đã có tài khoản? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Đăng nhập</a></div>
    </form>
</x-guest-layout>

