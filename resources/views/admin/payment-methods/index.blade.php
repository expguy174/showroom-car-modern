@extends('layouts.admin')

@section('title', 'Quản lý phương thức thanh toán')

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
        title="Phương thức thanh toán"
        description="Quản lý các phương thức thanh toán cho khách hàng"
        icon="fas fa-credit-card">
        <a href="{{ route('admin.payment-methods.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Thêm phương thức
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng phương thức"
            :value="\App\Models\PaymentMethod::count()"
            icon="fas fa-credit-card"
            color="blue"
            description="Tất cả phương thức"
            dataStat="total" />
            
        <x-admin.stats-card 
            title="Hoạt động"
            :value="\App\Models\PaymentMethod::where('is_active', true)->count()"
            icon="fas fa-check-circle"
            color="green"
            description="Đang hỗ trợ"
            dataStat="active" />
            
        <x-admin.stats-card 
            title="Tạm dừng"
            :value="\App\Models\PaymentMethod::where('is_active', false)->count()"
            icon="fas fa-times-circle"
            color="red"
            description="Ngừng hỗ trợ"
            dataStat="inactive" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.payment-methods.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tìm theo tên phương thức, provider..."
                    :value="request('search')"
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
                        ['value' => 'active', 'label' => 'Hoạt động'],
                        ['value' => 'inactive', 'label' => 'Tạm dừng']
                    ]"
                    optionValue="value"
                    optionText="label"
                    :selected="request('status')"
                    placeholder="Tất cả"
                    onchange="loadPaymentMethodsFromDropdown"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadPaymentMethods" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="payment-methods-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.payment-methods.index') }}"
        callback-name="loadPaymentMethods"
        after-load-callback="initializeEventListeners">
        @include('admin.payment-methods.partials.table', ['paymentMethods' => $paymentMethods])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal --}}
<x-admin.delete-modal 
    modal-id="deletePaymentModal"
    title="Xác nhận xóa phương thức thanh toán"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeletePayment"
    entity-type="payment" />

@push('scripts')
<script>
// Initialize event listeners
function initializeEventListeners() {
    // Status toggle buttons
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const paymentId = this.dataset.paymentmethodId || this.dataset.paymentId;
            const newStatus = this.dataset.status === 'true';
            const buttonElement = this;
            
            // Show loading state
            const originalIcon = buttonElement.querySelector('i').className;
            buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
            buttonElement.disabled = true;
            
            try {
                const response = await fetch(`/admin/payment-methods/${paymentId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update button appearance
                    if (newStatus) {
                        buttonElement.className = 'text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center';
                        buttonElement.title = 'Tạm dừng';
                        buttonElement.dataset.status = 'false';
                        buttonElement.querySelector('i').className = 'fas fa-pause w-4 h-4';
                    } else {
                        buttonElement.className = 'text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center';
                        buttonElement.title = 'Kích hoạt';
                        buttonElement.dataset.status = 'true';
                        buttonElement.querySelector('i').className = 'fas fa-play w-4 h-4';
                    }
                    
                    // Update status badge
                    if (window.updateStatusBadge) {
                        window.updateStatusBadge(paymentId, newStatus, 'payment');
                    }
                    
                    // Update stats cards if provided
                    if (data.stats && window.updateStatsFromServer) {
                        window.updateStatsFromServer(data.stats);
                    }
                    
                    // Show message
                    if (data.message && window.showMessage) {
                        window.showMessage(data.message, 'success');
                    }
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                console.error('Error:', error);
                buttonElement.querySelector('i').className = originalIcon;
                if (window.showMessage) {
                    window.showMessage('Có lỗi xảy ra khi cập nhật trạng thái!', 'error');
                }
            } finally {
                buttonElement.disabled = false;
            }
        });
    });
    
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const paymentId = this.dataset.paymentmethodId || this.dataset.paymentId;
            const paymentName = this.dataset.paymentmethodName || this.dataset.paymentName;
            
            if (window.deleteModalManager_deletePaymentModal) {
                window.deleteModalManager_deletePaymentModal.show({
                    entityName: `phương thức ${paymentName}`,
                    details: 'Hành động này không thể hoàn tác.',
                    deleteUrl: `/admin/payment-methods/${paymentId}`
                });
            }
        });
    });
}

// Dropdown callback
window.loadPaymentMethodsFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadPaymentMethods) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.payment-methods.index") }}?' + new URLSearchParams(formData).toString();
        window.loadPaymentMethods(url);
    }
};

// Search callback
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadPaymentMethods) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.payment-methods.index") }}?' + new URLSearchParams(formData).toString();
        window.loadPaymentMethods(url);
    }
};

// Delete confirmation
window.confirmDeletePayment = function(data) {
    if (!data || !data.deleteUrl) return;
    
    if (window.deleteModalManager_deletePaymentModal) {
        window.deleteModalManager_deletePaymentModal.setLoading(true);
    }
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) {
                // Server returned error status (400, 500, etc.)
                throw { status: response.status, data: data };
            }
            return data;
        });
    })
    .then(data => {
        if (data.success) {
            if (window.deleteModalManager_deletePaymentModal) {
                window.deleteModalManager_deletePaymentModal.hide();
            }
            
            // Update stats cards if provided
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            if (window.loadPaymentMethods) {
                window.loadPaymentMethods();
            }
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa phương thức thanh toán thành công!', 'success');
            }
        } else {
            throw { status: 200, data: data };
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deletePaymentModal) {
            window.deleteModalManager_deletePaymentModal.setLoading(false);
        }
        
        // Use server error message if available
        let errorMessage = 'Có lỗi xảy ra khi xóa phương thức thanh toán!';
        if (error.data && error.data.message) {
            errorMessage = error.data.message;
        } else if (error.message) {
            errorMessage = error.message;
        }
        
        if (window.showMessage) {
            window.showMessage(errorMessage, 'error');
        }
    });
};

// Function to update stats cards from server data
window.updateStatsFromServer = function(stats) {
    const totalCard = document.querySelector('[data-stat="total"] .text-2xl');
    const activeCard = document.querySelector('[data-stat="active"] .text-2xl');
    const inactiveCard = document.querySelector('[data-stat="inactive"] .text-2xl');
    
    if (totalCard && stats.total !== undefined) {
        totalCard.textContent = stats.total;
    }
    if (activeCard && stats.active !== undefined) {
        activeCard.textContent = stats.active;
    }
    if (inactiveCard && stats.inactive !== undefined) {
        inactiveCard.textContent = stats.inactive;
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
@endpush

@endsection
