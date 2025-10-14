@extends('layouts.admin')

@section('title', 'Quản lý hãng xe')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-dismiss="5000" />

<div class="space-y-6">
    {{-- Header --}}
    <x-admin.page-header 
        title="Quản lý hãng xe"
        description="Quản lý tất cả thương hiệu xe hơi trong hệ thống"
        icon="fas fa-industry">
        <a href="{{ route('admin.carbrands.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Thêm mới
        </a>
    </x-admin.page-header>


    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng hãng xe"
            :value="$totalCars"
            icon="fas fa-industry"
            color="blue"
            description="Tất cả thương hiệu"
            dataStat="total" />
        
        <x-admin.stats-card 
            title="Hoạt động"
            :value="$activeCars"
            icon="fas fa-check-circle"
            color="green"
            description="Đang kinh doanh"
            dataStat="active" />
        
        <x-admin.stats-card 
            title="Tạm dừng"
            :value="$inactiveCars"
            icon="fas fa-pause-circle"
            color="red"
            description="Ngừng hoạt động"
            dataStat="inactive" />
        
        <x-admin.stats-card 
            title="Nổi bật"
            :value="$featuredCars"
            icon="fas fa-star"
            color="yellow"
            description="Thương hiệu nổi bật"
            dataStat="featured" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        {{-- Loading Bar --}}
        <div id="filter-loading" class="hidden mb-4">
            <div class="w-full bg-gray-200 rounded-full h-1">
                <div class="bg-blue-600 h-1 rounded-full animate-pulse" style="width: 100%"></div>
            </div>
        </div>
        
        <form id="filterForm" method="GET" action="{{ route('admin.carbrands.index') }}" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.carbrands.index') }}">
            {{-- Search Input --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search" 
                    placeholder="Tên hãng xe, quốc gia..."
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
                        ['value' => 'inactive', 'label' => 'Tạm dừng'],
                        ['value' => 'featured', 'label' => 'Nổi bật']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="label"
                    :selected="request('status')"
                    onchange="loadCarBrandsFromDropdown"
                    :maxVisible="4"
                    :searchable="false"
                    width="w-full"
                    minWidth="min-w-[180px]" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadCarBrands" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="carbrands-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.carbrands.index') }}"
        callback-name="loadCarBrands"
        after-load-callback="initializeEventListeners">
        @include('admin.carbrands.partials.table', ['carbrands' => $carbrands])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal Component --}}
<x-admin.delete-modal 
    modal-id="deleteModal"
    title="Xác nhận xóa hãng xe"
    entity-name="hãng xe"
    warning-text="Bạn có chắc chắn muốn xóa"
    callback-name="confirmDelete" />

@push('scripts')
<script>
// Flash messages are now handled by FlashMessages component

// AJAX Functions - Now handled by AjaxTable component
// loadCarBrands function is automatically created by the component

// Initialize event listeners
function initializeEventListeners() {
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const brandId = this.dataset.carbrandId;
            const brandName = this.dataset.carbrandName;
            const modelsCount = parseInt(this.dataset.modelsCount) || 0;
            
            // Show delete modal with brand info
            if (window.deleteModalManager_deleteModal) {
                // Clear any previous modal content first
                window.deleteModalManager_deleteModal.reset();
                
                window.deleteModalManager_deleteModal.show({
                    entityName: brandName,
                    details: `<div class="text-sm">
                        <p><strong>Tác động:</strong></p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>${modelsCount} dòng xe sẽ bị ảnh hưởng</li>
                            <li>Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn</li>
                        </ul>
                    </div>`,
                    warnings: modelsCount > 0 ? `<div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                            <div class="text-sm text-yellow-800">
                                <strong>Cảnh báo:</strong> Hãng xe này có ${modelsCount} dòng xe. Việc xóa sẽ ảnh hưởng đến dữ liệu hiển thị.
                            </div>
                        </div>
                    </div>` : '',
                    deleteUrl: `/admin/carbrands/delete/${brandId}`
                });
            }
        });
    });
    
    // Simple Status toggle buttons
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const brandId = this.dataset.carbrandId;
            const newStatus = this.dataset.status === 'true';
            const buttonElement = this;
            
            // Show loading state
            const originalIcon = buttonElement.querySelector('i').className;
            buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
            buttonElement.disabled = true;
            
            try {
                const response = await fetch(`/admin/carbrands/${brandId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ is_active: newStatus })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Update button appearance based on NEW status
                    if (newStatus) {
                        // Now active -> show pause button
                        buttonElement.className = 'text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center';
                        buttonElement.title = 'Tạm dừng';
                        buttonElement.dataset.status = 'false'; // Next click will deactivate
                        buttonElement.querySelector('i').className = 'fas fa-pause w-4 h-4';
                    } else {
                        // Now inactive -> show play button
                        buttonElement.className = 'text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center';
                        buttonElement.title = 'Kích hoạt';
                        buttonElement.dataset.status = 'true'; // Next click will activate
                        buttonElement.querySelector('i').className = 'fas fa-play w-4 h-4';
                    }
                    
                    // Update status badge using component function
                    if (window.updateStatusBadge) {
                        window.updateStatusBadge(brandId, newStatus, 'carbrand');
                    }
                    
                    // Update stats cards if provided
                    if (data.stats && window.updateStatsFromServer) {
                        window.updateStatsFromServer(data.stats);
                    }
                    
                    // Show flash message from server response
                    if (data.message && window.showMessage) {
                        window.showMessage(data.message, 'success');
                    }
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            } catch (error) {
                console.error('Error:', error);
                // Restore original state
                buttonElement.querySelector('i').className = originalIcon;
                if (window.showMessage) {
                    window.showMessage('Có lỗi xảy ra khi cập nhật trạng thái!', 'error');
                }
            } finally {
                buttonElement.disabled = false;
            }
        });
    });
    
}

// Dropdown callback function
window.loadCarBrandsFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadCarBrands) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.carbrands.index") }}?' + new URLSearchParams(formData).toString();
        window.loadCarBrands(url);
    }
};

// Search input callback function
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadCarBrands) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.carbrands.index") }}?' + new URLSearchParams(formData).toString();
        window.loadCarBrands(url);
    }
};


// Delete confirmation function
window.confirmDelete = function(data) {
    if (!data || !data.deleteUrl) return;
    
    // Show loading state on delete button
    if (window.deleteModalManager_deleteModal) {
        window.deleteModalManager_deleteModal.setLoading(true);
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
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // 1. Close modal immediately
            if (window.deleteModalManager_deleteModal) {
                window.deleteModalManager_deleteModal.hide();
            }
            
            // 2. Update stats cards if provided
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            // 3. Reload table
            if (window.loadCarBrands) {
                window.loadCarBrands();
            }
            
            // 4. Show success message
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa hãng xe thành công!', 'success');
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra khi xóa');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        
        // Reset loading state on error
        if (window.deleteModalManager_deleteModal) {
            window.deleteModalManager_deleteModal.setLoading(false);
        }
        
        // Show error message from server
        if (window.showMessage) {
            window.showMessage(error.message || 'Có lỗi xảy ra khi xóa', 'error');
        }
    });
};

// Stats cards are now non-clickable for consistency

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    
    // Function to update stats cards from server data (delete/toggle)
    window.updateStatsFromServer = function(stats) {
        // Update all stats cards with server data
        const totalCard = document.querySelector('[data-stat="total"] .text-2xl');
        const activeCard = document.querySelector('[data-stat="active"] .text-2xl');
        const inactiveCard = document.querySelector('[data-stat="inactive"] .text-2xl');
        const featuredCard = document.querySelector('[data-stat="featured"] .text-2xl');
        
        // Update cards directly without animation
        if (totalCard && stats.totalCars !== undefined) {
            totalCard.textContent = stats.totalCars;
        }
        if (activeCard && stats.activeCars !== undefined) {
            activeCard.textContent = stats.activeCars;
        }
        if (inactiveCard && stats.inactiveCars !== undefined) {
            inactiveCard.textContent = stats.inactiveCars;
        }
        if (featuredCard && stats.featuredCars !== undefined) {
            featuredCard.textContent = stats.featuredCars;
        }
    };
});
</script>
@endpush
@endsection