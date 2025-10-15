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

    {{-- Mobile Menu Button --}}
    <div class="md:hidden fixed top-4 left-4 z-50">
        <button id="mobile-menu-button" class="p-2 rounded-lg bg-white shadow-lg border border-gray-200 text-gray-600 hover:text-gray-900">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>

    {{-- Mobile Overlay --}}
    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden"></div>

    {{-- Admin Layout --}}
    <div class="flex h-screen bg-gray-100">
        {{-- Sidebar --}}
        <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 md:flex md:flex-col">
            <div class="flex flex-col h-full overflow-hidden">
                {{-- Logo & Close Button --}}
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center group">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-indigo-700 rounded-xl flex items-center justify-center text-white shadow-md mr-3 group-hover:shadow-lg transition-shadow">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-base font-bold text-gray-900 group-hover:text-blue-700 transition-colors">AutoLux</h1>
                            <p class="text-xs text-gray-500">Hệ thống quản trị</p>
                        </div>
                    </a>
                    {{-- Mobile Close Button --}}
                    <button id="mobile-close-button" class="md:hidden p-1 rounded text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- User Profile --}}
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                            {{ strtoupper(substr($profile->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $profile->name ?? 'Admin' }}</h3>
                            <p class="text-xs text-gray-500">{{ $roleLabels[$role] ?? ucfirst($role) }}</p>
                        </div>
                    </div>
                </div>

            {{-- Enhanced Navigation --}}
            <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                {{-- 📦 SẢN PHẨM --}}
                <div>
                    <div class="flex items-center px-3 py-2 mb-2">
                        <i class="fas fa-box text-gray-500 mr-2 w-4"></i>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Sản phẩm</h3>
                    </div>
                    
                    @if(in_array($role, ['admin', 'manager']))
                    <a href="{{ route('admin.carbrands.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.carbrands.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-industry mr-3 w-4 text-center {{ request()->routeIs('admin.carbrands.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Hãng xe</span>
                    </a>
                    
                    <a href="{{ route('admin.carmodels.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.carmodels.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-layer-group mr-3 w-4 text-center {{ request()->routeIs('admin.carmodels.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Dòng xe</span>
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.carvariants.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.carvariants.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-cubes mr-3 w-4 text-center {{ request()->routeIs('admin.carvariants.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Phiên bản xe</span>
                    </a>
                    
                    <a href="{{ route('admin.accessories.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.accessories.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-puzzle-piece mr-3 w-4 text-center {{ request()->routeIs('admin.accessories.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Phụ kiện</span>
                    </a>
                    
                    <a href="{{ route('admin.reviews.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.reviews.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-star mr-3 w-4 text-center {{ request()->routeIs('admin.reviews.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Đánh giá</span>
                    </a>
                </div>

                {{-- 🛒 BÁN HÀNG --}}
                @if(in_array($role, ['admin', 'manager', 'sales_person']))
                <div class="pt-6">
                    <div class="flex items-center px-3 py-2 mb-2">
                        <i class="fas fa-shopping-cart text-gray-500 mr-2 w-4"></i>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Bán hàng</h3>
                    </div>
                    
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-receipt mr-3 w-4 text-center {{ request()->routeIs('admin.orders.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Đơn hàng</span>
                    </a>
                    
                    @if(in_array($role, ['admin', 'manager']))
                    <a href="{{ route('admin.installments.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.installments.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-calendar-check mr-3 w-4 text-center {{ request()->routeIs('admin.installments.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Lịch trả góp</span>
                    </a>
                    
                    <a href="{{ route('admin.finance-options.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.finance-options.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-calculator mr-3 w-4 text-center {{ request()->routeIs('admin.finance-options.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Gói trả góp</span>
                    </a>
                    @endif
                    
                    <a href="{{ route('admin.promotions.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.promotions.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-tags mr-3 w-4 text-center {{ request()->routeIs('admin.promotions.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Khuyến mãi</span>
                    </a>
                    
                    @if(in_array($role, ['admin', 'manager']))
                    <a href="{{ route('admin.payment-methods.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.payment-methods.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-credit-card mr-3 w-4 text-center {{ request()->routeIs('admin.payment-methods.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Phương thức TT</span>
                    </a>
                    
                    <a href="{{ route('admin.payments.refunds') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.payments.refunds*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-undo mr-3 w-4 text-center {{ request()->routeIs('admin.payments.refunds*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Hoàn tiền</span>
                    </a>
                    @endif
                </div>
                @endif

                {{-- 🔧 DỊCH VỤ --}}
                <div class="pt-6">
                    <div class="flex items-center px-3 py-2 mb-2">
                        <i class="fas fa-tools text-gray-500 mr-2 w-4"></i>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Dịch vụ</h3>
                    </div>
                    
                    <a href="{{ route('admin.service-appointments.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.service-appointments.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-calendar-check mr-3 w-4 text-center {{ request()->routeIs('admin.service-appointments.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Lịch hẹn</span>
                    </a>
                    
                    @if(in_array($role, ['admin', 'manager', 'sales_person']))
                    <a href="{{ route('admin.test-drives.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.test-drives.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-car-side mr-3 w-4 text-center {{ request()->routeIs('admin.test-drives.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Lái thử</span>
                    </a>
                    @endif
                    
                    @if(in_array($role, ['admin', 'manager']))
                    <a href="{{ route('admin.services.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-cogs mr-3 w-4 text-center {{ request()->routeIs('admin.services.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Quản lý dịch vụ</span>
                    </a>
                    @endif
                </div>

                {{-- 📊 BÁO CÁO --}}
                @if(in_array($role, ['admin', 'manager']))
                <div class="pt-6">
                    <div class="flex items-center px-3 py-2 mb-2">
                        <i class="fas fa-chart-bar text-gray-500 mr-2 w-4"></i>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Báo cáo</h3>
                    </div>
                    
                    <a href="{{ route('admin.analytics.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.analytics.dashboard') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-tachometer-alt mr-3 w-4 text-center {{ request()->routeIs('admin.analytics.dashboard') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Tổng quan</span>
                    </a>
                    
                    <a href="{{ route('admin.analytics.sales-report') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.analytics.sales-report') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-chart-bar mr-3 w-4 text-center {{ request()->routeIs('admin.analytics.sales-report') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Báo cáo bán hàng</span>
                    </a>
                    
                    <a href="{{ route('admin.analytics.customer-analytics') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.analytics.customer-analytics') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-users-cog mr-3 w-4 text-center {{ request()->routeIs('admin.analytics.customer-analytics') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Phân tích khách hàng</span>
                    </a>
                    
                    <a href="{{ route('admin.analytics.staff-performance') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.analytics.staff-performance') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-user-tie mr-3 w-4 text-center {{ request()->routeIs('admin.analytics.staff-performance') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Hiệu suất nhân viên</span>
                    </a>
                </div>
                @endif

                {{-- 👥 KHÁCH HÀNG --}}
                @if(in_array($role, ['admin', 'manager']))
                <div class="pt-6">
                    <div class="flex items-center px-3 py-2 mb-2">
                        <i class="fas fa-users text-gray-500 mr-2 w-4"></i>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Khách hàng</h3>
                    </div>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-user-circle mr-3 w-4 text-center {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Người dùng</span>
                    </a>
                    
                    <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.contact-messages.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-envelope mr-3 w-4 text-center {{ request()->routeIs('admin.contact-messages.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Liên hệ</span>
                    </a>
                </div>
                @endif

                {{-- 📝 NỘI DUNG --}}
                @if(in_array($role, ['admin', 'manager']))
                <div class="pt-6">
                    <div class="flex items-center px-3 py-2 mb-2">
                        <i class="fas fa-newspaper text-gray-500 mr-2 w-4"></i>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nội dung</h3>
                    </div>
                    
                    <a href="{{ route('admin.blogs.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.blogs.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-blog mr-3 w-4 text-center {{ request()->routeIs('admin.blogs.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Tin tức & Blog</span>
                    </a>
                </div>
                @endif

                {{-- 🏪 ĐỊA ĐIỂM --}}
                @if(in_array($role, ['admin', 'manager']))
                <div class="pt-6">
                    <div class="flex items-center px-3 py-2 mb-2">
                        <i class="fas fa-map-marker-alt text-gray-500 mr-2 w-4"></i>
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Địa điểm</h3>
                    </div>
                    
                    <a href="{{ route('admin.dealerships.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dealerships.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-handshake mr-3 w-4 text-center {{ request()->routeIs('admin.dealerships.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Đại lý</span>
                    </a>
                    
                    <a href="{{ route('admin.showrooms.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.showrooms.*') ? 'bg-blue-100 text-blue-700 border-r-2 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                        <i class="fas fa-store mr-3 w-4 text-center {{ request()->routeIs('admin.showrooms.*') ? 'text-blue-600' : 'text-gray-500' }}"></i>
                        <span>Showrooms</span>
                    </a>
                </div>
                @endif
            </nav>

            {{-- Bottom Actions --}}
            <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-white">
                <a href="{{ route('home') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-100 mb-2">
                    <i class="fas fa-external-link-alt mr-3 text-gray-500"></i>
                    <span>Xem trang chủ</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-3 text-red-500"></i>
                        Đăng xuất
                    </button>
                </form>
            </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden md:ml-0">
            {{-- Top Header --}}
            <header class="bg-white shadow-sm border-b border-gray-200 flex-shrink-0">
                <div class="px-4 md:px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-base md:text-xl font-semibold text-gray-900 truncate pl-12 md:pl-0">@yield('title', 'Dashboard')</h1>
                            @if(isset($breadcrumbs))
                            <nav class="flex mt-1 pl-12 md:pl-0" aria-label="Breadcrumb">
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
                            <div class="relative">
                                <button id="notifications-button" class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg">
                                    <i class="fas fa-bell"></i>
                                    @php
                                        // Đếm thông báo chưa đọc (có thể từ database)
                                        $unreadCount = 0; // Tạm thời = 0, sau này sẽ query từ DB
                                        
                                        // Ví dụ logic thông báo:
                                        // - Đơn hàng mới (pending orders)
                                        // - Lịch hẹn dịch vụ mới
                                        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                                        $pendingAppointments = \App\Models\ServiceAppointment::where('status', 'pending')->count();
                                        
                                        $unreadCount = $pendingOrders + $pendingAppointments;
                                    @endphp
                                    <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" style="display: none;">
                                        <span id="notification-count">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                                    </span>
                                </button>
                                
                                {{-- Notifications Dropdown --}}
                                <div id="notifications-dropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden">
                                    <div class="p-4 border-b border-gray-200">
                                        <h3 class="text-sm font-semibold text-gray-900">Thông báo</h3>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        {{-- Mock notifications --}}
                                        <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                                            <div class="flex items-start">
                                                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">Đơn hàng mới #12345</p>
                                                    <p class="text-sm text-gray-600">Khách hàng Nguyễn Văn A vừa đặt đơn hàng mới</p>
                                                    <p class="text-xs text-gray-400 mt-1">5 phút trước</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                                            <div class="flex items-start">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">Thanh toán thành công</p>
                                                    <p class="text-sm text-gray-600">Đơn hàng #12344 đã được thanh toán</p>
                                                    <p class="text-xs text-gray-400 mt-1">15 phút trước</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                                            <div class="flex items-start">
                                                <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-900">Lịch hẹn lái thử</p>
                                                    <p class="text-sm text-gray-600">Khách hàng đặt lịch lái thử BMW X5</p>
                                                    <p class="text-xs text-gray-400 mt-1">1 giờ trước</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="mark-all-read-section" class="p-3 border-t border-gray-200" style="display: none;">
                                        <button id="mark-all-read" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Đánh dấu tất cả đã đọc
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>


            {{-- Main Content Area --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="p-4 md:p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- Mobile Menu JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileCloseButton = document.getElementById('mobile-close-button');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', openSidebar);
            }

            if (mobileCloseButton) {
                mobileCloseButton.addEventListener('click', closeSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking on navigation links on mobile
            const navLinks = sidebar.querySelectorAll('a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        closeSidebar();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    closeSidebar();
                }
            });

            // Notifications dropdown
            const notificationsButton = document.getElementById('notifications-button');
            const notificationsDropdown = document.getElementById('notifications-dropdown');
            const markAllReadButton = document.getElementById('mark-all-read');
            const notificationBadge = document.getElementById('notification-badge');
            const notificationCount = document.getElementById('notification-count');
            const markAllReadSection = document.getElementById('mark-all-read-section');

            // Initialize notification state from localStorage and server data
            function initializeNotifications() {
                const isMarkedAsRead = localStorage.getItem('notifications_marked_as_read') === 'true';
                const serverCount = parseInt('{{ $unreadCount ?? 0 }}') || 0;
                
                if (isMarkedAsRead || serverCount === 0) {
                    if (notificationBadge) notificationBadge.style.display = 'none';
                    if (markAllReadSection) markAllReadSection.style.display = 'none';
                } else {
                    if (notificationBadge) notificationBadge.style.display = 'flex';
                    if (markAllReadSection) markAllReadSection.style.display = 'block';
                    if (notificationCount) notificationCount.textContent = serverCount > 99 ? '99+' : serverCount;
                }
            }

            if (notificationsButton && notificationsDropdown) {
                // Initialize on page load
                initializeNotifications();

                notificationsButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    notificationsDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!notificationsButton.contains(e.target) && !notificationsDropdown.contains(e.target)) {
                        notificationsDropdown.classList.add('hidden');
                    }
                });

                // Mark all as read functionality
                if (markAllReadButton) {
                    markAllReadButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Save state to localStorage
                        localStorage.setItem('notifications_marked_as_read', 'true');
                        
                        // Hide notification badge
                        if (notificationBadge) {
                            notificationBadge.style.display = 'none';
                        }
                        
                        // Hide mark all read button
                        if (markAllReadSection) {
                            markAllReadSection.style.display = 'none';
                        }
                        
                        // Show success message
                        const successMsg = document.createElement('div');
                        successMsg.className = 'p-3 text-center text-sm text-green-600 border-t border-gray-200';
                        successMsg.textContent = 'Đã đánh dấu tất cả thông báo là đã đọc';
                        notificationsDropdown.appendChild(successMsg);
                        
                        // Remove success message after 2 seconds
                        setTimeout(() => {
                            if (successMsg.parentNode) {
                                successMsg.parentNode.removeChild(successMsg);
                            }
                        }, 2000);
                        
                        // Optional: Make AJAX call to server
                        // fetch('/admin/notifications/mark-all-read', {
                        //     method: 'POST',
                        //     headers: {
                        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        //         'Content-Type': 'application/json'
                        //     }
                        // });
                    });
                }
            }

            // Reset notification state when new notifications arrive (you can call this function when needed)
            window.resetNotificationState = function() {
                localStorage.removeItem('notifications_marked_as_read');
                initializeNotifications();
            };
        });

    </script>

    <!-- User Toast System already loaded via main vite -->
    
    <!-- Admin Toast Integration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Flash messages are handled by component
            // No layout interference needed
            
            // Test function for debugging
            window.testToast = function() {
                if (typeof window.showMessage === 'function') {
                    showMessage('Test notification!', 'success');
                } else {
                    console.log('showMessage function not available yet - component may still be loading');
                }
            };
        });
    </script>

    @stack('scripts')
</body>
</html>
