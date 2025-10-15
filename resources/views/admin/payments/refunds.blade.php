@extends('layouts.admin')

@section('title', 'Quản lý hoàn tiền')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000" />

<div class="space-y-6">
        {{-- Page Header --}}
        <x-admin.page-header 
            title="Quản lý hoàn tiền"
            description="Xử lý các yêu cầu hoàn tiền từ khách hàng"
            icon="fas fa-undo">
        </x-admin.page-header>

        {{-- Stats Cards --}}
        @php
            $stats = [
                'pending' => $refunds->where('status', 'pending')->count(),
                'processing' => $refunds->where('status', 'processing')->count(),
                'refunded' => $refunds->where('status', 'refunded')->count(),
                'failed' => $refunds->where('status', 'failed')->count(),
            ];
        @endphp
        
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
            <x-admin.stats-card 
                title="Chờ xử lý"
                :value="$stats['pending']"
                icon="fas fa-clock"
                color="yellow"
                description="Yêu cầu mới"
                dataStat="pending"
                onclick="filterRefunds('pending')" />
                
            <x-admin.stats-card 
                title="Đang xử lý"
                :value="$stats['processing']"
                icon="fas fa-spinner"
                color="blue"
                description="Đang kiểm tra"
                dataStat="processing"
                onclick="filterRefunds('processing')" />
                
            <x-admin.stats-card 
                title="Đã hoàn tiền"
                :value="$stats['refunded']"
                icon="fas fa-check"
                color="green"
                description="Hoàn thành"
                dataStat="refunded"
                onclick="filterRefunds('refunded')" />
                
            <x-admin.stats-card 
                title="Thất bại"
                :value="$stats['failed']"
                icon="fas fa-times"
                color="red"
                description="Bị từ chối"
                dataStat="failed"
                onclick="filterRefunds('failed')" />
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <form id="filterForm" 
                  class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
                  data-base-url="{{ route('admin.payments.refunds') }}">
                
                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <x-admin.search-input 
                        name="search"
                        placeholder="Tìm theo ID, khách hàng, lý do..."
                        :value="request('search')"
                        callbackName="handleRefundSearch"
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
                            ['value' => 'processing', 'label' => 'Đang xử lý'],
                            ['value' => 'refunded', 'label' => 'Đã hoàn tiền'],
                            ['value' => 'failed', 'label' => 'Thất bại']
                        ]"
                        optionValue="value"
                        optionText="label"
                        :selected="request('status')"
                        placeholder="Tất cả"
                        onchange="loadRefundsFromDropdown"
                        :searchable="false"
                        width="w-full" />
                </div>
                
                {{-- Reset --}}
                <div>
                    <x-admin.reset-button 
                        formId="#filterForm" 
                        callback="loadRefunds" />
                </div>
            </form>
        </div>

        {{-- AJAX Table Component --}}
        <x-admin.ajax-table 
            table-id="refunds-content"
            loading-id="refunds-loading"
            form-id="#filterForm"
            base-url="{{ route('admin.payments.refunds') }}"
            callback-name="loadRefunds"
            after-load-callback="initializeRefundEventListeners">
            @include('admin.payments.partials.refunds-table', ['refunds' => $refunds])
        </x-admin.ajax-table>
    </div>
</div>

{{-- Delete Modal (if needed) --}}
<x-admin.delete-modal 
    modal-id="deleteRefundModal"
    title="Xác nhận xóa yêu cầu hoàn tiền"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeleteRefund"
    entity-type="refund" />

@push('scripts')
<script>
// Initialize event listeners
function initializeRefundEventListeners() {
    // Any additional event listeners for refunds can go here
    console.log('Refund event listeners initialized');
}

// Global functions for components integration
window.loadRefunds = function(url = null) {
    const baseUrl = url || '{{ route("admin.payments.refunds") }}';
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    
    // Build URL with parameters
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    const finalUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');
    
    // Use AJAX table component to load
    if (window.loadRefundsTable) {
        window.loadRefundsTable(finalUrl);
    } else {
        window.location.href = finalUrl;
    }
};

// Stats card click handlers
window.filterRefunds = function(status) {
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        // Update dropdown value
        statusSelect.value = status;
        // Trigger change event to update dropdown display
        statusSelect.dispatchEvent(new Event('change'));
    }
    
    // Load filtered results
    const baseUrl = '{{ route("admin.payments.refunds") }}';
    window.loadRefunds(baseUrl + '?status=' + status);
};

// Search input callback
window.handleRefundSearch = function(searchTerm, inputElement) {
    console.log('Refund search:', searchTerm);
    window.loadRefunds();
};

// Dropdown change handler
window.loadRefundsFromDropdown = function() {
    console.log('Loading refunds from dropdown change');
    window.loadRefunds();
};

function updateRefundStatus(refundId, status) {
    if (!status) return;
    
    if (!confirm('Bạn có chắc chắn muốn cập nhật trạng thái này?')) {
        return;
    }
    
    fetch(`/admin/payments/refunds/${refundId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            refund_status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof window.showMessage === 'function') {
                window.showMessage(data.message, 'success');
            } else {
                alert(data.message);
            }
            // Reload table content
            setTimeout(() => window.loadRefunds(), 1000);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof window.showMessage === 'function') {
            window.showMessage(error.message || 'Có lỗi xảy ra khi cập nhật trạng thái', 'error');
        } else {
            alert(error.message || 'Có lỗi xảy ra khi cập nhật trạng thái');
        }
    });
}
</script>
@endpush

@endsection
