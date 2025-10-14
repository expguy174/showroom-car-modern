@extends('layouts.admin')

@section('title', 'Quản lý lịch trả góp')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-dismiss="5000" />

<div class="space-y-3 sm:space-y-4 lg:space-y-6 px-2 sm:px-0">
    {{-- Header --}}
    <x-admin.page-header 
        title="Quản lý lịch trả góp"
        description="Quản lý các kỳ thanh toán trả góp của khách hàng"
        icon="fas fa-calendar-check">
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng đơn hàng"
            :value="$stats['total']"
            icon="fas fa-file-invoice"
            color="blue"
            description="Đơn có trả góp"
            dataStat="total" />
            
        <x-admin.stats-card 
            title="Đang trả góp"
            :value="$stats['pending']"
            icon="fas fa-clock"
            color="yellow"
            :description="number_format($stats['total_pending_amount']) . ' đ'"
            dataStat="pending" />
            
        <x-admin.stats-card 
            title="Hoàn thành"
            :value="$stats['paid']"
            icon="fas fa-check-circle"
            color="green"
            :description="number_format($stats['total_paid_amount']) . ' đ'"
            dataStat="paid" />
            
        <x-admin.stats-card 
            title="Có quá hạn"
            :value="$stats['overdue']"
            icon="fas fa-exclamation-triangle"
            color="red"
            :description="number_format($stats['total_overdue_amount']) . ' đ'"
            dataStat="overdue" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_auto_auto] gap-4 items-end"
              data-base-url="{{ route('admin.installments.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Mã đơn, tên KH, SĐT..."
                    :value="request('search')"
                    callbackName="handleSearch"
                    :debounceTime="500"
                    size="small"
                    :showIcon="true"
                    :showClearButton="true" />
            </div>
            
            {{-- Finance Option --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gói vay</label>
                <x-admin.custom-dropdown 
                    name="finance_option_id"
                    :options="$financeOptions"
                    placeholder="Tất cả"
                    optionValue="id"
                    optionText="name"
                    optionSubtext="bank_name"
                    :selected="request('finance_option_id')"
                    onchange="loadInstallmentsFromDropdown"
                    :maxVisible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadInstallments" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="installments-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.installments.index') }}"
        callback-name="loadInstallments"
        after-load-callback="initializeEventListeners">
        @include('admin.installments.partials.table', ['orders' => $orders])
    </x-admin.ajax-table>
</div>

@push('scripts')
<script>
// Initialize event listeners
function initializeEventListeners() {
    // No additional listeners needed
}

// Dropdown callback
window.loadInstallmentsFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadInstallments) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.installments.index") }}?' + new URLSearchParams(formData).toString();
        window.loadInstallments(url);
    }
};

// Search callback
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadInstallments) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.installments.index") }}?' + new URLSearchParams(formData).toString();
        window.loadInstallments(url);
    }
};

// Update stats from server
window.updateStatsFromServer = function(stats) {
    const totalCard = document.querySelector('[data-stat="total"] .text-2xl');
    const pendingCard = document.querySelector('[data-stat="pending"] .text-2xl');
    const paidCard = document.querySelector('[data-stat="paid"] .text-2xl');
    const overdueCard = document.querySelector('[data-stat="overdue"] .text-2xl');
    
    if (totalCard && stats.total !== undefined) totalCard.textContent = stats.total;
    if (pendingCard && stats.pending !== undefined) pendingCard.textContent = stats.pending;
    if (paidCard && stats.paid !== undefined) paidCard.textContent = stats.paid;
    if (overdueCard && stats.overdue !== undefined) overdueCard.textContent = stats.overdue;
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
@endpush
@endsection
