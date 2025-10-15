@extends('layouts.admin')

@section('title', 'Quản lý khuyến mãi')

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
        title="Quản lý khuyến mãi"
        description="Quản lý các chương trình khuyến mãi và ưu đãi"
        icon="fas fa-tags">
        <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Thêm khuyến mãi
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng khuyến mãi"
            :value="$totalPromotions ?? 0"
            icon="fas fa-tags"
            color="blue"
            description="Tất cả chương trình"
            dataStat="total" />
            
        <x-admin.stats-card 
            title="Hoạt động"
            :value="$activePromotions ?? 0"
            icon="fas fa-play-circle"
            color="green"
            description="Đang áp dụng"
            dataStat="active" />
            
        <x-admin.stats-card 
            title="Tạm dừng"
            :value="$inactivePromotions ?? 0"
            icon="fas fa-pause-circle"
            color="orange"
            description="Không hoạt động"
            dataStat="inactive" />
            
        <x-admin.stats-card 
            title="Hết hạn"
            :value="$expiredPromotions ?? 0"
            icon="fas fa-exclamation-triangle"
            color="red"
            description="Đã kết thúc"
            dataStat="expired" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_auto_auto] gap-4 items-end"
              data-base-url="{{ route('admin.promotions.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tên khuyến mãi, mã code..."
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
                        ['value' => 'active', 'text' => 'Hoạt động'],
                        ['value' => 'inactive', 'text' => 'Tạm dừng'],
                        ['value' => 'expired', 'text' => 'Hết hạn']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="text"
                    :selected="request('status')"
                    onchange="loadPromotionsFromDropdown"
                    :maxVisible="4"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadPromotionsWithStats" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="promotions-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.promotions.index') }}"
        callback-name="loadPromotions"
        after-load-callback="initializeEventListeners"
        :update-stats="true">
        @include('admin.promotions.partials.table', ['promotions' => $promotions])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal Component --}}
<x-admin.delete-modal 
    modal-id="deleteModal"
    title="Xác nhận xóa khuyến mãi"
    entity-name="khuyến mãi"
    warning-text="Bạn có chắc chắn muốn xóa"
    confirm-text="Xóa"
    cancel-text="Hủy" />

@push('scripts')
<script>
// Initialize event listeners
function initializeEventListeners() {
    initializeStatusToggle();
    initializeDeleteButtons();
}

// Status toggle functionality for promotions
function initializeStatusToggle() {
    document.querySelectorAll('.status-toggle').forEach(button => {
        // Remove existing listener to prevent duplicates
        button.removeEventListener('click', handleStatusToggle);
        // Add new listener
        button.addEventListener('click', handleStatusToggle);
    });
}

async function handleStatusToggle(e) {
    e.preventDefault();
    const promotionId = this.dataset.promotionId || this.getAttribute('data-promotion-id');
    const newStatus = this.dataset.status === 'true';
    const buttonElement = this;
        
    // Show loading state
    const originalIcon = buttonElement.querySelector('i').className;
    buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
    buttonElement.disabled = true;
    
    try {
        const response = await fetch(`/admin/promotions/${promotionId}/toggle`, {
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
            
            // Update status badge if exists
            if (window.updateStatusBadge) {
                window.updateStatusBadge(promotionId, newStatus, 'promotion');
            }
            
            // Update stats cards
            if (window.loadPromotionsWithStats) {
                const searchForm = document.getElementById('filterForm');
                const formData = new FormData(searchForm);
                const statsUrl = '{{ route("admin.promotions.index") }}?' + new URLSearchParams(formData).toString() + '&stats_only=1';
                
                fetch(statsUrl, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(stats => {
                    if (window.updateStatsFromServer) {
                        window.updateStatsFromServer(stats);
                    }
                })
                .catch(error => console.log('Stats update failed:', error));
            }
            
            // Show success message using flash-message component
            if (window.showMessage) {
                window.showMessage(data.message || 'Cập nhật trạng thái thành công!', 'success');
            }
            
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
        
    } catch (error) {
        console.error('Toggle error:', error);
        if (window.showMessage) {
            window.showMessage(error.message || 'Có lỗi xảy ra khi cập nhật trạng thái', 'error');
        }
        
        // Restore original state
        buttonElement.querySelector('i').className = originalIcon;
    } finally {
        buttonElement.disabled = false;
    }
}

// Initialize delete button event listeners
function initializeDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach((btn) => {
        // Remove existing listeners to prevent duplicates
        btn.removeEventListener('click', handleDeleteClick);
        // Add new listener
        btn.addEventListener('click', handleDeleteClick);
    });
}

// Handle delete button click
function handleDeleteClick(e) {
    e.preventDefault();
    
    const promotionId = this.dataset.promotionId;
    const promotionName = this.dataset.promotionName;
    const promotionCode = this.dataset.promotionCode;
    const promotionType = this.dataset.promotionType;
    const promotionValue = this.dataset.promotionValue;
    const usageCount = parseInt(this.dataset.usageCount) || 0;
    
    // Show delete modal with promotion info
    if (window.deleteModalManager_deleteModal) {
        // Clear any previous modal content first
        window.deleteModalManager_deleteModal.reset();
        
        // Determine type display name
        const typeNames = {
            'percentage': 'Giảm theo %',
            'fixed_amount': 'Giảm cố định',
            'free_shipping': 'Miễn phí ship',
            'brand_specific': 'Theo thương hiệu'
        };
        const typeDisplay = typeNames[promotionType] || 'Khác';
        
        // Format value
        let valueDisplay = '';
        if (promotionType === 'percentage') {
            valueDisplay = promotionValue + '%';
        } else if (promotionType === 'fixed_amount') {
            valueDisplay = new Intl.NumberFormat('vi-VN').format(promotionValue) + 'đ';
        } else {
            valueDisplay = '-';
        }
        
        // Usage status
        const hasUsage = usageCount > 0;
        const usageText = hasUsage ? `Đã được sử dụng ${usageCount} lần` : 'Chưa được sử dụng';
        const usageColor = hasUsage ? 'text-orange-600' : 'text-green-600';
        
        window.deleteModalManager_deleteModal.show({
            entityName: `${promotionName}`,
            details: `<div class="text-sm space-y-2">
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="font-medium text-gray-600">Mã:</span>
                            <span class="text-gray-900 font-mono">${promotionCode}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Loại:</span>
                            <span class="text-gray-900">${typeDisplay}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Giá trị:</span>
                            <span class="text-gray-900 font-semibold">${valueDisplay}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Sử dụng:</span>
                            <span class="${usageColor} font-medium">${usageText}</span>
                        </div>
                    </div>
                </div>
            </div>` + (hasUsage ? `
            <div class="mt-3 p-3 bg-orange-50 border border-orange-200 rounded-md">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-orange-500 mt-0.5 mr-2"></i>
                    <div class="text-sm text-orange-700">
                        <strong>Cảnh báo:</strong> Khuyến mãi này đã được sử dụng. Việc xóa có thể ảnh hưởng đến lịch sử đơn hàng.
                    </div>
                </div>
            </div>` : ''),
            deleteUrl: `/admin/promotions/${promotionId}`
        });
    }
}

// Delete confirmation function
window.confirmDelete = function(data) {
    if (!data || !data.deleteUrl) {
        return;
    }
    
    // Show loading state on delete button
    if (window.deleteModalManager_deleteModal) {
        window.deleteModalManager_deleteModal.setLoading(true);
    }
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // 1. Close modal immediately
            if (window.deleteModalManager_deleteModal) {
                window.deleteModalManager_deleteModal.hide();
            }
            
            // 2. Update stats cards and reload table
            if (window.loadPromotionsWithStats) {
                const searchForm = document.getElementById('filterForm');
                const formData = new FormData(searchForm);
                const url = '{{ route("admin.promotions.index") }}?' + new URLSearchParams(formData).toString();
                window.loadPromotionsWithStats(url);
            }
            
            // 3. Show success message
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa khuyến mãi thành công!', 'success');
            }
        } else {
            // Handle error response
            if (window.deleteModalManager_deleteModal) {
                window.deleteModalManager_deleteModal.setLoading(false);
            }
            
            if (data.message && window.showMessage) {
                window.showMessage(data.message, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Hide loading state
        if (window.deleteModalManager_deleteModal) {
            window.deleteModalManager_deleteModal.setLoading(false);
        }
        
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra khi xóa khuyến mãi', 'error');
        }
    });
};


// Custom load function with stats update
window.loadPromotionsWithStats = function(url) {
    // First load the table
    if (window.loadPromotions) {
        window.loadPromotions(url);
    }
    
    // Then fetch stats separately
    fetch(url + (url.includes('?') ? '&' : '?') + 'stats_only=1', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(stats => {
        if (window.updateStatsFromServer) {
            window.updateStatsFromServer(stats);
        }
    })
    .catch(error => console.log('Stats update failed:', error));
};

// Dropdown callback
window.loadPromotionsFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.promotions.index") }}?' + new URLSearchParams(formData).toString();
        window.loadPromotionsWithStats(url);
    }
};

// Search callback
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.promotions.index") }}?' + new URLSearchParams(formData).toString();
        window.loadPromotionsWithStats(url);
    }
};

// Update stats from server
window.updateStatsFromServer = function(stats) {
    const totalCard = document.querySelector('[data-stat="total"] .text-2xl');
    const activeCard = document.querySelector('[data-stat="active"] .text-2xl');
    const inactiveCard = document.querySelector('[data-stat="inactive"] .text-2xl');
    const expiredCard = document.querySelector('[data-stat="expired"] .text-2xl');
    
    if (totalCard && stats.total !== undefined) totalCard.textContent = stats.total;
    if (activeCard && stats.active !== undefined) activeCard.textContent = stats.active;
    if (inactiveCard && stats.inactive !== undefined) inactiveCard.textContent = stats.inactive;
    if (expiredCard && stats.expired !== undefined) expiredCard.textContent = stats.expired;
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
@endpush
@endsection
