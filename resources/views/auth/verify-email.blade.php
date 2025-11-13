<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Xác thực email của bạn</h2>
        <p class="mt-1 text-sm text-gray-600">Vui lòng xác thực địa chỉ email để tiếp tục sử dụng tài khoản.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 rounded-lg {{ in_array(session('status'), ['verification-link-sent', 'email-verified']) ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
            <div class="flex items-start gap-2">
                <i class="fas {{ in_array(session('status'), ['verification-link-sent', 'email-verified']) ? 'fa-check-circle' : 'fa-info-circle' }} mt-0.5"></i>
                <div class="flex-1">
    @if (session('status') == 'verification-link-sent')
                        <p class="font-medium">Email xác thực đã được gửi!</p>
                        <p class="text-sm mt-1">Một liên kết xác thực mới đã được gửi đến địa chỉ email bạn đã cung cấp khi đăng ký.</p>
                    @elseif (session('status') == 'email-verified')
                        <p class="font-medium">Xác thực email thành công!</p>
                        <p class="text-sm mt-1">Địa chỉ email của bạn đã được xác thực thành công. Bạn có thể tiếp tục sử dụng tài khoản.</p>
                    @else
                        <p class="font-medium">{{ session('status') }}</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border border-red-200">
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-circle mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-medium">Không thể gửi email</p>
                    <p class="text-sm mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (!auth()->user()->hasVerifiedEmail() && session('status') !== 'email-verified')
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-envelope text-indigo-600"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-700 leading-relaxed">
                        Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác thực địa chỉ email của bạn bằng cách nhấp vào liên kết trong email chúng tôi vừa gửi cho bạn. 
                        Nếu bạn không nhận được email, chúng tôi sẽ vui lòng gửi lại cho bạn.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        @if (auth()->user()->hasVerifiedEmail())
            <a href="{{ route('home') }}" onclick="clearCacheAndNavigate(event)" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-home"></i>
                <span>Tiếp tục đến trang chủ</span>
            </a>
        @else
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-paper-plane"></i>
                    <span>Gửi lại email xác thực</span>
                </button>
        </form>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 text-sm text-gray-600 hover:text-gray-900 font-medium rounded-lg border border-gray-300 hover:border-gray-400 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </button>
        </form>
    </div>

    <div class="mt-6 pt-6 border-t border-gray-200">
        <p class="text-xs text-gray-500 text-center">
            <i class="fas fa-info-circle mr-1"></i>
            Kiểm tra cả hộp thư spam nếu bạn không thấy email xác thực trong hộp thư đến.
        </p>
    </div>

    @push('scripts')
    <script>
        function clearCacheAndNavigate(event) {
            // Force browser to reload page without cache
            // Add timestamp to URL to bypass browser cache
            event.preventDefault();
            const url = '{{ route("home") }}?_nocache=' + Date.now();
            window.location.href = url;
        }
    </script>
    @endpush
</x-guest-layout>
