@extends('layouts.app')

@section('title', 'Hồ sơ khách hàng')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-id-badge text-white text-xl"></i>
                    </div>
                <div>
                        <h1 class="text-2xl font-bold text-gray-900">Hồ sơ khách hàng</h1>
                        <p class="text-gray-600">Thông tin chuyên sâu, sở thích mua xe và lịch sử giao dịch</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('user.profile.edit') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-user-cog mr-2"></i>
                        Tài khoản
                    </a>
                    <a href="{{ route('home') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-home mr-2"></i>
                        Trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Action Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                <div class="flex items-center gap-4 mb-4 sm:mb-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-emerald-600 text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $customerProfile->name }}</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $customerProfile->is_vip ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas fa-{{ $customerProfile->is_vip ? 'crown' : 'user' }} mr-1"></i>
                            {{ $customerProfile->is_vip ? 'VIP' : 'Thường' }}
                        </span>
                            <span class="text-sm text-gray-500">• Thành viên từ {{ $customerProfile->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('user.customer-profiles.edit') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                        <i class="fas fa-edit"></i>
                        Chỉnh sửa hồ sơ
                    </a>
                </div>
                    </div>
                    
            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">


                    <!-- Professional Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gradient-to-r from-gray-50 to-emerald-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-briefcase text-emerald-600"></i>
                                Thông tin nghề nghiệp
                            </h3>
                        </div>
                        <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($customerProfile->occupation)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                        <i class="fas fa-briefcase text-sm"></i>
                            </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs text-gray-500">Nghề nghiệp</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $customerProfile->occupation }}</div>
                            </div>
                            </div>
                                @endif
                                
                                @if($customerProfile->company_name)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <i class="fas fa-building text-sm"></i>
                            </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs text-gray-500">Công ty</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $customerProfile->company_name }}</div>
                            </div>
                        </div>
                                @endif
                                
                                @if($customerProfile->monthly_income)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 border border-green-100">
                                    <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                        <i class="fas fa-money-bill-wave text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs text-gray-500">Thu nhập hàng tháng</div>
                                        <div class="text-sm font-medium text-gray-900">{{ number_format($customerProfile->monthly_income) }} VNĐ</div>
                                    </div>
                            </div>
                                @endif
                                
                                @if($customerProfile->preferredShowroom)
                                <div class="flex items-center gap-3 p-3 rounded-lg bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-100">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                                        <i class="fas fa-store text-sm"></i>
                            </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs text-gray-500">Showroom ưa thích</div>
                                        <div class="text-sm font-medium text-gray-900">{{ $customerProfile->preferredShowroom->name }}</div>
                            </div>
                            </div>
                                @endif
                        </div>
                    </div>
                </div>

                <!-- Driver License Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-id-card text-emerald-600"></i>
                                Thông tin bằng lái xe
                            </h3>
                        </div>
                        <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                                    @if($customerProfile->driver_license_number)
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                            <i class="fas fa-id-card text-sm"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs text-gray-500">Số bằng lái</div>
                                            <div class="text-sm font-medium text-gray-900">{{ $customerProfile->driver_license_number }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($customerProfile->driver_license_issue_date)
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                        <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                            <i class="fas fa-calendar-plus text-sm"></i>
                            </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs text-gray-500">Ngày cấp</div>
                                            <div class="text-sm font-medium text-gray-900">{{ $customerProfile->driver_license_issue_date->format('d/m/Y') }}</div>
                            </div>
                        </div>
                                    @endif
                                </div>
                                
                        <div class="space-y-4">
                                    @if($customerProfile->driver_license_expiry_date)
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                            <i class="fas fa-calendar-times text-sm"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs text-gray-500">Ngày hết hạn</div>
                                            <div class="text-sm font-medium text-gray-900">{{ $customerProfile->driver_license_expiry_date->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($customerProfile->driving_experience_years)
                                    <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50">
                                        <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                                            <i class="fas fa-car text-sm"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs text-gray-500">Kinh nghiệm lái xe</div>
                                            <div class="text-sm font-medium text-gray-900">{{ $customerProfile->driving_experience_years }} năm</div>
                                        </div>
                            </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-shopping-bag text-emerald-600"></i>
                                    Đơn hàng gần đây
                                </h3>
                                <a href="{{ route('user.customer-profiles.orders') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                            Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                        </div>
                        <div class="p-6">
                    @if($orders->count() > 0)
                        <div class="space-y-4">
                                    @foreach($orders->take(3) as $order)
                                    <div class="p-4 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-sm font-semibold text-gray-800">
                                                #{{ $order->order_number ?? $order->id }}
                                            </div>
                                            <div class="text-lg font-bold text-emerald-600">
                                                {{ number_format($order->grand_total ?? $order->total_price) }} đ
                                            </div>
                                    </div>
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs">
                                                    {{ $order->status_display ?? ucfirst($order->status) }}
                                                </span>
                                                <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs">
                                                    {{ $order->payment_status_display ?? 'Chưa xác định' }}
                                        </span>
                                    </div>
                                </div>
                                        <div class="text-xs text-gray-600 flex items-center gap-1">
                                            <i class="fas fa-box text-gray-400"></i>
                                            <span>{{ $order->items->count() }} sản phẩm</span>
                                        </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                        <i class="fas fa-shopping-bag text-gray-400 text-xl"></i>
                                    </div>
                                    <div class="text-gray-500 mb-4">Bạn chưa có đơn hàng nào</div>
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                                        <i class="fas fa-car"></i>
                                        Mua sắm ngay
                                    </a>
                        </div>
                    @endif
                        </div>
                </div>

                <!-- Recent Test Drives -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                    <i class="fas fa-car text-blue-600"></i>
                                    Lái thử gần đây
                                </h3>
                                <a href="{{ route('user.customer-profiles.test-drives') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                        </div>
                        <div class="p-6">
                    @if($testDrives->count() > 0)
                        <div class="space-y-4">
                                    @foreach($testDrives->take(3) as $testDrive)
                                    <div class="p-4 rounded-xl border border-gray-200 bg-gray-50 hover:bg-gray-100 transition-colors">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-sm font-semibold text-gray-800">
                                                {{ $testDrive->carVariant->carModel->carBrand->name ?? 'N/A' }} {{ $testDrive->carVariant->carModel->name ?? 'N/A' }}
                                    </div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ $testDrive->status === 'completed' ? 'bg-green-100 text-green-700' : 
                                                   ($testDrive->status === 'scheduled' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                            {{ ucfirst($testDrive->status) }}
                                        </span>
                                    </div>
                                        <div class="text-xs text-gray-500 mb-2">
                                            @if($testDrive->preferred_date)
                                                <div class="flex items-center gap-1 mb-1">
                                                    <i class="fas fa-calendar text-gray-400"></i>
                                                    <span>{{ $testDrive->preferred_date->format('d/m/Y') }} {{ $testDrive->preferred_time }}</span>
                                                </div>
                                            @endif
                                            @if($testDrive->showroom_name)
                                                <div class="flex items-center gap-1">
                                                    <i class="fas fa-store text-gray-400"></i>
                                                    <span>{{ $testDrive->showroom_name }}</span>
                                                </div>
                                            @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                        <i class="fas fa-car text-gray-400 text-xl"></i>
                                    </div>
                                    <div class="text-gray-500 mb-4">Bạn chưa đặt lịch lái thử nào</div>
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-car"></i>
                                        Đặt lịch lái thử
                                    </a>
                        </div>
                    @endif
                        </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Preferences Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-heart text-pink-600"></i>
                                Sở thích
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($customerProfile->preferred_car_types)
                        <div>
                                <div class="text-xs text-gray-500 mb-2">Loại xe ưa thích</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($customerProfile->preferred_car_types as $type)
                                        <span class="inline-block bg-pink-100 text-pink-700 text-xs px-3 py-1 rounded-full font-medium">{{ $type }}</span>
                                    @endforeach
                                </div>
                            </div>
                                @endif
                            
                            @if($customerProfile->preferred_brands)
                        <div>
                                <div class="text-xs text-gray-500 mb-2">Hãng xe ưa thích</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($customerProfile->preferred_brands as $brand)
                                        <span class="inline-block bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">{{ $brand }}</span>
                                    @endforeach
                                </div>
                            </div>
                                @endif
                            
                            @if($customerProfile->budget_min && $customerProfile->budget_max)
                        <div>
                                <div class="text-xs text-gray-500 mb-2">Khoảng giá</div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ number_format($customerProfile->budget_min) }} - {{ number_format($customerProfile->budget_max) }} VNĐ
                                </div>
                            </div>
                                @endif
                            
                        <a href="{{ route('user.customer-profiles.preferences') }}" 
                               class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                <i class="fas fa-edit"></i>
                                Cập nhật sở thích
                        </a>
                    </div>
                </div>

                <!-- Marketing Preferences -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-bullhorn text-purple-600"></i>
                                Tùy chọn marketing
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <i class="fas fa-rocket text-orange-600"></i>
                                Thao tác nhanh
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('products.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors font-medium">
                                <i class="fas fa-car"></i>
                                Xem xe hơi
                            </a>
                            <a href="{{ route('accessories.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors font-medium">
                                <i class="fas fa-tools"></i>
                                Xem phụ kiện
                            </a>
                            <a href="{{ route('finance.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-purple-600 text-white hover:bg-purple-700 transition-colors font-medium">
                                <i class="fas fa-calculator"></i>
                                Tài chính
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

