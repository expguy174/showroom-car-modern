<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'AutoLux Showroom') }}</title>

    <!-- No external fonts - CSP Compliant -->
    
    <!-- Font Awesome (Local Only - CSP Safe) -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
    
    <!-- Fix FontAwesome Icons & Font Family - CSP Safe -->
    <style>
        /* Use system fonts only - no external fonts */
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif !important;
        }
        
        /* FontAwesome Icons Fix */
        .fas, .far, .fab, .fa {
            font-family: "Font Awesome 6 Free", "Font Awesome 5 Free", "FontAwesome" !important;
            font-weight: 900 !important;
            font-style: normal !important;
        }
        .far {
            font-weight: 400 !important;
        }
        .fab {
            font-family: "Font Awesome 6 Brands", "Font Awesome 5 Brands" !important;
            font-weight: 400 !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    @php
        $user = Auth::user();
        $profile = $user->userProfile;
        $role = $user->role;
        
        $roleLabels = [
            'admin' => 'Quản trị viên',
            'manager' => 'Quản lý',
            'sales_person' => 'Nhân viên Kinh doanh',
            'technician' => 'Kỹ thuật viên',
            'user' => 'Người dùng'
        ];
        
        $roleColors = [
            'admin' => 'bg-red-100 text-red-800',
            'manager' => 'bg-purple-100 text-purple-800',
            'sales_person' => 'bg-blue-100 text-blue-800',
            'technician' => 'bg-green-100 text-green-800',
            'user' => 'bg-gray-100 text-gray-800'
        ];
    @endphp

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <div class="w-64 bg-white shadow-lg">
            {{-- Logo --}}
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-car text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">AutoLux Admin</h1>
                        <p class="text-xs text-gray-500">Quản trị hệ thống</p>
                    </div>
                </div>
            </div>

            {{-- User Info --}}
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                        {{ strtoupper(substr($profile->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $profile->name ?? 'Admin' }}</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $roleColors[$role] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $roleLabels[$role] ?? ucfirst($role) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="p-4 space-y-2">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>

                {{-- Catalog Management (All Staff) --}}
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Danh mục sản phẩm</p>
                    
                    @if(in_array($role, ['admin', 'manager']))
                    <a href="{{ route('admin.cars.index') }}" class="flex items-center px-3 py-2 mt-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.cars.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-industry mr-3"></i>
                        Hãng xe
                    </a>
                    
                    <a href="{{ route('admin.carmodels.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.carmodels.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-layer-group mr-3"></i>
                        Dòng xe
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.carvariants.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.carvariants.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-cubes mr-3"></i>
                        Phiên bản xe
                    </a>
                    
                    <a href="{{ route('admin.accessories.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.accessories.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-puzzle-piece mr-3"></i>
                        Phụ kiện
                    </a>
                </div>

                {{-- Sales & Orders (Admin, Manager, Sales) --}}
                @if(in_array($role, ['admin', 'manager', 'sales_person']))
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Bán hàng & Đơn hàng</p>
                    
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-3 py-2 mt-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-shopping-cart mr-3"></i>
                        Đơn hàng
                    </a>
                    
                    <a href="{{ route('admin.test-drives.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.test-drives.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-car-side mr-3"></i>
                        Lái thử
                    </a>
                    
                    <a href="{{ route('admin.promotions.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.promotions.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-tags mr-3"></i>
                        Khuyến mãi
                    </a>
                </div>
                @endif

                {{-- Services (All Staff) --}}
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Dịch vụ</p>
                    
                    <a href="{{ route('admin.service-appointments.index') }}" class="flex items-center px-3 py-2 mt-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.service-appointments.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-calendar-check mr-3"></i>
                        Lịch hẹn dịch vụ
                    </a>
                    
                    @if(in_array($role, ['admin', 'manager']))
                    <a href="{{ route('admin.services.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.services.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-cogs mr-3"></i>
                        Quản lý dịch vụ
                    </a>
                    @endif
                </div>

                {{-- Showrooms & Locations (Admin, Manager) --}}
                @if(in_array($role, ['admin', 'manager']))
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Showroom & Địa điểm</p>
                    
                    <a href="{{ route('admin.dealerships.index') }}" class="flex items-center px-3 py-2 mt-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dealerships.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-handshake mr-3"></i>
                        Đại lý
                    </a>
                    
                    <a href="{{ route('admin.showrooms.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.showrooms.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-store mr-3"></i>
                        Showrooms
                    </a>
                </div>
                @endif

                {{-- Content & Marketing (Admin, Manager, Technician) --}}
                @if(in_array($role, ['admin', 'manager', 'technician']))
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Nội dung & Marketing</p>
                    
                    <a href="{{ route('admin.blogs.index') }}" class="flex items-center px-3 py-2 mt-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.blogs.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-newspaper mr-3"></i>
                        Tin tức & Blog
                    </a>
                </div>
                @endif

                {{-- System Management (Admin & Manager Only) --}}
                @if(in_array($role, ['admin', 'manager']))
                <div class="pt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Quản lý hệ thống</p>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 mt-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-users mr-3"></i>
                        Người dùng
                    </a>
                </div>
                @endif
            </nav>

            {{-- Bottom Actions --}}
            <div class="bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
                <a href="{{ route('home') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 mb-2">
                    <i class="fas fa-external-link-alt mr-3"></i>
                    Xem trang chủ
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top Header --}}
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                            @if(isset($breadcrumbs))
                            <nav class="flex mt-1" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                    @foreach($breadcrumbs as $breadcrumb)
                                    <li class="inline-flex items-center">
                                        @if(!$loop->last)
                                        <a href="{{ $breadcrumb['url'] }}" class="text-sm text-gray-500 hover:text-gray-700">{{ $breadcrumb['title'] }}</a>
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        @else
                                        <span class="text-sm text-gray-700">{{ $breadcrumb['title'] }}</span>
                                        @endif
                                    </li>
                                    @endforeach
                                </ol>
                            </nav>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            {{-- Notifications --}}
                            <button class="relative p-2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-bell"></i>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                            
                            {{-- Quick Actions --}}
                            <div class="flex items-center space-x-2">
                                @if(in_array($role, ['admin', 'manager']))
                                <a href="{{ route('admin.showrooms.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-store mr-2"></i>
                                    Showrooms
                                </a>
                                @endif
                                @if(in_array($role, ['admin', 'manager', 'sales_person']))
                                <a href="{{ route('admin.orders.create') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tạo đơn hàng
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Success/Error Messages --}}
            @if(session('success'))
            <div class="mx-6 mt-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mx-6 mt-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            {{-- Main Content Area --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
