<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Quên mật khẩu</h2>
        <p class="mt-1 text-sm text-gray-600">Nhập email của bạn, hệ thống sẽ gửi liên kết đặt lại mật khẩu.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form @submit="handleForgotPasswordSubmit($event)" method="POST" action="{{ route('password.email') }}" class="space-y-5" novalidate>
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <input id="email" type="text" name="email" value="{{ old('email') }}" autofocus placeholder="you@example.com"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-white font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-paper-plane"></i>
            Gửi liên kết đặt lại
        </button>

        <script>
            // Forgot password validation functions
            function showError(field, message) {
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                
                if (message) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-sm text-red-600 mt-1';
                    errorDiv.textContent = message;
                    field.parentNode.appendChild(errorDiv);
                }
            }
            
            function clearError(field) {
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
                
                field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                field.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
            }
            
            // Clear errors on input
            document.addEventListener('DOMContentLoaded', function() {
                const emailField = document.getElementById('email');
                if (emailField) {
                    emailField.addEventListener('input', function() {
                        clearError(this);
                    });
                }
            });
            
            // Handle forgot password form submission
            function handleForgotPasswordSubmit(event) {
                const form = event.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                const emailField = form.querySelector('#email');
                
                let hasErrors = false;
                
                // Clear previous errors
                clearError(emailField);
                
                // Validate email
                if (!emailField.value.trim()) {
                    showError(emailField, 'Vui lòng nhập email.');
                    hasErrors = true;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
                    showError(emailField, 'Email không đúng định dạng.');
                    hasErrors = true;
                }
                
                // If validation fails, prevent submit
                if (hasErrors) {
                    event.preventDefault();
                    emailField.focus();
                    return false;
                }
                
                // Show loading state only if validation passes
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
                    
                    // Reset after 10 seconds (longer for email sending)
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 10000);
                }
                
                return true;
            }
        </script>

        <div class="text-sm text-center text-gray-600"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Quay lại đăng nhập</a></div>
    </form>
</x-guest-layout>
