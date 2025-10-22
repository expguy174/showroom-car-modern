@extends('layouts.admin')

@section('title', 'Quản lý dịch vụ')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000" />

<div class="space-y-3 sm:space-y-4 lg:space-y-6 px-2 sm:px-0">
    {{-- Header --}}
    <x-admin.page-header
        title="Quản lý dịch vụ"
        description="Danh sách tất cả dịch vụ của showroom"
        icon="fas fa-cogs">
        <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Thêm dịch vụ
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4">
        <x-admin.stats-card 
            title="Tổng dịch vụ"
            :value="$totalServices ?? 0"
            icon="fas fa-cogs"
            color="gray"
            description="Tất cả dịch vụ"
            dataStat="total" />
        
        <x-admin.stats-card 
            title="Đang hoạt động"
            :value="$activeServices ?? 0"
            icon="fas fa-check-circle"
            color="green"
            description="Hoạt động"
            dataStat="active" />
        
        <x-admin.stats-card 
            title="Tạm dừng"
            :value="$inactiveServices ?? 0"
            icon="fas fa-pause-circle"
            color="red"
            description="Không hoạt động"
            dataStat="inactive" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.services.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tên dịch vụ, mã dịch vụ..."
                    :value="request('search')"
                    callbackName="handleSearch"
                    :debounceTime="500"
                    size="small"
                    :showIcon="true"
                    :showClearButton="true" />
            </div>
            
            {{-- Category Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                <x-admin.custom-dropdown 
                    name="category"
                    :options="[
                        ['value' => 'maintenance', 'text' => 'Bảo dưỡng'],
                        ['value' => 'repair', 'text' => 'Sửa chữa'],
                        ['value' => 'diagnostic', 'text' => 'Chẩn đoán'],
                        ['value' => 'cosmetic', 'text' => 'Làm đẹp'],
                        ['value' => 'emergency', 'text' => 'Khẩn cấp']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="text"
                    :selected="request('category')"
                    onchange="loadServicesFromDropdown"
                    :maxVisible="5"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <x-admin.custom-dropdown 
                    name="status"
                    :options="[
                        ['value' => 'active', 'text' => 'Hoạt động'],
                        ['value' => 'inactive', 'text' => 'Tạm dừng']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="text"
                    :selected="request('status')"
                    onchange="loadServicesFromDropdown"
                    :maxVisible="5"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadServices" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="services-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.services.index') }}"
        callback-name="loadServices"
        empty-message="Không có dịch vụ nào"
        empty-icon="fas fa-cogs"
        :show-pagination="false">
        @include('admin.services.partials.table', ['services' => $services])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal Component --}}
<x-admin.delete-modal 
    modal-id="deleteServiceModal"
    title="Xác nhận xóa dịch vụ"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeleteService"
    entity-type="service" />

@push('scripts')
<script>
// Update stats from server response (giống payment-methods)
window.updateStatsFromServer = function(stats) {
    const statsMapping = {
        'total': 'total',
        'active': 'active',
        'inactive': 'inactive'
    };
    
    Object.entries(statsMapping).forEach(([serverKey, cardKey]) => {
        if (stats[serverKey] !== undefined) {
            const statElement = document.querySelector(`p[data-stat="${cardKey}"]`);
            if (statElement) {
                statElement.textContent = stats[serverKey];
            }
        }
    });
};

// Handle search
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.services.index") }}?' + new URLSearchParams(formData).toString();
        if (window.loadServices) {
            window.loadServices(url);
        }
    }
};

// Handle dropdown change
window.loadServicesFromDropdown = function(selectedValue, dropdownElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.services.index") }}?' + new URLSearchParams(formData).toString();
        if (window.loadServices) {
            window.loadServices(url);
        }
    }
};

// Initialize event listeners (make it global for ajax-table component)
window.initializeEventListeners = function() {
    // Status toggle buttons
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const serviceId = this.dataset.serviceId;
            const newStatus = this.dataset.status === 'true';
            const buttonElement = this;
            const originalIcon = buttonElement.querySelector('i').className;
            
            // Show loading spinner
            buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
            buttonElement.disabled = true;
            
            try {
                const response = await fetch(`/admin/services/${serviceId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                        window.updateStatusBadge(serviceId, newStatus, 'service');
                    }
                    
                    // Update stats cards if provided
                    if (data.stats && window.updateStatsFromServer) {
                        window.updateStatsFromServer(data.stats);
                    }
                    
                    // Show message
                    if (window.showMessage) {
                        window.showMessage(data.message, 'success');
                    }
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                console.error('Toggle error:', error);
                // Restore original state on error
                buttonElement.querySelector('i').className = originalIcon;
                if (window.showMessage) {
                    window.showMessage(error.message || 'Có lỗi khi thay đổi trạng thái', 'error');
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
            const serviceId = this.dataset.serviceId;
            const serviceName = this.dataset.serviceName;
            const deleteUrl = this.dataset.deleteUrl;
            
            if (window.deleteModalManager_deleteServiceModal) {
                window.deleteModalManager_deleteServiceModal.show({
                    entityName: `dịch vụ ${serviceName}`,
                    details: 'Hành động này không thể hoàn tác.',
                    deleteUrl: deleteUrl
                });
            }
        });
    });
    
    // Pagination links - handled by ajax-table component
};

// Delete confirmation callback
window.confirmDeleteService = function(data) {
    if (!data || !data.deleteUrl) return;
    
    if (window.deleteModalManager_deleteServiceModal) {
        window.deleteModalManager_deleteServiceModal.setLoading(true);
    }
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (window.deleteModalManager_deleteServiceModal) {
                window.deleteModalManager_deleteServiceModal.hide();
            }
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa dịch vụ thành công!', 'success');
            }
            
            // Reload table using ajax-table component
            if (window.loadServices) {
                window.loadServices();
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deleteServiceModal) {
            window.deleteModalManager_deleteServiceModal.setLoading(false);
        }
        
        const errorMsg = error.message || 'Có lỗi khi xóa dịch vụ';
        if (window.showMessage) {
            window.showMessage(errorMsg, 'error');
        }
    });
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    if (window.initializeEventListeners) {
        window.initializeEventListeners();
    }
});
</script>
@endpush
@endsection
