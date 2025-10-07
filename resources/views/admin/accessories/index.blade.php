@extends('layouts.admin')

@section('title', 'Quản lý phụ kiện')

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
        title="Quản lý phụ kiện"
        description="Quản lý tất cả phụ kiện xe hơi trong hệ thống"
        icon="fas fa-cogs">
        <a href="{{ route('admin.accessories.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            <span>Thêm mới</span>
        </a>
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng phụ kiện"
            :value="$totalAccessories"
            icon="fas fa-cogs"
            color="blue"
            description="Tất cả phụ kiện"
            dataStat="total" />
        
        <x-admin.stats-card 
            title="Hoạt động"
            :value="$activeAccessories"
            icon="fas fa-check-circle"
            color="green"
            description="Đang kinh doanh"
            dataStat="active" />
        
        <x-admin.stats-card 
            title="Tạm dừng"
            :value="$inactiveAccessories"
            icon="fas fa-pause-circle"
            color="red"
            description="Ngừng hoạt động"
            dataStat="inactive" />
        
        <x-admin.stats-card 
            title="Nổi bật"
            :value="$featuredAccessories"
            icon="fas fa-star"
            color="yellow"
            description="Phụ kiện nổi bật"
            dataStat="featured" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.accessories.index') }}">
            
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
            
            {{-- Category --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
                <x-admin.custom-dropdown 
                    name="category"
                    :options="$categories"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="label"
                    :selected="request('category')"
                    onchange="loadAccessoriesFromDropdown"
                    :maxVisible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Status --}}
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
                        ['value' => 'bestseller', 'label' => 'Bán chạy'],
                        ['value' => 'in_stock', 'label' => 'Còn hàng'],
                        ['value' => 'out_of_stock', 'label' => 'Hết hàng']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="label"
                    :selected="request('status')"
                    onchange="loadAccessoriesFromDropdown"
                    :maxVisible="8"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadAccessories" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="accessories-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.accessories.index') }}"
        callback-name="loadAccessories">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @include('admin.accessories.partials.table', ['accessories' => $accessories])
        </div>
    </x-admin.ajax-table>
</div>

{{-- Delete Modal Component --}}
<x-admin.delete-modal 
    modal-id="deleteModal"
    title="Xác nhận xóa phụ kiện"
    entity-name="phụ kiện"
    warning-text="Bạn có chắc chắn muốn xóa"
    callback-name="confirmDelete" />

@push('scripts')
<script>
// Initialize delete button event listeners
document.addEventListener('DOMContentLoaded', function() {
    initializeDeleteButtons();
});

function initializeDeleteButtons() {
    console.log('🔍 Initializing delete buttons...');
    const deleteButtons = document.querySelectorAll('.delete-btn');
    console.log('🔍 Found delete buttons:', deleteButtons.length);
    
    deleteButtons.forEach((btn, index) => {
        console.log(`🔍 Delete button ${index}:`, btn);
        
        // Remove existing listeners to prevent duplicates
        btn.removeEventListener('click', handleDeleteClick);
        
        // Add new listener
        btn.addEventListener('click', handleDeleteClick);
    });
}

function handleDeleteClick(e) {
    e.preventDefault();
    const accessoryId = this.dataset.accessoryId;
    const accessoryName = this.dataset.accessoryName;
    const category = this.dataset.category;
    const stockQuantity = parseInt(this.dataset.stockQuantity) || 0;
    
    // Show delete modal with accessory info
    if (window.deleteModalManager_deleteModal) {
        // Clear any previous modal content first
        window.deleteModalManager_deleteModal.reset();
        
        // Determine category display name
        const categoryDisplay = category ? getCategoryDisplayName(category) : 'Không xác định';
        
        // Determine stock status and warnings
        const hasStock = stockQuantity > 0;
        const stockStatus = hasStock ? 'Còn hàng' : 'Hết hàng';
        const stockColor = hasStock ? 'text-green-600' : 'text-red-600';
        
        window.deleteModalManager_deleteModal.show({
            entityName: `${accessoryName}`,
            details: `<div class="text-sm space-y-2">
                <div class="bg-gray-50 rounded-md p-3">
                    <p><strong>Thông tin phụ kiện:</strong></p>
                    <ul class="mt-2 space-y-1">
                        <li><strong>Danh mục:</strong> ${categoryDisplay}</li>
                        <li><strong>Số lượng tồn kho:</strong> <span class="${stockColor}">${stockQuantity} sản phẩm (${stockStatus})</span></li>
                    </ul>
                </div>
                <div class="bg-red-50 rounded-md p-3">
                    <p><strong>Tác động khi xóa:</strong></p>
                    <ul class="list-disc list-inside mt-2 space-y-1 text-red-800">
                        <li>Phụ kiện sẽ bị xóa vĩnh viễn khỏi hệ thống</li>
                        <li>Hình ảnh và dữ liệu liên quan sẽ bị xóa</li>
                        <li>Không thể khôi phục sau khi xóa</li>
                        ${hasStock ? '<li><strong>Cảnh báo:</strong> Vẫn còn hàng tồn kho!</li>' : ''}
                    </ul>
                </div>
            </div>`,
            warnings: hasStock ? `<div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-400 mr-2 mt-0.5"></i>
                    <div class="text-sm text-yellow-800">
                        <strong>Cảnh báo:</strong> Phụ kiện này vẫn còn ${stockQuantity} sản phẩm trong kho. 
                        Bạn có chắc chắn muốn xóa không? Khuyến nghị nên "Tạm dừng" thay vì xóa.
                    </div>
                </div>
            </div>` : '',
            deleteUrl: `/admin/accessories/delete/${accessoryId}`
        });
    }
}

// Helper function to get category display name in Vietnamese
function getCategoryDisplayName(category) {
    const categoryMap = {
        'electronics': 'Điện tử',
        'interior': 'Nội thất',
        'exterior': 'Ngoại thất',
        'safety': 'An toàn',
        'performance': 'Hiệu suất',
        'comfort': 'Tiện nghi',
        'maintenance': 'Bảo dưỡng',
        'decoration': 'Trang trí'
    };
    return categoryMap[category] || category;
}

// Search input callback function
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadAccessories) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.accessories.index") }}?' + new URLSearchParams(formData).toString();
        window.loadAccessories(url);
    }
};

// Dropdown callback function
window.loadAccessoriesFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadAccessories) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.accessories.index") }}?' + new URLSearchParams(formData).toString();
        window.loadAccessories(url);
    }
};

function loadAccessoriesFromDropdown() {
    if (typeof window.loadAccessoriesFromDropdown === 'function') {
        window.loadAccessoriesFromDropdown();
    }
}

// Status toggle functionality
document.querySelectorAll('.status-toggle').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();
        const accessoryId = this.dataset.accessoryId;
        const newStatus = this.dataset.status === 'true';
        const buttonElement = this;
        
        // Show loading state
        const originalIcon = buttonElement.querySelector('i').className;
        buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
        buttonElement.disabled = true;
        
        try {
            const response = await fetch(`/admin/accessories/${accessoryId}/toggle-status`, {
                method: 'PATCH',
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
                    window.updateStatusBadge(accessoryId, newStatus, 'accessory');
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

// Delete confirmation function
window.confirmDelete = function(data) {
    console.log('🗑️ Delete function called with data:', data);
    if (!data || !data.deleteUrl) {
        console.log('❌ No data or deleteUrl provided');
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
            
            // 3. Reload accessories table
            if (window.loadAccessories) {
                const currentUrl = window.location.href;
                window.loadAccessories(currentUrl);
                
                // Re-initialize delete buttons after table reload
                setTimeout(() => {
                    initializeDeleteButtons();
                }, 500);
            }
            
            // 4. Show success message
            if (data.message && window.showMessage) {
                window.showMessage(data.message, 'success');
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
            window.showMessage('Có lỗi xảy ra khi xóa phụ kiện!', 'error');
        }
    });
};

// Function to update stats cards from server data (toggle/delete)
window.updateStatsFromServer = function(stats) {
    // Update all stats cards with server data
    const totalCard = document.querySelector('[data-stat="total"] .text-2xl');
    const activeCard = document.querySelector('[data-stat="active"] .text-2xl');
    const inactiveCard = document.querySelector('[data-stat="inactive"] .text-2xl');
    const featuredCard = document.querySelector('[data-stat="featured"] .text-2xl');
    
    // Update cards directly without animation
    if (totalCard && stats.total !== undefined) {
        totalCard.textContent = stats.total;
    }
    if (activeCard && stats.active !== undefined) {
        activeCard.textContent = stats.active;
    }
    if (inactiveCard && stats.inactive !== undefined) {
        inactiveCard.textContent = stats.inactive;
    }
    if (featuredCard && stats.featured !== undefined) {
        featuredCard.textContent = stats.featured;
    }
};

</script>
@endpush
@endsection