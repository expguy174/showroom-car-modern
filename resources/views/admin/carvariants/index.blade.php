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
                    callback-name="loadCarVariants"
                    :debounce-time="500"
                    size="small"
                    :show-icon="true"
                    :show-clear-button="true" />
            </div>
            
            {{-- Dòng xe --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dòng xe</label>
                <x-admin.custom-dropdown 
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
            
            {{-- Trạng thái --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <x-admin.custom-dropdown 
                    name="status"
                    :options="collect([
                        ['value' => 'active', 'label' => 'Hoạt động'],
                        ['value' => 'inactive', 'label' => 'Tạm dừng'],
                        ['value' => 'featured', 'label' => 'Nổi bật'],
                        ['value' => 'on_sale', 'label' => 'Khuyến mãi'],
                        ['value' => 'new_arrival', 'label' => 'Mới về'],
                        ['value' => 'bestseller', 'label' => 'Bán chạy']
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
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    form-id="#filterForm" 
                    callback-name="loadCarVariants" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="carvariants-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.carvariants.index') }}"
        callback-name="loadCarVariants">
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
                if (window.loadCarVariants) {
                    window.loadCarVariants(url);
                }
            }, 500);
        });
    }
    
    // Callback for custom dropdown components
    window.loadCarVariantsFromDropdown = function() {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString();
        if (window.loadCarVariants) {
            window.loadCarVariants(url);
        }
    };
    
    // Function to update stats cards when toggle status changes
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
    
    // Update Bestseller Variants
    const bestsellerElement = document.querySelector('[data-stat="bestseller"] .text-2xl');
    if (bestsellerElement) bestsellerElement.textContent = stats.bestsellerVariants;
}

// Duplicate event listener removed - using the one in initializeEventListeners() instead

// Function to update table row status badge
function updateVariantRowStatus(variantId, isActive) {
    const button = document.querySelector(`[data-variant-id="${variantId}"]`);
    if (button) {
        const row = button.closest('tr');
        if (row) {
            // Find the status badge (first span with inline-flex in the status column)
            const statusColumn = row.querySelector('td:nth-child(4)'); // Status column is 4th
            const statusBadge = statusColumn ? statusColumn.querySelector('span.inline-flex.items-center') : null;
            
            if (statusBadge) {
                if (isActive) {
                    statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-full bg-green-100 text-green-800';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i><span class="truncate">Hoạt động</span>';
                } else {
                    statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-full bg-red-100 text-red-800';
                    statusBadge.innerHTML = '<i class="fas fa-times-circle mr-1"></i><span class="truncate">Tạm dừng</span>';
                }
            }
        }
    }
}

// Delete modal functions
function showDeleteModal(variantId, variantName, modelName, colorsCount, imagesCount) {
    deleteFormId = `delete-form-${variantId}`;
    
    document.getElementById('delete-variant-name').textContent = variantName;
    document.getElementById('delete-model-name').textContent = modelName;
    
    // Update impact analysis
    document.getElementById('colorsCount').textContent = colorsCount;
    document.getElementById('imagesCount').textContent = imagesCount;
    
    document.getElementById('deleteModal').classList.remove('hidden');
}

function confirmDelete() {
    if (deleteFormId) {
        const confirmBtn = document.getElementById('confirmDelete');
        
        // Show loading state
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...';
        confirmBtn.disabled = true;
        
        // Get form and extract variant ID
        const form = document.getElementById(deleteFormId);
        const formAction = form.getAttribute('action');
        
        // Make AJAX request
        fetch(formAction, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                _method: 'DELETE'
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw { status: response.status, data: data };
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success toast
                showMessage(data.message, 'success');
                
                // Hide modal
                document.getElementById('deleteModal').classList.add('hidden');
                
                // Update stats if provided
                if (data.stats) {
                    updateStatsCards(data.stats);
                }
                
                // Remove the deleted row from table
                const variantId = deleteFormId.replace('delete-form-', '');
                const rowToRemove = document.querySelector(`[data-variant-id="${variantId}"]`).closest('tr');
                if (rowToRemove) {
                    rowToRemove.remove();
                }
                
                // Reset deleteFormId only on success
                deleteFormId = null;
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            
            // Show error toast
            if (error.data && error.data.message) {
                showMessage(error.data.message, 'error');
            } else {
                showMessage(error.message || 'Có lỗi xảy ra khi xóa phiên bản xe', 'error');
            }
            
            // Keep modal open for error cases - user can retry or cancel
            // Don't hide modal on error, let user decide
        })
        .finally(() => {
            // Reset button state
            confirmBtn.innerHTML = '<i class="fas fa-trash mr-2"></i>Xóa';
            confirmBtn.disabled = false;
            // Don't reset deleteFormId here - only reset on success or modal close
        });
    }
}
</script>
@endpush
@endsection