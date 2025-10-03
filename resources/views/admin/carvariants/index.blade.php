@extends('layouts.admin')

@section('title', 'Quản lý phiên bản xe')

@push('styles')
<style>
    @media (min-width: 475px) {
        .xs\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    /* Override StatusToggle component CSS to prevent conflicts with table badges */
    .status-toggle {
        /* Ensure status toggle buttons don't affect table badges */
        isolation: isolate;
    }
    
    /* Ensure table badges maintain original styling */
    tbody tr td span.inline-flex.items-center {
        /* Force original padding and styling */
        padding: 0.125rem 0.375rem !important; /* px-1.5 py-0.5 */
        font-size: 0.75rem !important; /* text-xs */
        line-height: 1rem !important;
    }
</style>
@endpush

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
        title="Quản lý phiên bản xe"
        description="Quản lý tất cả phiên bản xe theo từng mẫu"
        icon="fas fa-cubes">
        <a href="{{ route('admin.carvariants.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm mới</span>
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng phiên bản"
            :value="$totalVariants"
            icon="fas fa-cubes"
            color="blue"
            description="Tất cả phiên bản xe"
            dataStat="total" />
            
        <x-admin.stats-card 
            title="Hoạt động"
            :value="$activeVariants"
            icon="fas fa-check-circle"
            color="green"
            description="Đang kinh doanh"
            dataStat="active" />
            
        <x-admin.stats-card 
            title="Tạm dừng"
            :value="$inactiveVariants"
            icon="fas fa-times-circle"
            color="red"
            description="Tạm ngừng bán"
            dataStat="inactive" />
            
        <x-admin.stats-card 
            title="Khuyến mãi"
            :value="$onSaleVariants"
            icon="fas fa-tags"
            color="orange"
            description="Đang có ưu đãi giá" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.carvariants.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tìm kiếm..."
                    :value="request('search')"
                    callbackName="handleSearch"
                    :debounceTime="500"
                    size="small"
                    :showIcon="true"
                    :showClearButton="true" />
            </div>
            
            {{-- Dòng xe --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dòng xe</label>
                <x-admin.custom-dropdown 
                    name="car_model_id"
                    :options="$carModels"
                    placeholder="Tất cả"
                    optionValue="id"
                    optionText="name"
                    optionSubtext="carBrand"
                    :selected="request('car_model_id')"
                    onchange="loadCarVariantsFromDropdown"
                    :maxVisible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Trạng thái --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <x-admin.custom-dropdown 
                    name="status"
                    :options="[
                        ['value' => 'active', 'label' => 'Hoạt động'],
                        ['value' => 'inactive', 'label' => 'Tạm dừng'],
                        ['value' => 'featured', 'label' => 'Nổi bật'],
                        ['value' => 'on_sale', 'label' => 'Khuyến mãi'],
                        ['value' => 'new_arrival', 'label' => 'Mới về'],
                        ['value' => 'bestseller', 'label' => 'Bán chạy']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="label"
                    :selected="request('status')"
                    onchange="loadCarVariantsFromDropdown"
                    :maxVisible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadCarVariants" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="carvariants-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.carvariants.index') }}"
        callback-name="loadCarVariants"
        after-load-callback="initializeEventListeners">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @include('admin.carvariants.partials.table', ['carVariants' => $carVariants])
        </div>
    </x-admin.ajax-table>
</div>

{{-- Delete Modal Component --}}
<x-admin.delete-modal 
    modal-id="deleteModal"
    title="Xác nhận xóa phiên bản xe"
    entity-name="phiên bản xe"
    warning-text="Bạn có chắc chắn muốn xóa"
    callback-name="confirmDelete" />

@push('scripts')
<script>
let deleteFormId = null;

// Flash messages are now handled by FlashMessages component

// AJAX Functions - Now handled by AjaxTable component
// loadCarVariants function is automatically created by the component


// Initialize event listeners
function initializeEventListeners() {
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const variantId = this.dataset.carvariantId;
            const variantName = this.dataset.carvariantName;
            const modelName = this.dataset.modelName;
            const colorsCount = parseInt(this.dataset.colorsCount) || 0;
            const imagesCount = parseInt(this.dataset.imagesCount) || 0;
            
            // Show delete modal with variant info
            if (window.deleteModalManager_deleteModal) {
                // Clear any previous modal content first
                window.deleteModalManager_deleteModal.reset();
                
                window.deleteModalManager_deleteModal.show({
                    entityName: `${variantName} (${modelName})`,
                    details: `<div class="text-sm">
                        <p><strong>Tác động:</strong></p>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>${colorsCount} màu sắc sẽ bị xóa</li>
                            <li>${imagesCount} hình ảnh sẽ bị xóa</li>
                            <li>Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn</li>
                        </ul>
                    </div>`,
                    warnings: colorsCount > 0 || imagesCount > 0 ? `<div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                            <div class="text-sm text-yellow-800">
                                <strong>Cảnh báo:</strong> Phiên bản xe này có ${colorsCount} màu sắc và ${imagesCount} hình ảnh. Việc xóa sẽ ảnh hưởng đến dữ liệu hiển thị.
                            </div>
                        </div>
                    </div>` : '',
                    deleteUrl: `/admin/carvariants/delete/${variantId}`
                });
            }
        });
    });
    
    // Simple Status toggle buttons
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const variantId = this.dataset.carvariantId;
            const newStatus = this.dataset.status === 'true';
            const buttonElement = this;
            
            // Show loading state
            const originalIcon = buttonElement.querySelector('i').className;
            buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
            buttonElement.disabled = true;
            
            try {
                const response = await fetch(`/admin/carvariants/${variantId}/toggle-status`, {
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
                        window.updateStatusBadge(variantId, newStatus, 'carvariant');
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
window.loadCarVariantsFromDropdown = function() {
    console.log('Dropdown callback triggered');
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadCarVariants) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString();
        console.log('Loading URL:', url);
        window.loadCarVariants(url);
    }
};

// Search input callback function
window.handleSearch = function(searchTerm, inputElement) {
    console.log('Search callback triggered:', searchTerm);
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadCarVariants) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString();
        console.log('Search URL:', url);
        window.loadCarVariants(url);
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
        },
        body: JSON.stringify({
            confirmed: true
        })
    })
    .then(response => response.json())
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
            if (window.loadCarVariants) {
                window.loadCarVariants();
            }
            
            // 4. Show success message
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa phiên bản xe thành công!', 'success');
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
        
        if (window.showMessage) {
            window.showMessage(error.message || 'Có lỗi xảy ra khi xóa', 'error');
        }
    });
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    
    // Simple re-initialization - no wrapper needed
    // The AJAX table component will handle reloading
    
    // Function to update stats cards from server data (delete/toggle)
    window.updateStatsFromServer = function(stats) {
        // Update all stats cards with server data
        const totalCard = document.querySelector('[data-stat="total"] .text-2xl');
        const activeCard = document.querySelector('[data-stat="active"] .text-2xl');
        const inactiveCard = document.querySelector('[data-stat="inactive"] .text-2xl');
        const featuredCard = document.querySelector('[data-stat="featured"] .text-2xl');
        const onSaleCard = document.querySelector('[data-stat="on_sale"] .text-2xl');
        const newArrivalCard = document.querySelector('[data-stat="new_arrival"] .text-2xl');
        
        // Update cards directly without animation
        if (totalCard && stats.totalVariants !== undefined) {
            totalCard.textContent = stats.totalVariants;
        }
        if (activeCard && stats.activeVariants !== undefined) {
            activeCard.textContent = stats.activeVariants;
        }
        if (inactiveCard && stats.inactiveVariants !== undefined) {
            inactiveCard.textContent = stats.inactiveVariants;
        }
        if (featuredCard && stats.featuredVariants !== undefined) {
            featuredCard.textContent = stats.featuredVariants;
        }
        if (onSaleCard && stats.onSaleVariants !== undefined) {
            onSaleCard.textContent = stats.onSaleVariants;
        }
        if (newArrivalCard && stats.newArrivalVariants !== undefined) {
            newArrivalCard.textContent = stats.newArrivalVariants;
        }
        
        // Also handle bestseller if exists
        const bestsellerCard = document.querySelector('[data-stat="bestseller"] .text-2xl');
        if (bestsellerCard && stats.bestsellerVariants !== undefined) {
            bestsellerCard.textContent = stats.bestsellerVariants;
        }
    };
    
    // Function to update stats cards when toggle status changes (manual calculation)
    window.updateStatsCards = function(wasActive, newStatus) {
        const activeCard = document.querySelector('[data-stat="active"] .text-2xl');
        const inactiveCard = document.querySelector('[data-stat="inactive"] .text-2xl');
        
        if (!activeCard || !inactiveCard) {
            return;
        }
        
        let activeCount = parseInt(activeCard.textContent) || 0;
        let inactiveCount = parseInt(inactiveCard.textContent) || 0;
        
        // Update counts based on status change
        if (wasActive && !newStatus) {
            activeCount = Math.max(0, activeCount - 1);
            inactiveCount = inactiveCount + 1;
        } else if (!wasActive && newStatus) {
            activeCount = activeCount + 1;
            inactiveCount = Math.max(0, inactiveCount - 1);
        } else {
            return;
        }
        
        // Update the display with animation
        activeCard.textContent = activeCount;
        inactiveCard.textContent = inactiveCount;
        
        // Add visual feedback
        const activeCardContainer = activeCard.closest('[data-stat="active"]');
        const inactiveCardContainer = inactiveCard.closest('[data-stat="inactive"]');
        
        if (wasActive && !newStatus) {
            inactiveCardContainer.classList.add('ring-2', 'ring-red-300');
            setTimeout(() => inactiveCardContainer.classList.remove('ring-2', 'ring-red-300'), 1000);
        } else if (!wasActive && newStatus) {
            activeCardContainer.classList.add('ring-2', 'ring-green-300');
            setTimeout(() => activeCardContainer.classList.remove('ring-2', 'ring-green-300'), 1000);
        }
    };
});
</script>
@endpush
@endsection