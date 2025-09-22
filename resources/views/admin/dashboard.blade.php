@extends('layouts.admin')

@section('title', 'Trang quản trị hệ thống')

@section('content')
@php
    $user = Auth::user();
    $profile = $user->userProfile;
@endphp

{{-- Welcome Header --}}
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl p-6 text-white mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2">
                👋 Xin chào, {{ $profile->name ?? 'Admin' }}!
            </h1>
            <p class="text-blue-100 mb-3">
                Chào mừng bạn đến với hệ thống quản trị Showroom
            </p>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 {{ $user->getRoleColor() }} rounded-full text-sm font-medium">
                    {{ $user->getRoleLabel() }}
                </span>
                @if($user->department)
                <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                    {{ $user->department }}
                </span>
                @endif
            </div>
        </div>
        <div class="text-right">
            <div class="text-blue-100 text-sm">Lần đăng nhập cuối</div>
            <div class="font-semibold">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Chưa có' }}</div>
        </div>
    </div>
</div>

{{-- Statistics Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Users Stats --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng người dùng</p>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-user-plus mr-1"></i>
                    {{ \App\Models\User::where('created_at', '>=', now()->subDays(7))->count() }} mới tuần này
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Orders Stats --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Đơn hàng</p>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Order::count() }}</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-shopping-cart mr-1"></i>
                    {{ \App\Models\Order::where('created_at', '>=', now()->subDays(7))->count() }} mới tuần này
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Revenue Stats --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Doanh thu tháng</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($monthlyRevenue / 1000000, 1) }}M</p>
                <p class="text-xs text-purple-600 mt-1">
                    <i class="fas fa-chart-line mr-1"></i>
                    VNĐ
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    {{-- Products Stats --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Sản phẩm</p>
                @php
                    $totalProducts = $totalCarVariants + $totalAccessories;
                @endphp
                <p class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</p>
                <p class="text-xs text-orange-600 mt-1">
                    <i class="fas fa-car mr-1"></i>
                    {{ $totalCarVariants }} xe, {{ $totalAccessories }} phụ kiện
                </p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-box text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Admin Actions --}}
    @if($user->hasRole(['admin', 'manager']))
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Thao tác nhanh
        </h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                <i class="fas fa-users text-blue-600 mr-3"></i>
                <span class="text-sm font-medium text-blue-900">Quản lý Users</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                <i class="fas fa-shopping-cart text-green-600 mr-3"></i>
                <span class="text-sm font-medium text-green-900">Đơn hàng</span>
            </a>
            <a href="{{ route('admin.carvariants.index') }}" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                <i class="fas fa-car text-purple-600 mr-3"></i>
                <span class="text-sm font-medium text-purple-900">Xe hơi</span>
            </a>
            <a href="{{ route('admin.accessories.index') }}" class="flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors">
                <i class="fas fa-puzzle-piece text-orange-600 mr-3"></i>
                <span class="text-sm font-medium text-orange-900">Phụ kiện</span>
            </a>
        </div>
    </div>
    @endif

    {{-- Sales Actions --}}
    @if($user->hasRole(['admin', 'manager', 'sales_person']))
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-handshake text-blue-500 mr-2"></i>
            Kinh doanh
        </h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.orders.create') }}" class="flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                <i class="fas fa-plus text-indigo-600 mr-3"></i>
                <span class="text-sm font-medium text-indigo-900">Tạo đơn hàng</span>
            </a>
            <a href="{{ route('admin.test-drives.index') }}" class="flex items-center p-3 bg-teal-50 hover:bg-teal-100 rounded-lg transition-colors">
                <i class="fas fa-car-side text-teal-600 mr-3"></i>
                <span class="text-sm font-medium text-teal-900">Lái thử</span>
            </a>
            <a href="{{ route('admin.promotions.index') }}" class="flex items-center p-3 bg-pink-50 hover:bg-pink-100 rounded-lg transition-colors">
                <i class="fas fa-tags text-pink-600 mr-3"></i>
                <span class="text-sm font-medium text-pink-900">Khuyến mãi</span>
            </a>
            <a href="{{ route('admin.service-appointments.index') }}" class="flex items-center p-3 bg-cyan-50 hover:bg-cyan-100 rounded-lg transition-colors">
                <i class="fas fa-tools text-cyan-600 mr-3"></i>
                <span class="text-sm font-medium text-cyan-900">Dịch vụ</span>
            </a>
        </div>
    </div>
    @endif
</div>

{{-- Recent Activities & System Info --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Recent Orders --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-clock text-gray-500 mr-2"></i>
            Đơn hàng gần đây
        </h3>
        <div class="space-y-3">
            @forelse($recentOrders as $order)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-shopping-bag text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">#{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-600">{{ optional($order->user->userProfile)->name ?? 'Khách hàng' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900">{{ number_format($order->grand_total) }}đ</p>
                    <p class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>Chưa có đơn hàng nào</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- System Info --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-info-circle text-gray-500 mr-2"></i>
            Thông tin hệ thống
        </h3>
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="text-gray-600">Phiên bản</span>
                <span class="font-medium">v1.0.0</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Laravel</span>
                <span class="font-medium">{{ app()->version() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">PHP</span>
                <span class="font-medium">{{ PHP_VERSION }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Múi giờ</span>
                <span class="font-medium">{{ config('app.timezone') }}</span>
            </div>
            
            {{-- Role-specific info --}}
            @if($user->employee_id)
            <hr class="my-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Mã NV</span>
                <span class="font-medium">{{ $user->employee_id }}</span>
            </div>
            @endif
            @if($user->hire_date)
            <div class="flex justify-between">
                <span class="text-gray-600">Ngày vào làm</span>
                <span class="font-medium">{{ \Carbon\Carbon::parse($user->hire_date)->format('d/m/Y') }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection