@extends('layouts.admin')

@section('title', 'Phân tích khách hàng')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Phân tích khách hàng</h1>
        <p class="text-gray-600 mt-1">Hiểu rõ hành vi và giá trị của khách hàng</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.analytics.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        <a href="{{ route('admin.analytics.export-report', ['type' => 'customers']) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Xuất Excel
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng khách hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $customerDemographics->total_customers ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Khách hàng mới (tháng)</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $customerDemographics->new_customers_this_month ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-plus text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tỷ lệ giữ chân</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($retentionRate ?? 0, 1) }}%</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-heart text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Customer Lifetime Value --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Giá trị vòng đời khách hàng (Top 20)</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Khách hàng</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-900">Đơn hàng</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Tổng chi tiêu</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Giá trị TB</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($customerLifetimeValue ?? [] as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-semibold text-sm mr-3">
                                    {{ substr($customer->user_id, -2) }}
                                </div>
                                <span class="text-gray-900">Khách hàng #{{ $customer->user_id }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $customer->total_orders }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-gray-900">{{ number_format($customer->total_spent) }} VNĐ</td>
                        <td class="py-3 px-4 text-right text-gray-600">{{ number_format($customer->average_order_value) }} VNĐ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-500">
                            <i class="fas fa-users text-gray-300 text-3xl mb-3 block"></i>
                            Chưa có dữ liệu khách hàng
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Customer Engagement --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Mức độ tương tác</h3>
        </div>
        
        <div class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-car-side text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Lái thử</p>
                        <p class="text-sm text-gray-500">Tổng số lượt lái thử</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">{{ $engagementMetrics['test_drives'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">lượt</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Lịch hẹn dịch vụ</p>
                        <p class="text-sm text-gray-500">Bảo dưỡng và sửa chữa</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-green-600">{{ $engagementMetrics['service_appointments'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">lịch hẹn</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-star text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Đánh giá</p>
                        <p class="text-sm text-gray-500">Reviews và feedback</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-purple-600">{{ $engagementMetrics['reviews'] ?? 0 }}</p>
                    <p class="text-xs text-gray-500">đánh giá</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
