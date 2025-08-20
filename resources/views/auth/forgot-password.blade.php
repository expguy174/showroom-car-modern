<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Quên mật khẩu</h2>
        <p class="mt-1 text-sm text-gray-600">Nhập email của bạn, hệ thống sẽ gửi liên kết đặt lại mật khẩu.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form @submit="showButtonLoading($event.target.querySelector('button[type=submit]'), 'Đang gửi...')" method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com"
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

        <div class="text-sm text-center text-gray-600"><a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Quay lại đăng nhập</a></div>
    </form>
</x-guest-layout>
