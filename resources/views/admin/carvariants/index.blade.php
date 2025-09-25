@extends('layouts.admin')

@section('title', 'Quản lý phiên bản xe')

{{-- Session Flash Messages --}}
@if(session('success'))
    <meta name="flash-success" content="{{ session('success') }}">
@endif
@if(session('error'))
    <meta name="flash-error" content="{{ session('error') }}">
@endif
@if(session('warning'))
    <meta name="flash-warning" content="{{ session('warning') }}">
@endif
@if(session('info'))
    <meta name="flash-info" content="{{ session('info') }}">
@endif

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-cubes text-blue-600 mr-3"></i>
                    Quản lý phiên bản xe
                </h1>
                <p class="text-gray-600 mt-1">Quản lý tất cả phiên bản xe theo từng mẫu</p>
            </div>
            <a href="{{ route('admin.carvariants.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Thêm phiên bản xe
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="total">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-cubes text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng phiên bản</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalVariants }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="active">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hoạt động</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeVariants }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="inactive">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ngừng hoạt động</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $inactiveVariants }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="featured">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-star text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Nổi bật</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $featuredVariants }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="on_sale">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100">
                    <i class="fas fa-tags text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Khuyến mãi</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $onSaleVariants }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="new_arrival">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-certificate text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Mới về</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $newArrivalVariants }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form id="filterForm" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Tìm theo tên phiên bản, mẫu xe, hãng xe..." 
                           value="{{ request('search') }}">
                </div>
            </div>
            
            <div class="w-full md:w-48">
                <label for="car_model_id" class="block text-sm font-medium text-gray-700 mb-2">Mẫu xe</label>
                <select name="car_model_id" id="car_model_id" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tất cả mẫu xe</option>
                    @foreach($carModels as $model)
                        <option value="{{ $model->id }}" {{ request('car_model_id') == $model->id ? 'selected' : '' }}>
                            {{ $model->carBrand->name }} - {{ $model->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-40">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" id="status" 
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                    <option value="on_sale" {{ request('status') == 'on_sale' ? 'selected' : '' }}>Khuyến mãi</option>
                    <option value="new_arrival" {{ request('status') == 'new_arrival' ? 'selected' : '' }}>Mới về</option>
                </select>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Tìm kiếm
                </button>
                <a href="{{ route('admin.carvariants.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-undo mr-2"></i>
                    Đặt lại
                </a>
            </div>
        </form>
    </div>

    {{-- Loading State --}}
    <div id="loading-state" class="hidden">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-3 text-gray-600">Đang tải...</span>
            </div>
        </div>
    </div>

    {{-- Car Variants Table --}}
    <div id="carvariants-content" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @include('admin.carvariants.partials.table', ['carVariants' => $carVariants])
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="mb-5 text-lg font-normal text-gray-500">
                    Xác nhận xóa phiên bản xe
                </h3>
                <p class="text-sm text-gray-500 mb-3">
                    Bạn có chắc chắn muốn xóa phiên bản xe 
                    <strong id="delete-variant-name"></strong> 
                    của mẫu <strong id="delete-model-name"></strong>?
                </p>
                
                {{-- Impact Analysis --}}
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Phân tích tác động:
                    </h4>
                    <div class="text-xs text-yellow-700 space-y-1">
                        <div>• <span id="colorsCount">0</span> màu sắc sẽ bị xóa</div>
                        <div>• <span id="imagesCount">0</span> hình ảnh sẽ bị xóa</div>
                        <div class="text-red-600 font-medium mt-2">
                            <i class="fas fa-warning mr-1"></i>
                            Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn!
                        </div>
                    </div>
                </div>
                
                <p class="text-xs text-red-500 mt-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    Hành động này không thể hoàn tác!
                </p>
                
                <div class="flex space-x-3 mt-6">
                    <button id="cancelDelete" class="flex-1 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>Hủy
                    </button>
                    <button id="confirmDelete" class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Xóa
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let deleteFormId = null;

// Handle Laravel session flash messages from meta tags
document.addEventListener('DOMContentLoaded', function() {
    const flashSuccess = document.querySelector('meta[name="flash-success"]');
    if (flashSuccess) {
        showMessage(flashSuccess.getAttribute('content').replace(/[✅❌⚠️ℹ️]/g, '').trim(), 'success');
    }
    
    const flashError = document.querySelector('meta[name="flash-error"]');
    if (flashError) {
        showMessage(flashError.getAttribute('content').replace(/[✅❌⚠️ℹ️]/g, '').trim(), 'error');
    }
    
    const flashWarning = document.querySelector('meta[name="flash-warning"]');
    if (flashWarning) {
        showMessage(flashWarning.getAttribute('content').replace(/[✅❌⚠️ℹ️]/g, '').trim(), 'warning');
    }
    
    const flashInfo = document.querySelector('meta[name="flash-info"]');
    if (flashInfo) {
        showMessage(flashInfo.getAttribute('content').replace(/[✅❌⚠️ℹ️]/g, '').trim(), 'info');
    }
});

// AJAX Functions
function showLoading() {
    document.getElementById('carvariants-content').classList.add('hidden');
    document.getElementById('loading-state').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('carvariants-content').classList.remove('hidden');
}

function loadCarVariants(url = null, formData = null) {
    showLoading();
    
    const requestUrl = url || '{{ route("admin.carvariants.index") }}';
    const options = {
        method: formData ? 'POST' : 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    };
    
    if (formData) {
        options.body = formData;
    }
    
    fetch(requestUrl, options)
        .then(response => response.text())
        .then(html => {
            document.getElementById('carvariants-content').innerHTML = html;
            hideLoading();
            
            // Re-initialize event listeners for new content
            initializeEventListeners();
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
            showMessage('Có lỗi xảy ra khi tải dữ liệu', 'error');
        });
}

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
            
            showDeleteModal(variantId, variantName, modelName, colorsCount, imagesCount);
        });
    });
    
    // Pagination links
    document.querySelectorAll('#carvariants-content .pagination a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            if (url) {
                loadCarVariants(url);
            }
        });
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
    
    // Search form
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            loadCarVariants('{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString());
        });
    }
    
    // Real-time search
    const searchInput = document.getElementById('search');
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const formData = new FormData(searchForm);
                loadCarVariants('{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString());
            }, 500);
        });
    }
    
    // Status filter
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const formData = new FormData(searchForm);
            loadCarVariants('{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString());
        });
    }
    
    // Model filter
    const modelSelect = document.getElementById('car_model_id');
    if (modelSelect) {
        modelSelect.addEventListener('change', function() {
            const formData = new FormData(searchForm);
            loadCarVariants('{{ route("admin.carvariants.index") }}?' + new URLSearchParams(formData).toString());
        });
    }
});

// Event listeners for modal buttons
document.getElementById('confirmDelete').addEventListener('click', function() {
    confirmDelete();
});

document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteFormId = null;
});

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
        deleteFormId = null;
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('deleteModal').classList.add('hidden');
        deleteFormId = null;
    }
});

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

// Status toggle functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.status-toggle')) {
        const button = e.target.closest('.status-toggle');
        const variantId = button.dataset.variantId;
        const newStatus = button.dataset.status === 'true';
        
        toggleVariantStatus(variantId, newStatus, button);
    }
});

function toggleVariantStatus(variantId, newStatus, button) {
    // Add loading state with fixed width to prevent layout shift
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin w-4 h-4 inline-block"></i>';
    button.disabled = true;
    
    // Make AJAX request
    fetch(`/admin/carvariants/${variantId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            is_active: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button appearance with fixed dimensions
            if (newStatus) {
                button.className = 'text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center';
                button.title = 'Tạm dừng';
                button.innerHTML = '<i class="fas fa-pause w-4 h-4"></i>';
                button.dataset.status = 'false';
            } else {
                button.className = 'text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center';
                button.title = 'Kích hoạt';
                button.innerHTML = '<i class="fas fa-play w-4 h-4"></i>';
                button.dataset.status = 'true';
            }
            
            // Update stats if provided
            if (data.stats) {
                updateStatsCards(data.stats);
            }
            
            // Update row status badge
            updateVariantRowStatus(variantId, newStatus);
            
            // Show success message
            showMessage(data.message, 'success');
        } else {
            // Restore original state on error
            button.innerHTML = originalHTML;
            showMessage('Có lỗi xảy ra: ' + data.message, 'error');
        }
    })
    .catch(error => {
        // Restore original state on error
        button.innerHTML = originalHTML;
        showMessage('Có lỗi xảy ra khi cập nhật trạng thái', 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        button.disabled = false;
    });
}

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