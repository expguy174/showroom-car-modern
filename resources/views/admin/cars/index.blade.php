@extends('layouts.admin')

@section('title', 'Quản lý hãng xe')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-industry text-blue-600 mr-3"></i>
                    Quản lý hãng xe
                </h1>
                <p class="text-gray-600 mt-1">Quản lý tất cả thương hiệu xe hơi trong hệ thống</p>
            </div>
            <a href="{{ route('admin.cars.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Thêm hãng xe
            </a>
        </div>
    </div>


    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="total">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-industry text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng hãng xe</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalCars }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="active">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Đang hoạt động</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $activeCars }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ $featuredCars }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6" data-stat="inactive">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100">
                    <i class="fas fa-pause-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ngừng hoạt động</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $inactiveCars }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        {{-- Loading Bar --}}
        <div id="filter-loading" class="hidden mb-4">
            <div class="w-full bg-gray-200 rounded-full h-1">
                <div class="bg-blue-600 h-1 rounded-full animate-pulse" style="width: 100%"></div>
            </div>
        </div>
        
        <form id="search-form" method="GET" action="{{ route('admin.cars.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                       placeholder="Tên hãng xe, quốc gia...">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" id="status" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                    <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
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
    <div id="loading-state" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 py-20 text-center">
        <div class="flex flex-col items-center justify-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-4"></div>
            <span class="text-gray-600">Đang tải dữ liệu...</span>
        </div>
    </div>

    {{-- Car Brands Table --}}
    <div id="cars-content" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @include('admin.cars.partials.table', ['cars' => $cars])
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center">
    <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Xác nhận xóa hãng xe</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Bạn có chắc chắn muốn xóa hãng xe <span id="brandName" class="font-semibold text-gray-900"></span>?
                </p>
                
                <!-- Impact Analysis -->
                <div id="impactAnalysis" class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Phân tích tác động:
                    </h4>
                    <div class="text-xs text-yellow-700 space-y-1">
                        <div>• <span id="modelsCount">0</span> dòng xe sẽ bị xóa</div>
                        <div>• <span id="variantsCount">0</span> phiên bản xe sẽ bị xóa</div>
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
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex space-x-3">
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

// AJAX Functions
function showLoading() {
    document.getElementById('cars-content').classList.add('hidden');
    document.getElementById('loading-state').classList.remove('hidden');
    document.getElementById('filter-loading').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-state').classList.add('hidden');
    document.getElementById('filter-loading').classList.add('hidden');
    document.getElementById('cars-content').classList.remove('hidden');
}

function loadCars(url = null, formData = null) {
    showLoading();
    
    const requestUrl = url || '{{ route("admin.cars.index") }}';
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
            document.getElementById('cars-content').innerHTML = html;
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
            const carId = this.dataset.carId;
            const carName = this.dataset.carName;
            const modelsCount = parseInt(this.dataset.modelsCount) || 0;
            const variantsCount = parseInt(this.dataset.variantsCount) || 0;
            
            showDeleteModal(carId, carName, modelsCount, variantsCount);
        });
    });
    
    // Status toggle buttons
    const statusButtons = document.querySelectorAll('.status-toggle');
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const carId = this.dataset.carId;
            const newStatus = this.dataset.status === 'true';
            
            toggleCarStatus(carId, newStatus, this);
        });
    });
    
    // Pagination links
    const paginationLinks = document.querySelectorAll('.pagination-link');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            if (url) {
                loadCars(url);
            }
        });
    });
}

// Add event listeners to all buttons
document.addEventListener('DOMContentLoaded', function() {
    // Initialize event listeners for existing content
    initializeEventListeners();
    
    // Search form AJAX
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            loadCars('{{ route("admin.cars.index") }}?' + new URLSearchParams(formData).toString());
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
                loadCars('{{ route("admin.cars.index") }}?' + new URLSearchParams(formData).toString());
            }, 500);
        });
    }
    
    // Status filter change
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const formData = new FormData(searchForm);
            loadCars('{{ route("admin.cars.index") }}?' + new URLSearchParams(formData).toString());
        });
    }
    // Delete buttons
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const carId = this.dataset.carId;
            const carName = this.dataset.carName;
            const modelsCount = parseInt(this.dataset.modelsCount) || 0;
            const variantsCount = parseInt(this.dataset.variantsCount) || 0;
            
            showDeleteModal(carId, carName, modelsCount, variantsCount);
        });
    });
    
    // Status toggle buttons
    const statusButtons = document.querySelectorAll('.status-toggle');
    statusButtons.forEach(button => {
        button.addEventListener('click', function() {
            const carId = this.dataset.carId;
            const newStatus = this.dataset.status === 'true';
            
            toggleCarStatus(carId, newStatus, this);
        });
    });
});

function showDeleteModal(carId, carName, modelsCount, variantsCount) {
    deleteFormId = 'delete-form-' + carId;
    
    // Update modal content
    document.getElementById('brandName').textContent = carName;
    document.getElementById('modelsCount').textContent = modelsCount;
    document.getElementById('variantsCount').textContent = variantsCount;
    
    // Show/hide impact analysis based on data
    const impactAnalysis = document.getElementById('impactAnalysis');
    if (modelsCount > 0 || variantsCount > 0) {
        impactAnalysis.classList.remove('hidden');
        
        // Update warning color based on impact level
        if (modelsCount > 5 || variantsCount > 10) {
            impactAnalysis.className = 'mt-3 p-3 bg-red-50 border border-red-200 rounded-md';
            impactAnalysis.querySelector('h4').className = 'text-sm font-medium text-red-800 mb-2';
        } else {
            impactAnalysis.className = 'mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-md';
            impactAnalysis.querySelector('h4').className = 'text-sm font-medium text-yellow-800 mb-2';
        }
    } else {
        impactAnalysis.classList.add('hidden');
    }
    
    // Show modal
    document.getElementById('deleteModal').classList.remove('hidden');
}

function toggleCarStatus(carId, newStatus, button) {
    // Add loading state
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    // Make AJAX request
    fetch(`/admin/cars/${carId}/toggle-status`, {
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
            updateTableRowStatus(carId, newStatus);
            
            // Show success toast
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

// Function to update stats cards
function updateStatsCards(stats) {
    // Update Total Cars
    const totalElement = document.querySelector('[data-stat="total"] .text-2xl');
    if (totalElement) totalElement.textContent = stats.totalCars;
    
    // Update Active Cars
    const activeElement = document.querySelector('[data-stat="active"] .text-2xl');
    if (activeElement) activeElement.textContent = stats.activeCars;
    
    // Update Inactive Cars
    const inactiveElement = document.querySelector('[data-stat="inactive"] .text-2xl');
    if (inactiveElement) inactiveElement.textContent = stats.inactiveCars;
    
    // Update Featured Cars
    const featuredElement = document.querySelector('[data-stat="featured"] .text-2xl');
    if (featuredElement) featuredElement.textContent = stats.featuredCars;
}

// Function to update table row status badge
function updateTableRowStatus(carId, isActive) {
    const button = document.querySelector(`[data-car-id="${carId}"]`);
    if (button) {
        const row = button.closest('tr');
        if (row) {
            // Find the status badge (first span with inline-flex in the status column)
            const statusColumn = row.querySelector('td:nth-child(3)'); // Status column is 3rd
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

document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteModal').classList.add('hidden');
    deleteFormId = null;
});

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (deleteFormId) {
        // Add loading state
        const confirmBtn = this;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang xóa...';
        confirmBtn.disabled = true;
        
        // Get form and extract car ID
        const form = document.getElementById(deleteFormId);
        const carId = deleteFormId.replace('delete-form-', '');
        
        // Send AJAX request
        fetch(form.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
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
                
                // Reload the cars table
                setTimeout(() => {
                    loadCars(window.location.href);
                }, 1000);
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Show error toast
            if (error.data && error.data.message) {
                showMessage(error.data.message, 'error');
            } else {
                showMessage(error.message || 'Có lỗi xảy ra khi xóa hãng xe', 'error');
            }
        })
        .finally(() => {
            // Reset button state
            confirmBtn.innerHTML = 'Xác nhận xóa';
            confirmBtn.disabled = false;
            deleteFormId = null;
        });
    }
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
</script>
@endpush
@endsection