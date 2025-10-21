@extends('layouts.admin')

@section('title', 'Quản lý lịch hẹn dịch vụ')

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
        title="Quản lý lịch hẹn dịch vụ"
        description="Quản lý các lịch hẹn dịch vụ từ khách hàng"
        icon="fas fa-calendar-alt">
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng lịch hẹn"
            :value="$totalAppointments ?? 0"
            icon="fas fa-calendar-alt"
            color="gray"
            description="Tất cả lịch hẹn"
            dataStat="total" />
            
        <x-admin.stats-card 
            title="Đã đặt lịch"
            :value="$pendingAppointments ?? 0"
            icon="fas fa-clock"
            color="yellow"
            description="Chờ xác nhận"
            dataStat="pending" />
            
        <x-admin.stats-card 
            title="Đã xác nhận"
            :value="$confirmedAppointments ?? 0"
            icon="fas fa-check-circle"
            color="green"
            description="Đã xác nhận"
            dataStat="confirmed" />
            
        <x-admin.stats-card 
            title="Đang thực hiện"
            :value="$inProgressAppointments ?? 0"
            icon="fas fa-cog"
            color="purple"
            description="Đang xử lý"
            dataStat="in_progress" />
            
        <x-admin.stats-card 
            title="Hoàn thành"
            :value="$completedAppointments ?? 0"
            icon="fas fa-flag-checkered"
            color="blue"
            description="Đã hoàn thành"
            dataStat="completed" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.service-appointments.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tên, email, biển số xe..."
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
                        ['value' => 'in_progress', 'text' => 'Đang thực hiện'],
                        ['value' => 'completed', 'text' => 'Hoàn thành'],
                        ['value' => 'cancelled', 'text' => 'Đã hủy']
                    ]"
                    placeholder="Tất cả"
                    optionValue="value"
                    optionText="text"
                    :selected="request('status')"
                    onchange="loadAppointmentsFromDropdown"
                    :maxVisible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Service Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dịch vụ</label>
                <x-admin.custom-dropdown 
                    name="service_id"
                    :options="$services ?? []"
                    placeholder="Tất cả"
                    optionValue="id"
                    optionText="name"
                    :selected="request('service_id')"
                    onchange="loadAppointmentsFromDropdown"
                    :maxVisible="6"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadAppointmentsWithStats" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="appointments-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.service-appointments.index') }}"
        callback-name="loadAppointments"
        after-load-callback="initializeEventListeners">
        @include('admin.service-appointments.partials.table', ['appointments' => $appointments])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal Component --}}
<x-admin.delete-modal 
    modal-id="deleteModal"
    title="Xác nhận xóa lịch hẹn"
    entity-name="lịch hẹn"
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
                <h3 class="text-lg font-semibold text-gray-900">Xác nhận lịch hẹn</h3>
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

{{-- Status Update Modal (Start/Complete) --}}
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center" id="statusModalIconWrapper">
                <i id="statusModalIcon"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900" id="statusModalTitle"></h3>
            </div>
        </div>
        <div class="mb-6">
            <p class="text-gray-600" id="statusModalMessage"></p>
        </div>
        <div class="flex space-x-3">
            <button type="button" onclick="closeStatusModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" id="statusModalButton"
                    class="flex-1 px-4 py-2 text-white rounded-lg font-medium transition-colors">
                <span id="statusModalBtnText"></span>
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
                <h3 class="text-lg font-semibold text-gray-900">Hủy lịch hẹn</h3>
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

@push('scripts')
<script>
function initializeEventListeners() {
    initializeConfirmButtons();
    initializeCancelButtons();
    initializeDeleteButtons();
    initializeStartServiceButtons();
    initializeCompleteServiceButtons();
}

function initializeConfirmButtons() {
    document.querySelectorAll('.confirm-btn').forEach(button => {
        button.removeEventListener('click', handleConfirmClick);
        button.addEventListener('click', handleConfirmClick);
    });
}

function handleConfirmClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    const customerName = this.dataset.customerName;
    
    // Show modal
    document.getElementById('confirmModalMessage').textContent = `Bạn có chắc chắn muốn xác nhận lịch hẹn của ${customerName}?`;
    document.getElementById('confirmModal').classList.remove('hidden');
    
    // Set confirm action
    document.getElementById('confirmModalButton').onclick = () => executeConfirm(appointmentId);
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

async function executeConfirm(appointmentId) {
    const button = document.getElementById('confirmModalButton');
    const btnText = document.getElementById('confirmModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/confirm`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeConfirmModal();
            // Update badge and buttons without reloading table
            updateAppointmentStatus(appointmentId, 'confirmed');
            updateStatsCards();
            if (window.showMessage) window.showMessage(data.message || 'Đã xác nhận lịch hẹn!', 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Confirm error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi xác nhận', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

function initializeCancelButtons() {
    document.querySelectorAll('.cancel-btn').forEach(button => {
        button.removeEventListener('click', handleCancelClick);
        button.addEventListener('click', handleCancelClick);
    });
}

function handleCancelClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    const customerName = this.dataset.customerName;
    const service = this.dataset.service;
    const date = this.dataset.date;
    
    // Show cancel modal
    document.getElementById('cancelModalMessage').textContent = `Bạn có chắc chắn muốn hủy lịch hẹn của ${customerName}?`;
    document.getElementById('cancelModalDetails').innerHTML = `
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Dịch vụ:</span>
                <span class="font-medium text-gray-900">${service}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Ngày hẹn:</span>
                <span class="font-medium text-gray-900">${date}</span>
            </div>
        </div>
    `;
    document.getElementById('cancelModal').classList.remove('hidden');
    
    // Set cancel action
    document.getElementById('cancelModalButton').onclick = () => executeCancel(appointmentId);
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
}

async function executeCancel(appointmentId) {
    const button = document.getElementById('cancelModalButton');
    const btnText = document.getElementById('cancelModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/cancel`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeCancelModal();
            // Update badge and buttons without reloading table
            updateAppointmentStatus(appointmentId, 'cancelled');
            updateStatsCards();
            if (window.showMessage) window.showMessage(data.message || 'Đã hủy lịch hẹn!', 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Cancel error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi hủy', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

function initializeDeleteButtons() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.removeEventListener('click', handleDeleteClick);
        btn.addEventListener('click', handleDeleteClick);
    });
}

function handleDeleteClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    const customerName = this.dataset.customerName;
    const service = this.dataset.service;
    const date = this.dataset.date;
    
    if (window.deleteModalManager_deleteModal) {
        window.deleteModalManager_deleteModal.reset();
        window.deleteModalManager_deleteModal.show({
            entityName: `lịch hẹn của ${customerName}`,
            details: `<div class="text-sm space-y-2">
                <div class="bg-gray-50 rounded-md p-3">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div><span class="font-medium text-gray-600">Dịch vụ:</span> <span class="text-gray-900">${service}</span></div>
                        <div><span class="font-medium text-gray-600">Ngày hẹn:</span> <span class="text-gray-900">${date}</span></div>
                    </div>
                </div>
            </div>`,
            deleteUrl: `/admin/service-appointments/${appointmentId}`
        });
    }
}

window.confirmDelete = function(data) {
    if (!data || !data.deleteUrl) return;
    if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(true);
    
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
            if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.hide();
            if (window.loadAppointmentsWithStats) loadAppointmentsWithStats('{{ route("admin.service-appointments.index") }}');
            if (window.showMessage) window.showMessage(data.message || 'Đã xóa lịch hẹn!', 'success');
        } else {
            if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(false);
            if (data.message && window.showMessage) window.showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deleteModal) window.deleteModalManager_deleteModal.setLoading(false);
        if (window.showMessage) window.showMessage('Có lỗi xảy ra khi xóa lịch hẹn', 'error');
    });
};

// Start Service handlers
function initializeStartServiceButtons() {
    document.querySelectorAll('.start-service-btn').forEach(button => {
        button.removeEventListener('click', handleStartServiceClick);
        button.addEventListener('click', handleStartServiceClick);
    });
}

function handleStartServiceClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    
    showStatusModal({
        title: 'Bắt đầu thực hiện dịch vụ',
        message: 'Bạn có chắc chắn muốn bắt đầu thực hiện dịch vụ cho lịch hẹn này?',
        icon: 'fas fa-play-circle text-purple-600',
        iconBg: 'bg-purple-100',
        buttonClass: 'bg-purple-600 hover:bg-purple-700',
        buttonText: 'Bắt đầu',
        action: () => executeStatusUpdate(appointmentId, 'in_progress', 'Đã bắt đầu thực hiện dịch vụ!')
    });
}

// Complete Service handlers
function initializeCompleteServiceButtons() {
    document.querySelectorAll('.complete-service-btn').forEach(button => {
        button.removeEventListener('click', handleCompleteServiceClick);
        button.addEventListener('click', handleCompleteServiceClick);
    });
}

function handleCompleteServiceClick(e) {
    e.preventDefault();
    const appointmentId = this.dataset.appointmentId;
    
    showStatusModal({
        title: 'Hoàn thành dịch vụ',
        message: 'Bạn có chắc chắn đã hoàn thành dịch vụ cho lịch hẹn này?',
        icon: 'fas fa-check-double text-green-600',
        iconBg: 'bg-green-100',
        buttonClass: 'bg-green-600 hover:bg-green-700',
        buttonText: 'Hoàn thành',
        action: () => executeStatusUpdate(appointmentId, 'completed', 'Đã hoàn thành dịch vụ!')
    });
}

function showStatusModal(config) {
    document.getElementById('statusModalTitle').textContent = config.title;
    document.getElementById('statusModalMessage').textContent = config.message;
    document.getElementById('statusModalIcon').className = config.icon;
    document.getElementById('statusModalIconWrapper').className = `flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center ${config.iconBg}`;
    document.getElementById('statusModalButton').className = `flex-1 px-4 py-2 text-white rounded-lg font-medium transition-colors ${config.buttonClass}`;
    document.getElementById('statusModalBtnText').textContent = config.buttonText;
    document.getElementById('statusModal').classList.remove('hidden');
    
    document.getElementById('statusModalButton').onclick = config.action;
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

async function executeStatusUpdate(appointmentId, newStatus, successMessage) {
    const button = document.getElementById('statusModalButton');
    const btnText = document.getElementById('statusModalBtnText');
    const originalText = btnText.textContent;
    
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    button.disabled = true;
    
    try {
        const response = await fetch(`/admin/service-appointments/${appointmentId}/update-status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        });
        
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        
        if (data.success) {
            closeStatusModal();
            // Update badge and buttons without reloading table
            updateAppointmentStatus(appointmentId, newStatus);
            updateStatsCards();
            if (window.showMessage) window.showMessage(data.message || successMessage, 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    } catch (error) {
        console.error('Status update error:', error);
        if (window.showMessage) window.showMessage(error.message || 'Có lỗi khi cập nhật trạng thái', 'error');
    } finally {
        btnText.textContent = originalText;
        button.disabled = false;
    }
}

// Update appointment status badge and action buttons without reload
function updateAppointmentStatus(appointmentId, newStatus) {
    // Find the row for this appointment
    const rows = document.querySelectorAll('tbody tr');
    
    for (const row of rows) {
        const viewBtn = row.querySelector(`a[href*="/service-appointments/${appointmentId}"]`);
        if (!viewBtn) continue;
        
        // Update status badge
        const statusCell = row.querySelectorAll('td')[5]; // Status column (index 5)
        if (statusCell) {
            statusCell.innerHTML = getStatusBadgeHTML(newStatus);
        }
        
        // Update action buttons
        const actionsCell = row.querySelectorAll('td')[6]; // Actions column (index 6)
        if (actionsCell) {
            const actionsDiv = actionsCell.querySelector('.flex');
            if (actionsDiv) {
                actionsDiv.innerHTML = getActionButtonsHTML(appointmentId, newStatus, row);
                // Re-initialize event listeners for new buttons
                initializeEventListeners();
            }
        }
        
        break;
    }
}

// Get status badge HTML based on status
function getStatusBadgeHTML(status) {
    const badges = {
        'scheduled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Đã đặt lịch</span>',
        'confirmed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Đã xác nhận</span>',
        'in_progress': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800"><i class="fas fa-cog mr-1"></i>Đang thực hiện</span>',
        'completed': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fas fa-flag-checkered mr-1"></i>Hoàn thành</span>',
        'cancelled': '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Đã hủy</span>'
    };
    return badges[status] || status;
}

// Get action buttons HTML based on status
function getActionButtonsHTML(appointmentId, status, row) {
    // Extract data attributes from row
    const customerName = row.querySelector('[data-customer-name]')?.dataset.customerName || 'Khách hàng';
    const service = row.querySelector('[data-service]')?.dataset.service || 'N/A';
    const date = row.querySelector('[data-date]')?.dataset.date || '';
    
    let html = `
        <a href="/admin/service-appointments/${appointmentId}" 
           class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50" 
           title="Xem chi tiết">
            <i class="fas fa-eye w-4 h-4"></i>
        </a>
    `;
    
    // Confirm button - scheduled/rescheduled
    if (['scheduled', 'rescheduled'].includes(status)) {
        html += `
            <button type="button"
                    class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 confirm-btn"
                    data-appointment-id="${appointmentId}"
                    data-customer-name="${customerName}"
                    title="Xác nhận lịch hẹn">
                <i class="fas fa-check-circle w-4 h-4"></i>
            </button>
        `;
    }
    
    // Start button - confirmed
    if (status === 'confirmed') {
        html += `
            <button type="button"
                    class="text-purple-600 hover:text-purple-900 transition-colors p-1 rounded hover:bg-purple-50 start-service-btn"
                    data-appointment-id="${appointmentId}"
                    title="Bắt đầu thực hiện">
                <i class="fas fa-play-circle w-4 h-4"></i>
            </button>
        `;
    }
    
    // Complete button - in_progress
    if (status === 'in_progress') {
        html += `
            <button type="button"
                    class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50 complete-service-btn"
                    data-appointment-id="${appointmentId}"
                    title="Hoàn thành">
                <i class="fas fa-check-double w-4 h-4"></i>
            </button>
        `;
    }
    
    // Cancel button - scheduled/confirmed
    if (['scheduled', 'confirmed'].includes(status)) {
        html += `
            <button type="button"
                    class="text-orange-600 hover:text-orange-900 transition-colors p-1 rounded hover:bg-orange-50 cancel-btn"
                    data-appointment-id="${appointmentId}"
                    data-customer-name="${customerName}"
                    data-service="${service}"
                    data-date="${date}"
                    title="Hủy lịch hẹn">
                <i class="fas fa-ban w-4 h-4"></i>
            </button>
        `;
    }
    
    // Delete button - always
    html += `
        <button type="button"
                class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50 delete-btn"
                data-appointment-id="${appointmentId}"
                data-customer-name="${customerName}"
                data-service="${service}"
                data-date="${date}"
                title="Xóa lịch hẹn">
            <i class="fas fa-trash w-4 h-4"></i>
        </button>
    `;
    
    return html;
}

// Update stats cards without reload
function updateStatsCards() {
    const url = '{{ route("admin.service-appointments.index") }}';
    fetch(url + (url.includes('?') ? '&' : '?') + 'stats_only=1', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update stats cards
        const stats = {
            'total': data.total || 0,
            'pending': data.pending || 0,
            'confirmed': data.confirmed || 0,
            'in_progress': data.in_progress || 0,
            'completed': data.completed || 0
        };
        
        // Update each stat card
        for (const [key, value] of Object.entries(stats)) {
            const statElement = document.querySelector(`[data-stat="${key}"]`);
            if (statElement) {
                statElement.textContent = value;
            }
        }
    })
    .catch(error => console.error('Error updating stats:', error));
}

// Note: window.loadAppointments is created by ajax-table component
// Load with stats update
window.loadAppointmentsWithStats = function(url) {
    // Load table
    window.loadAppointments(url);
    
    // Load stats
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

window.loadAppointmentsFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.service-appointments.index") }}?' + new URLSearchParams(formData).toString();
        window.loadAppointmentsWithStats(url);
    }
};

window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.service-appointments.index") }}?' + new URLSearchParams(formData).toString();
        window.loadAppointmentsWithStats(url);
    }
};

window.updateStatsFromServer = function(stats) {
    const statsMapping = {
        'total': 'total',
        'pending': 'pending',
        'confirmed': 'confirmed',
        'inProgress': 'in_progress',
        'completed': 'completed'
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

document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
@endpush
@endsection
