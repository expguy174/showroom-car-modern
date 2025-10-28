@extends('layouts.admin')

@section('title', 'Hiệu suất nhân viên')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">
            <i class="fas fa-chart-line text-blue-600 mr-2"></i>
            Hiệu suất nhân viên
        </h1>
        <p class="text-gray-600 mt-1">Dữ liệu tracking từ order logs - Cập nhật theo thời gian thực</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.analytics.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
    </div>
</div>

{{-- Performance Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-2">Đơn hàng xử lý</p>
                <p class="text-3xl font-bold text-gray-900">{{ $staffStats['total_orders_handled'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-2">Tổng doanh thu</p>
                <p class="text-3xl font-bold text-green-600">{{ format_currency_short($staffStats['total_revenue']) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-2">Nhân viên</p>
                <p class="text-3xl font-bold text-purple-600">{{ $staffStats['total'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Staff List by Role --}}
<div class="space-y-6 mb-8">
    @forelse($staffByRole as $roleKey => $roleGroup)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                @php
                    $roleIcon = match($roleKey) {
                        'admin' => 'fa-user-shield',
                        'sales_person' => 'fa-handshake',
                        'technician' => 'fa-wrench',
                        'manager' => 'fa-user-tie',
                        default => 'fa-user'
                    };
                    $roleColor = match($roleKey) {
                        'admin' => 'text-purple-600',
                        'sales_person' => 'text-green-600',
                        'technician' => 'text-orange-600',
                        'manager' => 'text-blue-600',
                        default => 'text-gray-600'
                    };
                @endphp
                <i class="fas {{ $roleIcon }} {{ $roleColor }} text-xl mr-3"></i>
                <h3 class="text-lg font-semibold text-gray-900">{{ $roleGroup['role_name'] }}</h3>
                <span class="ml-3 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                    {{ $roleGroup['count'] }} người
                </span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Nhân viên</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-900">Đơn hàng</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Doanh thu</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-900">Tỷ lệ hoàn thành</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($roleGroup['staff'] as $staff)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                {{-- Avatar --}}
                                <div class="flex-shrink-0">
                                    @php
                                        $currentStaff = $allStaff->firstWhere('id', $staff->id);
                                    @endphp
                                    @if($currentStaff && $currentStaff->userProfile && $currentStaff->userProfile->avatar_path)
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-gray-200" 
                                             src="{{ Storage::url($currentStaff->userProfile->avatar_path) }}" 
                                             alt="{{ $currentStaff->userProfile->name ?? $currentStaff->email }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm">
                                                {{ strtoupper(mb_substr($staff->name ?? 'ST', 0, 2, 'UTF-8')) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Staff Details --}}
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 truncate">
                                        {{ $staff->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-id-badge text-gray-400 mr-1"></i>
                                        Mã: {{ $staff->employee_id }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $staff->orders_handled }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-gray-900">
                            {{ $staff->total_revenue > 0 ? format_currency_short($staff->total_revenue) : '-' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $rate = $staff->completion_rate;
                                $color = $rate >= 80 ? 'text-green-600 bg-green-100' : ($rate >= 50 ? 'text-yellow-600 bg-yellow-100' : 'text-red-600 bg-red-100');
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ format_percentage($rate) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12">
        <div class="text-center">
            <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500 text-lg mb-2">Chưa có nhân viên nào</p>
            <p class="text-gray-400 text-sm">Vui lòng thêm nhân viên vào hệ thống</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
