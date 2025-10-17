@extends('layouts.admin')

@section('title', 'Danh sách đơn hàng')

@section('content')
{{-- Flash Messages --}}
<x-admin.flash-messages />

<div class="space-y-6">
    {{-- Page Header --}}
    <x-admin.page-header 
        title="Danh sách đơn hàng" 
        description="Quản lý tất cả đơn hàng trong hệ thống"
        icon="fas fa-shopping-cart">
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>
                Xuất Excel
            </button>
        </div>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng đơn hàng"
            :value="$totalOrders"
            icon="fas fa-shopping-cart"
            color="blue"
            description="Tất cả đơn hàng"
            dataStat="total" />
        
        <x-admin.stats-card 
            title="Chờ xử lý"
            :value="$pendingOrders"
            icon="fas fa-clock"
            color="yellow"
            description="Cần xử lý ngay"
            dataStat="pending" />
        
        <x-admin.stats-card 
            title="Đã hủy"
            :value="$cancelledOrders"
            icon="fas fa-times-circle"
            color="red"
            description="Đơn bị hủy"
            dataStat="cancelled" />
        
        <x-admin.stats-card 
            title="Hoàn thành"
            :value="$deliveredOrders"
            icon="fas fa-check-circle"
            color="green"
            description="Đã giao thành công"
            dataStat="delivered" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" id="filterForm" data-base-url="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end">
            {{-- Search Input --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search" 
                    :value="request('search', '')" 
                    placeholder="Tìm kiếm đơn hàng, khách hàng..."
                    callbackName="handleSearch"
                    :debounceTime="500"
                    size="small"
                    :showIcon="true"
                    :showClearButton="true" />
            </div>
            
            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <x-admin.custom-dropdown 
                    name="status"
                    :options="[
                        ['value' => 'pending', 'label' => 'Chờ xử lý'],
                        ['value' => 'confirmed', 'label' => 'Đã xác nhận'],
                        ['value' => 'shipping', 'label' => 'Đang giao'],
                        ['value' => 'delivered', 'label' => 'Đã giao'],
                        ['value' => 'cancelled', 'label' => 'Đã hủy']
                    ]"
                    :selected="request('status', '')"
                    placeholder="Tất cả"
                    option-value="value"
                    option-text="label"
                    :searchable="false"
                    onchange="autoSubmitForm"
                    width="w-full" />
            </div>
            
            {{-- Payment Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Thanh toán</label>
                <x-admin.custom-dropdown 
                    name="payment_status"
                    :options="[
                        ['value' => 'pending', 'label' => 'Chờ thanh toán'],
                        ['value' => 'processing', 'label' => 'Đang xử lý'],
                        ['value' => 'partial', 'label' => 'Thanh toán một phần'],
                        ['value' => 'completed', 'label' => 'Đã thanh toán'],
                        ['value' => 'refunded', 'label' => 'Đã hoàn tiền'],
                        ['value' => 'failed', 'label' => 'Thất bại'],
                        ['value' => 'cancelled', 'label' => 'Đã hủy']
                    ]"
                    :selected="request('payment_status', '')"
                    placeholder="Tất cả"
                    option-value="value"
                    option-text="label"
                    :searchable="false"
                    onchange="autoSubmitForm"
                    width="w-full" />
            </div>
            
            {{-- Reset Button --}}
            <div>
                <x-admin.reset-button 
                    form-id="#filterForm" 
                    callback="handleFormReset" />
            </div>
        </form>
        
        {{-- Active Filters Display --}}
        @if(request('search') || request('status') || request('payment_status'))
        <div class="mt-3 flex items-center gap-2 flex-wrap">
            <span class="text-sm text-gray-600">Bộ lọc đang áp dụng:</span>
            @if(request('search'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <i class="fas fa-search mr-1"></i>
                {{ request('search') }}
                <button type="button" onclick="removeFilter('search')" class="ml-2 hover:text-blue-900">
                    <i class="fas fa-times"></i>
                </button>
            </span>
            @endif
            @if(request('status'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                {{ ['pending' => 'Chờ xử lý', 'confirmed' => 'Đã xác nhận', 'shipping' => 'Đang giao', 'delivered' => 'Đã giao', 'cancelled' => 'Đã hủy'][request('status')] ?? request('status') }}
                <button type="button" onclick="removeFilter('status')" class="ml-2 hover:text-purple-900">
                    <i class="fas fa-times"></i>
                </button>
            </span>
            @endif
            @if(request('payment_status'))
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                {{ ['pending' => 'Chờ thanh toán', 'processing' => 'Đang xử lý', 'partial' => 'Thanh toán một phần', 'completed' => 'Đã thanh toán', 'refunded' => 'Đã hoàn tiền', 'failed' => 'Thất bại', 'cancelled' => 'Đã hủy'][request('payment_status')] ?? request('payment_status') }}
                <button type="button" onclick="removeFilter('payment_status')" class="ml-2 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </span>
            @endif
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-3 py-1 text-xs font-medium text-red-600 hover:text-red-800 transition-colors">
                <i class="fas fa-times-circle mr-1"></i>
                Xóa tất cả
            </a>
        </div>
        @endif
    </div>

    {{-- AJAX Table --}}
    <x-admin.ajax-table 
        table-id="orders-content" 
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.orders.index') }}"
        callback-name="loadTableData">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @include('admin.orders.partials.table', ['orders' => $orders])
        </div>
    </x-admin.ajax-table>
</div>

@push('scripts')
<script>
// Search input callback
window.handleSearch = function(searchTerm, inputElement) {
    const form = document.getElementById('filterForm');
    if (form && window.loadTableData) {
        const formData = new FormData(form);
        const url = '{{ route("admin.orders.index") }}?' + new URLSearchParams(formData).toString();
        window.loadTableData(url);
    }
};

// Dropdown callback
window.autoSubmitForm = function() {
    const form = document.getElementById('filterForm');
    if (form && window.loadTableData) {
        const formData = new FormData(form);
        const url = '{{ route("admin.orders.index") }}?' + new URLSearchParams(formData).toString();
        window.loadTableData(url);
    }
};

// Reset button callback
window.handleFormReset = function() {
    if (window.loadTableData) {
        window.loadTableData('{{ route("admin.orders.index") }}');
    }
};
</script>
@endpush

@endsection
