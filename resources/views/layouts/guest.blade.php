<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Showroom') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        @php
            $brandCount = \App\Models\CarBrand::count();
            $carCount = \App\Models\CarModel::count();
            $brands = \App\Models\CarBrand::query()
                ->where('is_active', 1)
                ->orderBy('sort_order')
                ->take(6)
                ->get();
        @endphp
        <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
            <!-- Left: Brand / Hero -->
            <div class="relative hidden lg:flex items-center justify-center overflow-hidden bg-gradient-to-br from-indigo-900 via-slate-900 to-slate-800 p-12">
                <div class="absolute inset-0 opacity-30" style="background: radial-gradient(1200px 600px at 10% -10%, rgba(99,102,241,.7), transparent), radial-gradient(1000px 500px at 90% 110%, rgba(56,189,248,.5), transparent);"></div>
                <div class="relative z-10 max-w-xl text-white">
                    <div class="flex items-center gap-3 text-indigo-200/90">
                        <img src="{{ asset('images/logo.png') }}" alt="AutoLux" class="h-8 w-auto" onerror="this.remove()">
                        <span class="uppercase tracking-wider text-sm">AutoLux</span>
                    </div>
                    <h1 class="mt-4 text-4xl font-bold leading-tight">Khởi động hành trình cùng chiếc xe mơ ước</h1>
                    <p class="mt-4 text-slate-200/90 text-lg">Đăng nhập để quản lý xe, đơn hàng, lịch hẹn lái thử và dịch vụ bảo dưỡng một cách nhanh chóng.</p>

                    <div class="mt-8 flex gap-4">
                        <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-3">
                            <div class="text-2xl font-semibold">{{ number_format($brandCount) }}</div>
                            <div class="text-sm text-indigo-100/90">Thương hiệu</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-3">
                            <div class="text-2xl font-semibold">{{ number_format($carCount) }}</div>
                            <div class="text-sm text-indigo-100/90">Mẫu xe</div>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-wrap items-center gap-6 opacity-90">
                        @foreach ($brands as $brand)
                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="h-8 rounded-md bg-white/10 p-1 ring-1 ring-white/10">
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Auth Form -->
            <div class="flex items-center justify-center p-6 sm:p-10">
                <div class="w-full max-w-md">
                    <a href="/" class="inline-flex items-center gap-3 mb-8 text-gray-700 hover:text-gray-900">
                        <img src="{{ asset('images/logo.png') }}" alt="AutoLux" class="h-10 w-auto" onerror="this.remove()">
                        <span class="font-semibold">AutoLux</span>
                    </a>
                    <div class="bg-white shadow-xl shadow-slate-200/40 ring-1 ring-slate-900/5 rounded-2xl p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                    <p class="mt-6 text-center text-sm text-gray-500">
                        © {{ date('Y') }} {{ config('app.name', 'Showroom') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
