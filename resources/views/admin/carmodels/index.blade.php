@extends('layouts.admin')

@section('title', 'Quản lý dòng xe')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-layer-group text-blue-600 mr-3"></i>
                    Quản lý dòng xe
                </h1>
                <p class="text-gray-600 mt-1">Quản lý tất cả dòng xe theo từng hãng</p>
            </div>
            <a href="{{ route('admin.carmodels.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Thêm dòng xe
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-layer-group text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng dòng xe</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalModels }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $activeModels }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $inactiveModels }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $featuredModels }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="new">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-sparkles text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Mới</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $newModels }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form id="search-form" method="GET" action="{{ route('admin.carmodels.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Tên dòng xe...">
            </div>

            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Hãng xe</label>
                <select name="brand" id="brand" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tất cả hãng</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Mới</option>
                    <option value="discontinued" {{ request('status') == 'discontinued' ? 'selected' : '' }}>Ngừng sản xuất</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>
                    Lọc
                </button>
            </div>
        </form>
    </div>

    {{-- Loading State --}}
    <div id="loading-state" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
        <div class="flex items-center justify-center">
            <i class="fas fa-spinner fa-spin text-blue-600 text-2xl mr-3"></i>
            <span class="text-gray-600">Đang tải dữ liệu...</span>
        </div>
    </div>

    {{-- Car Models Table --}}
    <div id="carmodels-content" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @include('admin.carmodels.partials.table', ['carModels' => $carModels])
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Xác nhận xóa dòng xe</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-3">
                    Bạn có chắc chắn muốn xóa dòng xe <strong id="delete-model-name"></strong> 
                    của hãng <strong id="delete-brand-name"></strong>?
                </p>
                <div id="delete-warning" class="hidden bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-3">
                    <p class="text-sm text-yellow-800"></p>
                </div>
                <p class="text-xs text-gray-400">
                    Hành động này không thể hoàn tác!
                </p>
            </div>
            <div class="flex items-center justify-center space-x-4 mt-4">
                <button onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Hủy
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Xóa
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let deleteFormId = null;

// AJAX Functions
function showLoading() {
    document.getElementById('carmodels-content').classList.add('hidden');
    document.getElementById('loading-state').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('carmodels-content').classList.remove('hidden');
}

function loadCarModels(url = null) {
    showLoading();
    
    const requestUrl = url || '{{ route("admin.carmodels.index") }}';
    
    fetch(requestUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('carmodels-content').innerHTML = html;
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

function initializeEventListeners() {
    // Delete buttons
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modelId = this.dataset.modelId;
            const modelName = this.dataset.modelName;
            const brandName = this.dataset.brandName;
            const variantsCount = parseInt(this.dataset.variantsCount) || 0;
            
            showDeleteModal(modelId, modelName, brandName, variantsCount);
        });
    });
    
    // Status toggle buttons
    const statusButtons = document.querySelectorAll('.status-toggle');
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modelId = this.dataset.modelId;
            const newStatus = this.dataset.status === 'true';
            
            toggleModelStatus(modelId, newStatus, this);
        });
    });
    
    // Pagination links
    const paginationLinks = document.querySelectorAll('.pagination-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            if (url) {
                loadCarModels(url);
            }
        });
    });
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners for existing content
    initializeEventListeners();
    
    // Search form AJAX
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            loadCarModels('{{ route("admin.carmodels.index") }}?' + new URLSearchParams(formData).toString());
        });
    }
    
    // Real-time search
    const searchInput = document.getElementById('search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const formData = new FormData(searchForm);
                loadCarModels('{{ route("admin.carmodels.index") }}?' + new URLSearchParams(formData).toString());
            }, 500);
        });
    }
    
    // Filter changes
    const brandSelect = document.getElementById('brand');
    const statusSelect = document.getElementById('status');
    
    if (brandSelect) {
        brandSelect.addEventListener('change', function() {
            const formData = new FormData(searchForm);
            loadCarModels('{{ route("admin.carmodels.index") }}?' + new URLSearchParams(formData).toString());
        });
    }
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const formData = new FormData(searchForm);
            loadCarModels('{{ route("admin.carmodels.index") }}?' + new URLSearchParams(formData).toString());
        });
    }
});

// Function to update stats cards
function updateStatsCards(stats) {
    // Update Total Models
    const totalElement = document.querySelector('[data-stat="total"] .text-2xl');
    if (totalElement) totalElement.textContent = stats.totalModels;
    
    // Update Active Models
    const activeElement = document.querySelector('[data-stat="active"] .text-2xl');
    if (activeElement) activeElement.textContent = stats.activeModels;
    
    // Update Inactive Models
    const inactiveElement = document.querySelector('[data-stat="inactive"] .text-2xl');
    if (inactiveElement) inactiveElement.textContent = stats.inactiveModels;
    
    // Update Featured Models
    const featuredElement = document.querySelector('[data-stat="featured"] .text-2xl');
    if (featuredElement) featuredElement.textContent = stats.featuredModels;
    
    // Update New Models
    const newElement = document.querySelector('[data-stat="new"] .text-2xl');
    if (newElement) newElement.textContent = stats.newModels;
}

// Status toggle function
function toggleModelStatus(modelId, newStatus, button) {
    fetch(`/admin/carmodels/${modelId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ is_active: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button appearance
            if (newStatus) {
                button.className = 'text-orange-600 hover:text-orange-900 status-toggle';
                button.title = 'Ngừng hoạt động';
                button.innerHTML = '<i class="fas fa-pause"></i>';
                button.dataset.status = 'false';
            } else {
                button.className = 'text-green-600 hover:text-green-900 status-toggle';
                button.title = 'Kích hoạt';
                button.innerHTML = '<i class="fas fa-play"></i>';
                button.dataset.status = 'true';
            }
            
            // Update stats cards
            if (data.stats) {
                updateStatsCards(data.stats);
            }
            
            // Update status badge in table row
            updateTableRowStatus(modelId, newStatus);
            
            // Show success message
            console.log(data.message);
        } else {
            alert('Có lỗi xảy ra khi cập nhật trạng thái');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái');
    });
}

// Function to update table row status badge
function updateTableRowStatus(modelId, isActive) {
    const button = document.querySelector(`[data-model-id="${modelId}"]`);
    if (button) {
        const row = button.closest('tr');
        if (row) {
            // Find the status badge (first span with inline-flex in the status column)
            const statusColumn = row.querySelector('td:nth-child(4)'); // Status column is 4th
            const statusBadge = statusColumn ? statusColumn.querySelector('span.inline-flex.items-center') : null;
            
            if (statusBadge) {
                if (isActive) {
                    statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Hoạt động';
                } else {
                    statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                    statusBadge.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Ngừng hoạt động';
                }
            }
        }
    }
}

// Delete modal functions
function showDeleteModal(modelId, modelName, brandName, variantsCount) {
    deleteFormId = `delete-form-${modelId}`;
    
    document.getElementById('delete-model-name').textContent = modelName;
    document.getElementById('delete-brand-name').textContent = brandName;
    
    const warningText = document.getElementById('delete-warning');
    if (variantsCount > 0) {
        warningText.innerHTML = `<strong>Cảnh báo:</strong> Dòng xe này có ${variantsCount} phiên bản. Việc xóa sẽ ảnh hưởng đến tất cả phiên bản liên quan.`;
        warningText.classList.remove('hidden');
    } else {
        warningText.classList.add('hidden');
    }
    
    document.getElementById('deleteModal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteFormId = null;
}

function confirmDelete() {
    if (deleteFormId) {
        document.getElementById(deleteFormId).submit();
    }
}
</script>
@endpush
@endsection
