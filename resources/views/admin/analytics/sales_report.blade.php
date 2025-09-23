@extends('layouts.admin')

@section('title', 'Báo cáo bán hàng')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Báo cáo bán hàng</h1>
        <p class="text-gray-600 mt-1">Phân tích chi tiết doanh thu và hiệu quả bán hàng</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.analytics.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        <a href="{{ route('admin.analytics.export-report', ['type' => 'sales']) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download mr-2"></i>
            Xuất Excel
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng đơn hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $salesSummary->total_orders ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Tổng doanh thu</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format(($salesSummary->total_revenue ?? 0) / 1000000, 1) }}M</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Giá trị trung bình</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format(($salesSummary->average_order_value ?? 0) / 1000000, 1) }}M</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Khách hàng</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $salesSummary->unique_customers ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

{{-- Charts Section --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    {{-- Daily Sales Trend --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Xu hướng theo ngày</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Ngày</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Doanh thu</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Đơn hàng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($dailySales ?? [] as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4 text-gray-900">{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-right font-medium text-gray-900">{{ number_format($sale->daily_revenue) }} VNĐ</td>
                        <td class="py-3 px-4 text-right text-gray-600">{{ $sale->daily_orders }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-gray-500">
                            <i class="fas fa-chart-line text-gray-300 text-3xl mb-3 block"></i>
                            Không có dữ liệu bán hàng
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Hiệu quả sản phẩm</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-medium text-gray-900">Sản phẩm</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">SL bán</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-900">Doanh thu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($productPerformance ?? [] as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <div>
                                <p class="font-medium text-gray-900">{{ $product->brand }} {{ $product->model }}</p>
                                <p class="text-sm text-gray-500">{{ $product->variant }}</p>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-gray-900">{{ $product->units_sold }}</td>
                        <td class="py-3 px-4 text-right text-gray-600">{{ number_format($product->revenue) }} VNĐ</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-gray-500">
                            <i class="fas fa-car text-gray-300 text-3xl mb-3 block"></i>
                            Chưa có dữ liệu sản phẩm
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
