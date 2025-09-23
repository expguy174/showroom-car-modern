@extends('layouts.admin')

@section('title', 'Hiệu suất nhân viên')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Hiệu suất nhân viên</h1>
        <p class="text-gray-600 mt-1">Theo dõi và đánh giá hiệu quả làm việc của đội ngũ</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.analytics.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
    </div>
</div>

{{-- Performance Overview --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Sales Performance --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Hiệu suất bán hàng</h3>
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Theo nhân viên bán hàng
            </div>
        </div>
        
        @if($salesStaffPerformance->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Nhân viên</th>
                            <th class="text-center py-3 px-4 font-medium text-gray-900">Đơn hàng</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-900">Doanh thu</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-900">Trung bình</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($salesStaffPerformance as $staff)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-semibold text-sm mr-3">
                                        {{ substr($staff->sales_staff_id, -2) }}
                                    </div>
                                    <span class="text-gray-900">NV #{{ $staff->sales_staff_id }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $staff->orders_handled }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-medium text-gray-900">{{ number_format($staff->total_sales) }} VNĐ</td>
                            <td class="py-3 px-4 text-right text-gray-600">{{ number_format($staff->average_sale) }} VNĐ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-user-tie text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500 text-lg mb-2">Chưa có dữ liệu hiệu suất bán hàng</p>
                <p class="text-gray-400 text-sm">Dữ liệu sẽ xuất hiện khi có đơn hàng được xử lý bởi nhân viên</p>
            </div>
        @endif
    </div>

    {{-- Test Drive Performance --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Hiệu suất lái thử</h3>
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Theo showroom
            </div>
        </div>
        
        @if($testDrivePerformance->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-900">Showroom</th>
                            <th class="text-center py-3 px-4 font-medium text-gray-900">Lái thử</th>
                            <th class="text-center py-3 px-4 font-medium text-gray-900">Hoàn thành</th>
                            <th class="text-right py-3 px-4 font-medium text-gray-900">Tỷ lệ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($testDrivePerformance as $performance)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm mr-3">
                                        {{ substr($performance->showroom_id, -2) }}
                                    </div>
                                    <span class="text-gray-900">Showroom #{{ $performance->showroom_id }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $performance->test_drives_handled }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $performance->conversions }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end">
                                    @php
                                        $rate = $performance->conversion_rate;
                                        $color = $rate >= 70 ? 'text-green-600' : ($rate >= 50 ? 'text-yellow-600' : 'text-red-600');
                                        $bgColor = $rate >= 70 ? 'bg-green-100' : ($rate >= 50 ? 'bg-yellow-100' : 'bg-red-100');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bgColor }} {{ $color }}">
                                        {{ number_format($rate, 1) }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-car-side text-gray-300 text-4xl mb-4"></i>
                <p class="text-gray-500 text-lg mb-2">Chưa có dữ liệu lái thử</p>
                <p class="text-gray-400 text-sm">Dữ liệu sẽ xuất hiện khi có lịch lái thử được xử lý</p>
            </div>
        @endif
    </div>
</div>

{{-- Performance Insights --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Thông tin hiệu suất</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-chart-line text-blue-600 text-xl"></i>
            </div>
            <h4 class="font-medium text-gray-900 mb-2">Theo dõi hiệu suất</h4>
            <p class="text-sm text-gray-600">Dữ liệu được cập nhật theo thời gian thực để đánh giá hiệu quả làm việc</p>
        </div>
        
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-target text-green-600 text-xl"></i>
            </div>
            <h4 class="font-medium text-gray-900 mb-2">Mục tiêu KPI</h4>
            <p class="text-sm text-gray-600">Đặt mục tiêu và theo dõi tiến độ đạt được của từng nhân viên</p>
        </div>
        
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-award text-purple-600 text-xl"></i>
            </div>
            <h4 class="font-medium text-gray-900 mb-2">Đánh giá & Thưởng</h4>
            <p class="text-sm text-gray-600">Hệ thống đánh giá công bằng dựa trên dữ liệu hiệu suất thực tế</p>
        </div>
    </div>
</div>
@endsection
