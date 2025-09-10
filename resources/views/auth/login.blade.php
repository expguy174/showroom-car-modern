<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Chào mừng trở lại</h2>
        <p class="mt-1 text-sm text-gray-600">Đăng nhập để tiếp tục quản lý showroom của bạn.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form x-data="{ show: false }" @submit="showButtonLoading($event.target.querySelector('button[type=submit]'), 'Đang đăng nhập...')" method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
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
                const setMsg = (el, msg) => { if (el) { el.setCustomValidity(msg || ''); } };
                const email = form.querySelector('#email');
                const pass = form.querySelector('#password');
                if (email) email.addEventListener('invalid', ()=> setMsg(email, 'Vui lòng nhập email hợp lệ.'));
                if (email) email.addEventListener('input', ()=> setMsg(email, ''));
                if (pass) pass.addEventListener('invalid', ()=> setMsg(pass, 'Vui lòng nhập mật khẩu.'));
                if (pass) pass.addEventListener('input', ()=> setMsg(pass, ''));
            })();
        </script>
    </form>

    
</x-guest-layout>