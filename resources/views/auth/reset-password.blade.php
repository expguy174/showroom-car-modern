<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Đặt lại mật khẩu</h2>
        <p class="mt-1 text-sm text-gray-600">Nhập mật khẩu mới cho tài khoản của bạn.</p>
    </div>

    <form x-data="{ show1: false, show2: false }" @submit="handleResetPasswordSubmit($event)" method="POST" action="{{ route('password.store') }}" class="space-y-5" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <input id="email" type="text" name="email" value="{{ old('email', $request->email) }}" readonly
                       class="block w-full rounded-xl border-gray-300 bg-gray-50 shadow-sm px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
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
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
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

        <script>
            // Reset password validation functions
            function showError(field, message) {
                const existingError = field.parentNode.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                
                if (message) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-sm text-red-600 mt-1';
                    errorDiv.textContent = message;
                    field.parentNode.parentNode.appendChild(errorDiv);
                }
            }
            
            function clearError(field) {
                const existingError = field.parentNode.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            }
            
            // Clear errors on input
            document.addEventListener('DOMContentLoaded', function() {
                const fields = ['password', 'password_confirmation'];
                fields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (field) {
                        field.addEventListener('input', function() {
                            clearError(this);
                        });
                    }
                });
            });
            
            // Handle reset password form submission
            function handleResetPasswordSubmit(event) {
                const form = event.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                const passwordField = form.querySelector('#password');
                const confirmField = form.querySelector('#password_confirmation');
                
                let hasErrors = false;
                
                // Clear previous errors
                [passwordField, confirmField].forEach(field => {
                    if (field) clearError(field);
                });
                
                // Validate password
                if (!passwordField.value.trim()) {
                    showError(passwordField, 'Vui lòng nhập mật khẩu mới.');
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
                
                // If validation fails, prevent submit
                if (hasErrors) {
                    event.preventDefault();
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
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đặt lại...';
                    
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
            <i class="fas fa-key"></i>
            Đặt lại mật khẩu
        </button>

        <div class="text-sm text-center text-gray-600"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Quay lại đăng nhập</a></div>
    </form>
</x-guest-layout>
