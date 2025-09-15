<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Chào mừng trở lại</h2>
        <p class="mt-1 text-sm text-gray-600">Đăng nhập để tiếp tục quản lý showroom của bạn.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form x-data="{ show: false }" @submit="handleLoginSubmit($event)" method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}">Quên mật khẩu?</a>
                @endif
            </div>
            <div class="mt-1 relative">
                <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 pr-12" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Ghi nhớ đăng nhập</span>
            </label>
            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('register') }}">Tạo tài khoản</a>
        </div>

        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-white font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-sign-in-alt"></i>
            Đăng nhập
        </button>
        <script>
            (function(){
                const form = document.currentScript.closest('form');
                if (!form) return;
                
                // Enhanced validation with better UX
                const setMsg = (el, msg) => { 
                    if (el) { 
                        el.setCustomValidity(msg || ''); 
                        // Show custom error message
                        if (msg) {
                            el.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            el.classList.remove('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                        } else {
                            el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            el.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                        }
                    } 
                };
                
                const email = form.querySelector('#email');
                const pass = form.querySelector('#password');
                
                // Email validation
                if (email) {
                    email.addEventListener('invalid', ()=> {
                        if (email.validity.valueMissing) {
                            setMsg(email, 'Vui lòng nhập email.');
                        } else if (email.validity.typeMismatch) {
                            setMsg(email, 'Email không đúng định dạng.');
                        }
                    });
                    email.addEventListener('input', ()=> {
                        setMsg(email, '');
                        // Real-time email format check
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (email.value && !emailRegex.test(email.value)) {
                            setMsg(email, 'Email không đúng định dạng.');
                        }
                    });
                }
                
                // Password validation
                if (pass) {
                    pass.addEventListener('invalid', ()=> {
                        if (pass.validity.valueMissing) {
                            setMsg(pass, 'Vui lòng nhập mật khẩu.');
                        }
                    });
                    pass.addEventListener('input', ()=> setMsg(pass, ''));
                }
                
                // Form submission with better error handling
                form.addEventListener('submit', function(e) {
                    // Clear previous errors
                    [email, pass].forEach(el => {
                        if (el) {
                            el.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
                            el.classList.add('border-gray-300', 'focus:border-indigo-500', 'focus:ring-indigo-500');
                        }
                    });
                    
                    // Check if form is valid
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        // Focus first invalid field
                        const firstInvalid = form.querySelector(':invalid');
                        if (firstInvalid) {
                            firstInvalid.focus();
                            firstInvalid.dispatchEvent(new Event('invalid'));
                        }
                        return false;
                    }
                });
            })();
            
            // Handle login form submission with loading state
            function handleLoginSubmit(event) {
                const form = event.target;
                const submitBtn = form.querySelector('button[type="submit"]');
                
                // Show loading state
                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đăng nhập...';
                    
                    // Reset after 10 seconds as fallback
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 10000);
                }
                
                // Let form submit normally
                return true;
            }
        </script>
    </form>

    
</x-guest-layout>