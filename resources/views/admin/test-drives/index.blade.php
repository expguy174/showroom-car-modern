@extends('layouts.admin')

@section('title', 'Quản lý lái thử')

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
        title="Quản lý lái thử"
        description="Danh sách tất cả yêu cầu lái thử xe"
        icon="fas fa-car">
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-4">
        <x-admin.stats-card 
            title="Tổng lái thử"
            :value="$totalTestDrives ?? 0"
            icon="fas fa-car"
            color="gray"
            description="Tất cả lái thử"
            dataStat="total" />
        
        <x-admin.stats-card 
            title="Đã đặt lịch"
            :value="$pendingTestDrives ?? 0"
            icon="fas fa-clock"
            color="yellow"
            description="Chờ xác nhận"
            dataStat="pending" />
        
        <x-admin.stats-card 
            title="Đã xác nhận"
            :value="$confirmedTestDrives ?? 0"
            icon="fas fa-check-circle"
            color="green"
            description="Đã xác nhận"
            dataStat="confirmed" />
        
        <x-admin.stats-card 
            title="Hoàn thành"
            :value="$completedTestDrives ?? 0"
            icon="fas fa-flag-checkered"
            color="blue"
            description="Đã hoàn thành"
            dataStat="completed" />
        
        <x-admin.stats-card 
            title="Đã hủy"
            :value="$cancelledTestDrives ?? 0"
            icon="fas fa-times-circle"
            color="red"
            description="Đã hủy"
            dataStat="cancelled" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.test-drives.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tên, email, tên xe..."
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
                        ['value' => 'scheduled', 'text' => 'Đã đặt lịch'],
                        ['value' => 'confirmed', 'text' => 'Đã xác nhận'],
                        ['value' => 'completed', 'text' => 'Hoàn thành'],
                        ['value' => 'cancelled', 'text' => 'Đã hủy']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="text"
                    :selected="request('status')"
                    onchange="loadTestDrivesFromDropdown"
                    :maxVisible="5"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Showroom Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Showroom</label>
                <x-admin.custom-dropdown 
                    name="showroom_id"
                    :options="$showrooms ?? []"
                    placeholder="Tất cả"
                    optionValue="id"
                    optionText="name"
                    :selected="request('showroom_id')"
                    onchange="loadTestDrivesFromDropdown"
                    :maxVisible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadTestDrivesWithStats" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="test-drives-content"
        loading-id="loading-state"
        empty-message="Không có lịch lái thử nào"
        empty-icon="fas fa-car">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            @include('admin.test-drives.partials.table')
        </div>
    </x-admin.ajax-table>
</div>

@push('scripts')
<script>
let currentStatus = '';

// Update stats cards from server response
window.updateStatsFromServer = function(stats) {
    const statsMapping = {
        'total': 'total',
        'pending': 'pending',  // Map scheduled from server to pending dataStat
        'confirmed': 'confirmed',
        'completed': 'completed',
        'cancelled': 'cancelled'
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

// Functions
function loadTestDrivesFromDropdown() {
    loadTestDrivesWithStats();
}

function handleSearch() {
    loadTestDrivesWithStats();
}

function loadTestDrivesWithStats() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    params.append('ajax', '1');
    params.append('with_stats', '1');
    
    // Show loading state
    const loadingContainer = document.getElementById('loading-state');
    const tableContainer = document.getElementById('test-drives-content');
    if (tableContainer) tableContainer.style.display = 'none';
    if (loadingContainer) loadingContainer.classList.remove('hidden');
    
    fetch(`${form.dataset.baseUrl}?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update table
        document.getElementById('test-drives-content').innerHTML = data.html;
        
        // Update stats
        if (data.stats) {
            updateStatsCards(data.stats);
        }
        
        // Hide loading state
        if (loadingContainer) loadingContainer.classList.add('hidden');
        if (tableContainer) tableContainer.style.display = 'block';
        
        // Re-attach event listeners
        initializeEventListeners();
        
        // Re-initialize pagination
        if (window.ajaxTableManager_testdrivescontent) {
            window.ajaxTableManager_testdrivescontent.initializePagination();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Hide loading state on error
        if (loadingContainer) loadingContainer.classList.add('hidden');
        if (tableContainer) tableContainer.style.display = 'block';
    });
}

function updateStatsCards(stats) {
    const cards = {
        'total': stats.total || 0,
        'pending': stats.pending || 0,
        'confirmed': stats.confirmed || 0,
        'completed': stats.completed || 0,
        'cancelled': stats.cancelled || 0
    };
    
    Object.entries(cards).forEach(([key, value]) => {
        const element = document.querySelector(`[data-stat="${key}"]`);
        if (element) {
            const valueElement = element.querySelector('[data-stat-value]');
            if (valueElement) {
                valueElement.textContent = value;
            }
        }
    });
}

// Update test drive status badge and action buttons without reload
function updateTestDriveStatus(testDriveId, newStatus) {
    const row = document.querySelector(`tr[data-test-drive-id="${testDriveId}"]`);
    if (!row) return;
    
    const statusCell = row.querySelector('.status-cell');
    const actionsCell = row.querySelector('.actions-cell');
    
    // Extract data from row for buttons
    const customerName = row.querySelector('td:first-child .font-medium')?.textContent?.trim() || '';
    const carVariantName = row.querySelector('td:nth-child(2) .font-medium')?.textContent?.trim() || '';
    const carBrandModel = row.querySelector('td:nth-child(2) .text-gray-700')?.textContent?.replace(/\s+/g, ' ').trim() || '';
    // Combine: Brand Model + Variant (e.g., "Suzuki XL7 - XL7 AT")
    const carInfo = carBrandModel ? `${carBrandModel} - ${carVariantName}` : carVariantName;
    const dateInfo = row.querySelector('td:nth-child(3) .text-sm')?.textContent?.trim() || '';
    
    // Update status badge
    if (statusCell) {
        const statusBadges = {
            'scheduled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Đã đặt lịch</span>',
            'confirmed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Đã xác nhận</span>',
            'completed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fas fa-flag-checkered mr-1"></i>Hoàn thành</span>',
            'cancelled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Đã hủy</span>'
        };
        statusCell.innerHTML = statusBadges[newStatus] || statusCell.innerHTML;
    }
    
    // Update action buttons based on new status
    if (actionsCell) {
        const buttonsHtml = [];
        
        // Start wrapper div
        buttonsHtml.push('<div class="flex items-center justify-center gap-2">');
        
        // View button (always visible)
        buttonsHtml.push(`
            <a href="/admin/test-drives/${testDriveId}" 
               class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50"
               title="Xem chi tiết">
                <i class="fas fa-eye w-4 h-4"></i>
            </a>
        `);
        
        if (newStatus === 'scheduled') {
            // Confirm button for scheduled
            buttonsHtml.push(`
                <button type="button"
                        class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 confirm-test-drive-btn"
                        data-test-drive-id="${testDriveId}"
                        data-customer-name="${customerName}"
                        title="Xác nhận">
                    <i class="fas fa-check w-4 h-4"></i>
                </button>
            `);
        }
        
        if (newStatus === 'confirmed') {
            // Complete button for confirmed
            buttonsHtml.push(`
                <button type="button"
                        class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50 complete-test-drive-btn"
                        data-test-drive-id="${testDriveId}"
                        title="Hoàn thành">
                    <i class="fas fa-check-double w-4 h-4"></i>
                </button>
            `);
        }
        
        if (newStatus === 'scheduled' || newStatus === 'confirmed') {
            // Cancel button for scheduled/confirmed
            buttonsHtml.push(`
                <button type="button"
                        class="text-orange-600 hover:text-orange-900 transition-colors p-1 rounded hover:bg-orange-50 cancel-test-drive-btn"
                        data-test-drive-id="${testDriveId}"
                        data-customer-name="${customerName}"
                        data-car="${carInfo}"
                        data-date="${dateInfo}"
                        title="Hủy">
                    <i class="fas fa-times w-4 h-4"></i>
                </button>
            `);
        }
        
        // Delete button (always visible)
        buttonsHtml.push(`
            <button type="button"
                    class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50 delete-btn"
                    data-test-drive-id="${testDriveId}"
                    title="Xóa">
                <i class="fas fa-trash w-4 h-4"></i>
            </button>
        `);
        
        // Close wrapper div
        buttonsHtml.push('</div>');
        
        actionsCell.innerHTML = buttonsHtml.join('');
        
        // Re-attach event listeners to new buttons
        actionsCell.querySelectorAll('.confirm-test-drive-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                openConfirmModal(btn.dataset.testDriveId, btn.dataset.customerName);
            });
        });
        
        actionsCell.querySelectorAll('.complete-test-drive-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                openCompleteModal(btn.dataset.testDriveId);
            });
        });
        
        actionsCell.querySelectorAll('.cancel-test-drive-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                openCancelModal(
                    btn.dataset.testDriveId,
                    btn.dataset.customerName,
                    btn.dataset.car,
                    btn.dataset.date
                );
            });
        });
        
        actionsCell.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (window.deleteModalManager_deleteModal) {
                    window.deleteModalManager_deleteModal.show({
                        entityName: 'lịch lái thử',
                        deleteUrl: `/admin/test-drives/${btn.dataset.testDriveId}`
                    });
                }
            });
        });
    }
}

function initializeEventListeners() {
    // Confirm buttons
    document.querySelectorAll('.confirm-test-drive-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openConfirmModal(this.dataset.testDriveId, this.dataset.customerName);
        });
    });
    
    // Complete buttons
    document.querySelectorAll('.complete-test-drive-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openCompleteModal(this.dataset.testDriveId);
        });
    });
    
    // Cancel buttons
    document.querySelectorAll('.cancel-test-drive-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openCancelModal(this.dataset.testDriveId, this.dataset.customerName, this.dataset.car, this.dataset.date);
        });
    });
    
    // Delete buttons
    document.querySelectorAll('.delete-test-drive-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            openDeleteModal(this.dataset.testDriveId, this.dataset.customerName, this.dataset.car, this.dataset.date);
        });
    });
}

function showNotification(type, message) {
    // Simple notification (you can replace with your notification system)
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Modal variables
let currentTestDriveId = null;

// Modal functions
function openConfirmModal(testDriveId, customerName) {
    currentTestDriveId = testDriveId;
    document.getElementById('confirmModalMessage').textContent = 
        `Bạn có chắc chắn muốn xác nhận lịch lái thử của ${customerName}?`;
    document.getElementById('confirmModal').classList.remove('hidden');
    
    // Set confirm action
    document.getElementById('confirmModalButton').onclick = () => handleConfirm();
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    currentTestDriveId = null;
}

function openCompleteModal(testDriveId) {
    currentTestDriveId = testDriveId;
    document.getElementById('completeModal').classList.remove('hidden');
    
    // Set complete action
    document.getElementById('completeModalButton').onclick = () => handleComplete();
}

function closeCompleteModal() {
    document.getElementById('completeModal').classList.add('hidden');
    currentTestDriveId = null;
}

function openCancelModal(testDriveId, customerName, car, date) {
    currentTestDriveId = testDriveId;
    document.getElementById('cancelModalMessage').textContent = 
        `Bạn có chắc chắn muốn hủy lịch lái thử của ${customerName}?`;
    document.getElementById('cancelModalDetails').innerHTML = `
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Xe:</span>
                <span class="font-medium text-gray-900">${car}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Ngày hẹn:</span>
                <span class="font-medium text-gray-900">${date}</span>
            </div>
        </div>
    `;
    document.getElementById('cancelModal').classList.remove('hidden');
    
    // Set cancel action
    document.getElementById('cancelModalButton').onclick = () => handleCancel();
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    currentTestDriveId = null;
}

function openDeleteModal(testDriveId, customerName, car, date) {
    currentTestDriveId = testDriveId;
    
    if (window.deleteModalManager_deleteModal) {
        window.deleteModalManager_deleteModal.reset();
        window.deleteModalManager_deleteModal.show({
            entityName: `lịch lái thử của ${customerName}`,
            details: `<div class="text-sm space-y-2">
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Xe:</span>
                        <span class="font-medium">${car}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ngày hẹn:</span>
                        <span class="font-medium">${date}</span>
                    </div>
                </div>
            </div>`,
            deleteUrl: `/admin/test-drives/${testDriveId}`
        });
    }
}

// Delete confirm handler for modal component
window.confirmDelete = function(data) {
    if (!data || !data.deleteUrl) return;
    if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(true);
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.hide();
            
            // Update stats cards real-time
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            // Reload table to refresh data
            loadTestDrivesWithStats();
            
            // Show flash message
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa lịch lái thử!', 'success');
            }
        } else {
            if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(false);
            if (window.showMessage) {
                window.showMessage(data.message || 'Có lỗi xảy ra!', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(false);
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra khi xóa!', 'error');
        }
    });
};

// Handler functions with spinner
function handleConfirm() {
    const button = document.getElementById('confirmModalButton');
    const btnText = document.getElementById('confirmModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    fetch(`/admin/test-drives/${currentTestDriveId}/confirm`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Save ID before closing modal (which resets currentTestDriveId to null)
            const testDriveId = currentTestDriveId;
            closeConfirmModal();
            
            // Update stats cards real-time
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            // Update status badge and buttons in table without reload
            updateTestDriveStatus(testDriveId, 'confirmed');
            
            // Show flash message
            if (window.showMessage) {
                window.showMessage(data.message, 'success');
            }
        } else {
            if (window.showMessage) {
                window.showMessage(data.message, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra', 'error');
        }
    })
    .finally(() => {
        btnText.textContent = originalText;
        button.disabled = false;
    });
}

function handleComplete() {
    const button = document.getElementById('completeModalButton');
    const btnText = document.getElementById('completeModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    fetch(`/admin/test-drives/${currentTestDriveId}/complete`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Save ID before closing modal (which resets currentTestDriveId to null)
            const testDriveId = currentTestDriveId;
            closeCompleteModal();
            
            // Update stats cards real-time
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            // Update status badge and buttons in table without reload
            updateTestDriveStatus(testDriveId, 'completed');
            
            // Show flash message
            if (window.showMessage) {
                window.showMessage(data.message, 'success');
            }
        } else {
            if (window.showMessage) {
                window.showMessage(data.message, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra', 'error');
        }
    })
    .finally(() => {
        btnText.textContent = originalText;
        button.disabled = false;
    });
}

function handleCancel() {
    const button = document.getElementById('cancelModalButton');
    const btnText = document.getElementById('cancelModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    fetch(`/admin/test-drives/${currentTestDriveId}/cancel`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Save ID before closing modal (which resets currentTestDriveId to null)
            const testDriveId = currentTestDriveId;
            closeCancelModal();
            
            // Update stats cards real-time
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            // Update status badge and buttons in table without reload
            updateTestDriveStatus(testDriveId, 'cancelled');
            
            // Show flash message
            if (window.showMessage) {
                window.showMessage(data.message, 'success');
            }
        } else {
            if (window.showMessage) {
                window.showMessage(data.message, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra', 'error');
        }
    })
    .finally(() => {
        btnText.textContent = originalText;
        button.disabled = false;
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
@endpush

{{-- Delete Modal Component --}}
<x-admin.delete-modal 
    modal-id="deleteModal"
    title="Xác nhận xóa lịch lái thử"
    entity-name="lịch lái thử"
    warning-text="Bạn có chắc chắn muốn xóa"
    confirm-text="Xóa"
    cancel-text="Hủy" />

{{-- Confirm Modal --}}
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Xác nhận lịch lái thử</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600" id="confirmModalMessage"></p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeConfirmModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="confirmModalButton"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <span id="confirmModalBtnText">Xác nhận</span>
            </button>
        </div>
    </div>
</div>

{{-- Complete Modal --}}
<div id="completeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-check-double text-purple-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Hoàn thành lịch lái thử</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600">Đánh dấu lịch lái thử này là đã hoàn thành?</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeCompleteModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="completeModalButton"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <span id="completeModalBtnText">Hoàn thành</span>
            </button>
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-ban text-orange-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900">Hủy lịch lái thử</h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600 mb-3" id="cancelModalMessage"></p>
            <div id="cancelModalDetails" class="bg-gray-50 rounded-lg p-3 text-sm"></div>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeCancelModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Không
            </button>
            <button type="button" id="cancelModalButton"
                    class="flex-1 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                <span id="cancelModalBtnText">Hủy lịch hẹn</span>
            </button>
        </div>
    </div>
</div>

@endsection
