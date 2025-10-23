@extends('layouts.admin')

@section('title', 'Chi tiết người dùng: ' . ($user->userProfile->name ?? $user->email))

@section('content')
<div class="space-y-6 px-2 sm:px-0">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
            <div class="flex items-start space-x-4">
                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    @if($user->userProfile && $user->userProfile->avatar_path)
                        <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-200" 
                             src="{{ Storage::url($user->userProfile->avatar_path) }}" 
                             alt="{{ $user->userProfile->name }}">
                    @else
                        <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">
                                {{ strtoupper(mb_substr($user->userProfile->name ?? $user->email, 0, 2, 'UTF-8')) }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $user->userProfile->name ?? 'Chưa có tên' }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                    <div class="flex items-center mt-3 space-x-2 flex-wrap gap-2">
                        {{-- Status Badge --}}
                        @if($user->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>Ngưng hoạt động
                            </span>
                        @endif
                        
                        {{-- Email Verified Badge --}}
                        @if($user->email_verified || $user->email_verified_at)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-check-circle mr-1"></i>Đã xác thực
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-exclamation-circle mr-1"></i>Chưa xác thực
                            </span>
                        @endif
                        
                        {{-- Role Badge --}}
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->getRoleColor() }}">
                            {{ $user->getRoleLabel() }}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Information --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Account Info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user-lock text-blue-600 mr-2"></i>
                    Thông tin tài khoản
                </h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Vai trò</dt>
                        <dd class="mt-1">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $user->getRoleColor() }}">
                                {{ $user->getRoleLabel() }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Trạng thái</dt>
                        <dd class="mt-1">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Hoạt động
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-ban mr-1"></i>Tạm khóa
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lần đăng nhập cuối</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Chưa đăng nhập' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Employee Info (if applicable) --}}
            @if($user->role !== 'user' && ($user->employee_id || $user->department || $user->position))
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-id-badge text-blue-600 mr-2"></i>
                    Thông tin nhân viên
                </h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($user->employee_id)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Mã nhân viên</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $user->employee_id }}</dd>
                    </div>
                    @endif
                    @if($user->department)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phòng ban</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->department }}</dd>
                    </div>
                    @endif
                    @if($user->position)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Chức vụ</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->position }}</dd>
                    </div>
                    @endif
                    @if($user->hire_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ngày tuyển dụng</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->hire_date->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                    @if($user->userProfile && $user->userProfile->employee_salary)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Lương</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold text-green-600">
                            {{ number_format($user->userProfile->employee_salary, 0, ',', '.') }} VND/tháng
                        </dd>
                    </div>
                    @endif
                    @if($user->userProfile && $user->userProfile->employee_skills)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Kỹ năng chuyên môn</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->userProfile->employee_skills }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            {{-- Profile Info --}}
            @if($user->userProfile)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-id-card text-blue-600 mr-2"></i>
                    Hồ sơ cá nhân
                </h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Họ và tên</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->userProfile->name }}</dd>
                    </div>
                    @if($user->userProfile->phone)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Số điện thoại</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->userProfile->phone }}</dd>
                    </div>
                    @endif
                    @if($user->userProfile->birth_date)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ngày sinh</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->userProfile->birth_date->format('d/m/Y') }}</dd>
                    </div>
                    @endif
                    @if($user->userProfile->gender)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Giới tính</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @switch($user->userProfile->gender)
                                @case('male') Nam @break
                                @case('female') Nữ @break
                                @default Khác
                            @endswitch
                        </dd>
                    </div>
                    @endif
                    @if($user->userProfile->driver_license_number)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Số bằng lái</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->userProfile->driver_license_number }}</dd>
                    </div>
                    @endif
                    @if($user->userProfile->driver_license_class)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Hạng bằng lái</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->userProfile->driver_license_class }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            {{-- Customer Preferences (for customers only) --}}
            @if($user->role === 'user' && $user->userProfile && ($user->userProfile->budget_min || $user->userProfile->budget_max || $user->userProfile->purchase_purpose || $user->userProfile->is_vip))
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-heart text-red-600 mr-2"></i>
                    Sở thích & Ngân sách
                </h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($user->userProfile->budget_min)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ngân sách tối thiểu</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold text-green-600">
                            {{ number_format($user->userProfile->budget_min, 0, ',', '.') }} VND
                        </dd>
                    </div>
                    @endif
                    @if($user->userProfile->budget_max)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Ngân sách tối đa</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold text-green-600">
                            {{ number_format($user->userProfile->budget_max, 0, ',', '.') }} VND
                        </dd>
                    </div>
                    @endif
                    @if($user->userProfile->purchase_purpose)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Mục đích mua xe</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @switch($user->userProfile->purchase_purpose)
                                @case('personal') Cá nhân @break
                                @case('business') Kinh doanh @break
                                @case('family') Gia đình @break
                                @case('investment') Đầu tư @break
                                @default {{ $user->userProfile->purchase_purpose }}
                            @endswitch
                        </dd>
                    </div>
                    @endif
                    @if($user->userProfile->customer_type)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Loại khách hàng</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($user->userProfile->customer_type === 'vip') bg-yellow-100 text-yellow-800
                                @elseif($user->userProfile->customer_type === 'returning') bg-blue-100 text-blue-800
                                @elseif($user->userProfile->customer_type === 'prospect') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @switch($user->userProfile->customer_type)
                                    @case('new') Mới @break
                                    @case('returning') Quay lại @break
                                    @case('vip') VIP @break
                                    @case('prospect') Tiềm năng @break
                                    @default {{ $user->userProfile->customer_type }}
                                @endswitch
                            </span>
                        </dd>
                    </div>
                    @endif
                    @if($user->userProfile->is_vip)
                    <div class="md:col-span-2">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-300">
                            <i class="fas fa-crown mr-2"></i>
                            Khách hàng VIP
                        </span>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            {{-- Addresses --}}
            @if($user->addresses && $user->addresses->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                    Địa chỉ
                </h2>
                <div class="space-y-4">
                    @foreach($user->addresses as $address)
                    <div class="border border-gray-200 rounded-lg p-4 {{ $address->is_default ? 'bg-blue-50 border-blue-300' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-xs font-medium px-2 py-1 rounded {{ $address->is_default ? 'bg-blue-200 text-blue-800' : 'bg-gray-200 text-gray-700' }}">
                                        @switch($address->type)
                                            @case('home') Nhà riêng @break
                                            @case('work') Cơ quan @break
                                            @case('billing') Thanh toán @break
                                            @case('shipping') Giao hàng @break
                                            @default Khác
                                        @endswitch
                                    </span>
                                    @if($address->is_default)
                                    <span class="text-xs font-medium px-2 py-1 rounded bg-green-200 text-green-800">
                                        Mặc định
                                    </span>
                                    @endif
                                </div>
                                <p class="text-sm font-medium text-gray-900">{{ $address->contact_name }}</p>
                                @if($address->phone)
                                <p class="text-sm text-gray-600"><i class="fas fa-phone text-gray-400 mr-1"></i>{{ $address->phone }}</p>
                                @endif
                                <p class="text-sm text-gray-600 mt-1">{{ $address->address }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $address->city }}@if($address->state), {{ $address->state }}@endif
                                    @if($address->postal_code) - {{ $address->postal_code }}@endif
                                </p>
                                @if($address->notes)
                                <p class="text-xs text-gray-500 mt-2 italic">
                                    <i class="fas fa-sticky-note mr-1"></i>{{ $address->notes }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Quick Stats --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thống kê</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-shopping-cart text-blue-600 mr-2"></i>
                            Đơn hàng
                        </span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->orders()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-car text-green-600 mr-2"></i>
                            Lái thử
                        </span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->testDrives()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">
                            <i class="fas fa-wrench text-orange-600 mr-2"></i>
                            Dịch vụ
                        </span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->serviceAppointments()->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thời gian</h3>
                <div class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Ngày tạo</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Cập nhật</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($user->last_login_at)
                    <div>
                        <dt class="text-xs font-medium text-gray-500">Đăng nhập cuối</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->last_login_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
