<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Tạo tài khoản</h2>
        <p class="mt-1 text-sm text-gray-600">Đăng ký để nhận ưu đãi, đặt lịch lái thử và quản lý đơn hàng dễ dàng.</p>
    </div>

    <form x-data="{ show1: false, show2: false }" @submit="showButtonLoading($event.target.querySelector('button[type=submit]'), 'Đang tạo tài khoản...')" method="POST" action="{{ route('register') }}" class="space-y-5" novalidate>
        @csrf

        <!-- Name (required) -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Họ và tên <span class="text-red-500">*</span></label>
            <div class="mt-1 relative">
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nguyễn Văn A"
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
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required autocomplete="tel" placeholder="0901 234 567"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-phone"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5" />
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
            <div class="mt-1 relative">
                <input :type="show1 ? 'text' : 'password'" id="password" name="password" required minlength="8" autocomplete="new-password" placeholder="••••••••"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 pr-12" />
                <button type="button" @click="show1 = !show1" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i :class="show1 ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu</label>
            <div class="mt-1 relative">
                <input :type="show2 ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                       class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2.5 pr-12" />
                <button type="button" @click="show2 = !show2" class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                    <i :class="show2 ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Terms -->
        <div class="flex items-start gap-3">
            <input id="terms" name="terms" type="checkbox" required class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
            <label for="terms" class="text-sm text-gray-600">Tôi đồng ý với <a href="#" class="text-indigo-600 hover:underline">Điều khoản sử dụng</a> và <a href="#" class="text-indigo-600 hover:underline">Chính sách bảo mật</a>.</label>
        </div>
        <x-input-error :messages="$errors->get('terms')" class="mt-2" />

        <script>
            (function(){
                const form = document.currentScript.closest('form');
                if (!form) return;
                const setMsg = (el, msg) => { if (el) { el.setCustomValidity(msg || ''); } };
                const name = form.querySelector('#name');
                const phone = form.querySelector('#phone');
                const email = form.querySelector('#email');
                const pass = form.querySelector('#password');
                const pass2 = form.querySelector('#password_confirmation');
                const terms = form.querySelector('#terms');

                if (name) name.addEventListener('invalid', ()=> setMsg(name, 'Vui lòng nhập họ và tên.'));
                if (name) name.addEventListener('input', ()=> setMsg(name, ''));

                if (phone) phone.addEventListener('invalid', ()=> setMsg(phone, 'Vui lòng nhập số điện thoại.'));
                if (phone) phone.addEventListener('input', ()=> setMsg(phone, ''));

                if (email) email.addEventListener('invalid', ()=> setMsg(email, 'Vui lòng nhập email hợp lệ.'));
                if (email) email.addEventListener('input', ()=> setMsg(email, ''));

                if (pass) pass.addEventListener('invalid', ()=> setMsg(pass, 'Mật khẩu phải có ít nhất 8 ký tự.'));
                if (pass) pass.addEventListener('input', ()=> setMsg(pass, ''));

                if (pass2) pass2.addEventListener('invalid', ()=> setMsg(pass2, 'Vui lòng xác nhận mật khẩu.'));
                if (pass2) pass2.addEventListener('input', ()=> setMsg(pass2, ''));

                if (terms) terms.addEventListener('invalid', ()=> setMsg(terms, 'Bạn phải đồng ý với điều khoản sử dụng.'));
                if (terms) terms.addEventListener('input', ()=> setMsg(terms, ''));
            })();
        </script>

        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-white font-medium shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <i class="fas fa-user-plus"></i>
            Tạo tài khoản
        </button>

        <div class="text-sm text-center text-gray-600">Đã có tài khoản? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Đăng nhập</a></div>
    </form>
</x-guest-layout>

