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
<x-flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000" />
<div class="space-y-4 sm:space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="min-w-0 flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-cubes text-blue-600 mr-2 sm:mr-3 text-lg sm:text-xl"></i>
                    <span class="truncate">Quản lý phiên bản xe</span>
                </h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Quản lý tất cả phiên bản xe theo từng mẫu</p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('admin.carvariants.create') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="sm:hidden">Thêm mới</span>
                    <span class="hidden sm:inline">Thêm phiên bản xe</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards Components --}}
    <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 lg:gap-6">
        <x-stats-card 
            title="Tổng phiên bản"
            :value="$totalVariants"
            icon="fas fa-cubes"
            color="blue"
            click-action="filterAll"
            description="Tất cả phiên bản xe" />
            
        <x-stats-card 
            title="Hoạt động"
            :value="$activeVariants"
            icon="fas fa-check-circle"
            color="green"
            click-action="filterActive"
            description="Đang kinh doanh" />
            
        <x-stats-card 
            title="Ngừng hoạt động"
            :value="$inactiveVariants"
            icon="fas fa-times-circle"
            color="red"
            click-action="filterInactive"
            description="Tạm ngừng bán" />
            
        <x-stats-card 
            title="Nổi bật"
            :value="$featuredVariants"
            icon="fas fa-star"
            color="yellow"
            click-action="filterFeatured"
            description="Được đánh dấu nổi bật" />
            
        <x-stats-card 
            title="Khuyến mãi"
            :value="$onSaleVariants"
            icon="fas fa-tags"
            color="purple"
            click-action="filterOnSale"
            description="Đang có ưu đãi" />
            
        <x-stats-card 
            title="Mới về"
            :value="$newArrivalVariants"
            icon="fas fa-certificate"
            color="gray"
            click-action="filterNewArrival"
            description="Sản phẩm mới nhất" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
        <form id="filterForm" class="space-y-4 md:space-y-0 md:flex md:flex-wrap md:gap-4 md:items-end">
            <div class="flex-1 min-w-0 md:min-w-64 lg:min-w-80">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="search"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Tìm kiếm phiên bản xe..."
                           value="{{ request('search') }}"
                           autocomplete="off">
                </div>
            </div>
            
            <div class="w-full sm:w-auto sm:min-w-48 md:w-48">
                <label for="car_model_id" class="block text-sm font-medium text-gray-700 mb-2">Dòng xe</label>
                <x-custom-dropdown 
                    name="car_model_id"
                    :options="$carModels"
                    placeholder="Tất cả"
                    option-value="id"
                    option-text="name"
                    option-subtext="carBrand.name"
                    :selected="request('car_model_id')"
                    onchange="loadCarVariantsFromDropdown"
                    :max-visible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            <div class="w-full sm:w-auto sm:min-w-40 md:w-40">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <x-custom-dropdown 
                    name="status"
                    :options="collect([
                        ['value' => 'active', 'label' => 'Hoạt động'],
                        ['value' => 'inactive', 'label' => 'Tạm dừng'],
                        ['value' => 'featured', 'label' => 'Nổi bật'],
                        ['value' => 'on_sale', 'label' => 'Khuyến mãi'],
                        ['value' => 'new_arrival', 'label' => 'Mới về']
                    ])"
                    placeholder="Tất cả"
                    option-value="value"
                    option-text="label"
                    :selected="request('status')"
                    onchange="loadCarVariantsFromDropdown"
                    :max-visible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            <div class="w-full sm:w-auto md:w-auto">
                <x-reset-button 
                    form-id="#filterForm" 
                    callback-name="loadCarVariants" />
            </div>
        </form>
    </div>

    {{-- Loading State --}}
    <div id="loading-state" class="hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
            <div class="flex flex-col items-center">
                <p class="text-gray-600 text-base sm:text-lg">Đang tải dữ liệu...</p>
            </div>
        </div>
    </div>

    {{-- AJAX Table Component --}}
    <x-ajax-table 
        table-id="carvariants-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.carvariants.index') }}"
        callback-name="loadCarVariants">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @include('admin.carvariants.partials.table')
        </div>
    </x-ajax-table>
</div>

{{-- Delete Modal Component --}}
<x-delete-modal 
    modal-id="deleteModal"
    title="Xác nhận xóa phiên bản xe"
    entity-name="phiên bản xe"
    warning-text="Bạn có chắc chắn muốn xóa"
    delete-callback-name="confirmDelete" />

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
            const variantId = this.dataset.variantId;
            const variantName = this.dataset.variantName;
            const modelName = this.dataset.modelName;
            const colorsCount = parseInt(this.dataset.colorsCount) || 0;
            const imagesCount = parseInt(this.dataset.imagesCount) || 0;
            
            // Use component's show function
            showDeleteModal({
                variantId: variantId,
                entityName: `${variantName} (${modelName})`,
                details: `
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                        <h4 class="text-sm font-medium text-yellow-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Phân tích tác động:
                        </h4>
                        <div class="text-xs text-yellow-700 space-y-1">
                            <div>• ${colorsCount} màu sắc sẽ bị xóa</div>
                            <div>• ${imagesCount} hình ảnh sẽ bị xóa</div>
                        </div>
                    </div>
                `,
                warnings: `
                    <div class="text-red-600 font-medium text-sm">
                        <i class="fas fa-warning mr-1"></i>
                        Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn!
                    </div>
                `,
                deleteUrl: `{{ url('admin/carvariants/delete') }}/${variantId}`
            });
        });
    });
    
    // Simple Status toggle buttons
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const variantId = this.dataset.variantId;
            const newStatus = this.dataset.status === 'true';
            const buttonElement = this;
            
            // Show loading state
            const originalIcon = buttonElement.querySelector('i').className;
            buttonElement.querySelector('i').className = 'fas fa-spinner fa-spin w-4 h-4';
            buttonElement.disabled = true;
            
            try {
                const response = await fetch(`{{ url('admin/carvariants') }}/${variantId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ is_active: newStatus })
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
                    
                    // Update status badges using component function
                    if (window.updateStatusBadge) {
                        window.updateStatusBadge(variantId, newStatus);
                    }
                    
                    // Show success flash message
                    if (window.showMessage) {
                        const statusText = newStatus ? 'hoạt động' : 'tạm dừng';
                        window.showMessage(`Đã cập nhật trạng thái thành ${statusText}`, 'success');
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
    
    // Pagination is now handled by AjaxTable component
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    
    // Real-time search
    const searchForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    if (searchInput && searchForm) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const formData = new FormData(searchForm);
                const url = '{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString();
                // Use the function created by AjaxTable component
                if (window.loadCarVariants) {
                    window.loadCarVariants(url);
                }
            }, 500);
        });
    }
    
    // Reset button is now handled by ResetButton component
    
    // Callback for custom dropdown components
    window.loadCarVariantsFromDropdown = function() {
        console.log('Dropdown callback triggered');
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString();
        console.log('Generated URL:', url);
        // Use the function created by AjaxTable component
        if (window.loadCarVariants) {
            console.log('Calling loadCarVariants');
            window.loadCarVariants(url);
        } else {
            console.error('loadCarVariants function not available');
        }
    };
    
    // Callback for reset button (will be overridden by AjaxTable component, but define as fallback)
    if (!window.loadCarVariants) {
        window.loadCarVariants = function(url) {
            console.log('AjaxTable component not ready yet, will be overridden');
        };
    }
    
    // Stats card click handlers
    window.filterAll = function() {
        console.log('Filter: All variants');
        if (window.loadCarVariants) {
            window.loadCarVariants('{{ route("admin.carvariants.index") }}');
        }
    };
    
    window.filterActive = function() {
        console.log('Filter: Active variants');
        if (window.loadCarVariants) {
            window.loadCarVariants('{{ route("admin.carvariants.index") }}?status=active');
        }
    };
    
    window.filterInactive = function() {
        console.log('Filter: Inactive variants');
        if (window.loadCarVariants) {
            window.loadCarVariants('{{ route("admin.carvariants.index") }}?status=inactive');
        }
    };
    
    window.filterFeatured = function() {
        console.log('Filter: Featured variants');
        if (window.loadCarVariants) {
            window.loadCarVariants('{{ route("admin.carvariants.index") }}?status=featured');
        }
    };
    
    window.filterOnSale = function() {
        console.log('Filter: On sale variants');
        if (window.loadCarVariants) {
            window.loadCarVariants('{{ route("admin.carvariants.index") }}?status=on_sale');
        }
    };
    
    window.filterNewArrival = function() {
        console.log('Filter: New arrival variants');
        if (window.loadCarVariants) {
            window.loadCarVariants('{{ route("admin.carvariants.index") }}?status=new_arrival');
        }
    };
});

// Delete confirmation function for DeleteModal component
window.confirmDelete = function(data) {
    if (!data || !data.deleteUrl) return;
    
    const deleteUrl = data.deleteUrl;
    
    fetch(deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(data.message.replace(/[✅❌⚠️ℹ️]/g, '').trim(), 'success');
            
            // Reload table using component's function
            if (window.loadCarVariants) {
                loadCarVariants();
            }
            
            // Hide modal
            document.getElementById('deleteModal').classList.add('hidden');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage(error.message || 'Có lỗi xảy ra khi xóa', 'error');
    });
};

// Function to update stats cards
function updateStatsCards(stats) {
    // Update Total Variants
    const totalElement = document.querySelector('[data-stat="total"] .text-2xl');
    if (totalElement) totalElement.textContent = stats.totalVariants;
    
    // Update Active Variants
    const activeElement = document.querySelector('[data-stat="active"] .text-2xl');
    if (activeElement) activeElement.textContent = stats.activeVariants;
    
    // Update Inactive Variants
    const inactiveElement = document.querySelector('[data-stat="inactive"] .text-2xl');
    if (inactiveElement) inactiveElement.textContent = stats.inactiveVariants;
    
    // Update Featured Variants
    const featuredElement = document.querySelector('[data-stat="featured"] .text-2xl');
    if (featuredElement) featuredElement.textContent = stats.featuredVariants;
    
    // Update On Sale Variants
    const onSaleElement = document.querySelector('[data-stat="on_sale"] .text-2xl');
    if (onSaleElement) onSaleElement.textContent = stats.onSaleVariants;
    
    // Update New Arrival Variants
    const newArrivalElement = document.querySelector('[data-stat="new_arrival"] .text-2xl');
    if (newArrivalElement) newArrivalElement.textContent = stats.newArrivalVariants;
}

// Duplicate event listener removed - using the one in initializeEventListeners() instead

// Old updateVariantRowStatus function removed - now using StatusToggle component

// Delete modal functions now handled by DeleteModal component

// Old confirmDelete function removed - now using DeleteModal component
</script>
@endpush