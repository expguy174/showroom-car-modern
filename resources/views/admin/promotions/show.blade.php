@extends('layouts.admin')

@section('title', 'Chi tiết khuyến mãi: ' . $promotion->name)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-16 w-16 mr-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                    @switch($promotion->type)
                        @case('percentage')
                            <i class="fas fa-percent text-white text-2xl"></i>
                            @break
                        @case('fixed_amount')
                            <i class="fas fa-dollar-sign text-white text-2xl"></i>
                            @break
                        @case('free_shipping')
                            <i class="fas fa-shipping-fast text-white text-2xl"></i>
                            @break
                        @case('brand_specific')
                            <i class="fas fa-tags text-white text-2xl"></i>
                            @break
                        @default
                            <i class="fas fa-gift text-white text-2xl"></i>
                    @endswitch
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $promotion->name }}</h1>
                    <p class="text-gray-600 mt-1">{{ $promotion->code }}</p>
                    <div class="flex items-center mt-2 space-x-2">
                        @if($promotion->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Ngừng hoạt động
                            </span>
                        @endif
                        
                        @switch($promotion->type)
                            @case('percentage')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-percent mr-1"></i>
                                    Giảm theo %
                                </span>
                                @break
                            @case('fixed_amount')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-dollar-sign mr-1"></i>
                                    Giảm cố định
                                </span>
                                @break
                            @case('free_shipping')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-shipping-fast mr-1"></i>
                                    Miễn phí ship
                                </span>
                                @break
                            @case('brand_specific')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    <i class="fas fa-tags mr-1"></i>
                                    Theo thương hiệu
                                </span>
                                @break
                        @endswitch
                        
                        @if($promotion->usage_limit)
                            @php
                                $usagePercent = $promotion->usage_limit > 0 ? ($promotion->usage_count / $promotion->usage_limit) * 100 : 0;
                            @endphp
                            @if($usagePercent >= 90)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Sắp hết lượt
                                </span>
                            @elseif($usagePercent >= 70)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>
                                    Còn ít lượt
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.promotions.edit', $promotion) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Chỉnh sửa
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Promotion Details --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Chi tiết khuyến mãi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-600 block mb-1">Loại khuyến mãi:</span>
                        <p class="font-medium">
                            @switch($promotion->type)
                                @case('percentage')
                                    Giảm theo phần trăm
                                    @break
                                @case('fixed_amount')
                                    Giảm số tiền cố định
                                    @break
                                @case('free_shipping')
                                    Miễn phí vận chuyển
                                    @break
                                @case('brand_specific')
                                    Giảm giá theo thương hiệu
                                    @break
                                @default
                                    {{ $promotion->type }}
                            @endswitch
                        </p>
                    </div>
                    
                    <div>
                        <span class="text-gray-600 block mb-1">Giá trị giảm:</span>
                        <p class="font-medium">
                            @if($promotion->type === 'percentage' || $promotion->type === 'brand_specific')
                                {{ number_format($promotion->discount_value, 0) }}%
                            @elseif($promotion->type === 'fixed_amount')
                                {{ number_format($promotion->discount_value, 0, ',', '.') }} VNĐ
                            @else
                                Miễn phí vận chuyển
                            @endif
                        </p>
                    </div>
                    
                    @if($promotion->min_order_amount)
                    <div>
                        <span class="text-gray-600 block mb-1">Đơn hàng tối thiểu:</span>
                        <p class="font-medium">{{ number_format($promotion->min_order_amount, 0, ',', '.') }} VNĐ</p>
                    </div>
                    @endif
                    
                    @if($promotion->max_discount_amount)
                    <div>
                        <span class="text-gray-600 block mb-1">Giảm tối đa:</span>
                        <p class="font-medium">{{ number_format($promotion->max_discount_amount, 0, ',', '.') }} VNĐ</p>
                    </div>
                    @endif
                </div>
                
                @if($promotion->description)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <span class="text-gray-600 block mb-2">Mô tả:</span>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($promotion->description)) !!}
                    </div>
                </div>
                @endif
            </div>

            {{-- Usage Statistics --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                    Thống kê sử dụng
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-blue-600">Đã sử dụng</p>
                                <p class="text-2xl font-bold text-blue-900">{{ number_format($promotion->usage_count ?? 0) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($promotion->usage_limit)
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-600">Còn lại</p>
                                <p class="text-2xl font-bold text-green-900">{{ number_format($promotion->usage_limit - ($promotion->usage_count ?? 0)) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-list text-gray-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-600">Tổng giới hạn</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($promotion->usage_limit) }}</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-purple-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-infinity text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-purple-600">Giới hạn</p>
                                <p class="text-lg font-bold text-purple-900">Không giới hạn</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                @if($promotion->usage_limit)
                <div class="mt-4">
                    <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                        <span>Tiến độ sử dụng</span>
                        <span>{{ number_format((($promotion->usage_count ?? 0) / $promotion->usage_limit) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min((($promotion->usage_count ?? 0) / $promotion->usage_limit) * 100, 100) }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar Information --}}
        <div class="space-y-6">
            {{-- Basic Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info text-blue-600 mr-2"></i>
                    Thông tin cơ bản
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Mã khuyến mãi:</span>
                        <span class="font-medium font-mono">{{ $promotion->code }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Loại:</span>
                        <span class="font-medium">
                            @switch($promotion->type)
                                @case('percentage')
                                    Giảm %
                                    @break
                                @case('fixed_amount')
                                    Giảm cố định
                                    @break
                                @case('free_shipping')
                                    Miễn phí ship
                                    @break
                                @case('brand_specific')
                                    Theo thương hiệu
                                    @break
                            @endswitch
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Trạng thái:</span>
                        @if($promotion->is_active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Hoạt động
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                Ngừng
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Ngày tạo:</span>
                        <span class="font-medium">{{ $promotion->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Cập nhật cuối:</span>
                        <span class="font-medium">{{ $promotion->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Time Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                    Thời gian hiệu lực
                </h3>
                <div class="space-y-3">
                    @if($promotion->start_date)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Bắt đầu:</span>
                        <span class="font-medium">{{ $promotion->start_date->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    
                    @if($promotion->end_date)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Kết thúc:</span>
                        <span class="font-medium">{{ $promotion->end_date->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif
                    
                    @if($promotion->start_date && $promotion->end_date)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Thời lượng:</span>
                        <span class="font-medium">{{ $promotion->start_date->diffInDays($promotion->end_date) }} ngày</span>
                    </div>
                    @endif
                    
                    @if(!$promotion->start_date && !$promotion->end_date)
                        <p class="text-gray-500 text-sm">Không giới hạn thời gian</p>
                    @endif
                </div>
            </div>

            {{-- Usage Limits --}}
            @if($promotion->usage_limit || $promotion->min_order_amount || $promotion->max_discount_amount)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cog text-blue-600 mr-2"></i>
                    Điều kiện & Giới hạn
                </h3>
                <div class="space-y-3">
                    @if($promotion->usage_limit)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Giới hạn sử dụng:</span>
                        <span class="font-medium">{{ number_format($promotion->usage_limit) }} lượt</span>
                    </div>
                    @endif
                    
                    @if($promotion->min_order_amount)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Đơn tối thiểu:</span>
                        <span class="font-medium">{{ number_format($promotion->min_order_amount, 0, ',', '.') }}đ</span>
                    </div>
                    @endif
                    
                    @if($promotion->max_discount_amount)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Giảm tối đa:</span>
                        <span class="font-medium">{{ number_format($promotion->max_discount_amount, 0, ',', '.') }}đ</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
