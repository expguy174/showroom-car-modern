@extends('layouts.admin')

@section('title', 'Quản lý hoàn tiền')

@section('content')
{{-- Flash Messages Component --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000" />

<div class="space-y-6">
        {{-- Page Header --}}
        <x-admin.page-header 
            title="Quản lý hoàn tiền"
            description="Xử lý các yêu cầu hoàn tiền từ khách hàng"
            icon="fas fa-undo">
        </x-admin.page-header>

        {{-- Stats Cards (passed from controller) --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-6">
            <x-admin.stats-card 
                title="Chờ xử lý"
                :value="$stats['pending']"
                icon="fas fa-clock"
                color="yellow"
                description="Yêu cầu mới"
                dataStat="pending"
                onclick="filterRefunds('pending')" />
                
            <x-admin.stats-card 
                title="Đang xử lý"
                :value="$stats['processing']"
                icon="fas fa-spinner"
                color="blue"
                description="Đang kiểm tra"
                dataStat="processing"
                onclick="filterRefunds('processing')" />
                
            <x-admin.stats-card 
                title="Đã hoàn tiền"
                :value="$stats['refunded']"
                icon="fas fa-check"
                color="green"
                description="Hoàn thành"
                dataStat="refunded"
                onclick="filterRefunds('refunded')" />
                
            <x-admin.stats-card 
                title="Thất bại"
                :value="$stats['failed']"
                icon="fas fa-times"
                color="red"
                description="Bị từ chối"
                dataStat="failed"
                onclick="filterRefunds('failed')" />
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <form id="filterForm" 
                  class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
                  data-base-url="{{ route('admin.payments.refunds') }}">
                
                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                    <x-admin.search-input 
                        name="search"
                        placeholder="Tìm theo mã đơn hàng, khách hàng, lý do..."
                        :value="request('search')"
                        callbackName="handleRefundSearch"
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
                            ['value' => 'pending', 'label' => 'Chờ xử lý'],
                            ['value' => 'processing', 'label' => 'Đang xử lý'],
                            ['value' => 'refunded', 'label' => 'Đã hoàn tiền'],
                            ['value' => 'failed', 'label' => 'Thất bại']
                        ]"
                        optionValue="value"
                        optionText="label"
                        :selected="request('status')"
                        placeholder="Tất cả"
                        onchange="loadRefundsFromDropdown"
                        :searchable="false"
                        width="w-full" />
                </div>
                
                {{-- Reset --}}
                <div>
                    <x-admin.reset-button 
                        formId="#filterForm" 
                        callback="loadRefunds" />
                </div>
            </form>
        </div>

        {{-- AJAX Table Component --}}
        <x-admin.ajax-table 
            table-id="refunds-content"
            loading-id="refunds-loading"
            form-id="#filterForm"
            base-url="{{ route('admin.payments.refunds') }}"
            callback-name="loadRefunds"
            after-load-callback="initializeRefundEventListeners">
            @include('admin.payments.partials.refunds-table', ['refunds' => $refunds])
        </x-admin.ajax-table>
    </div>
</div>

{{-- Update Status Modal --}}
<div id="updateRefundModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 overflow-hidden transform transition-all">
        {{-- Header --}}
        <div class="flex items-center mb-4">
            <div id="modalIcon" class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-question-circle text-blue-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">
                    Xác nhận thao tác
                </h3>
            </div>
        </div>
        
        {{-- Content --}}
        <div class="mb-6">
            <p class="text-gray-600" id="modalMessage">
                Bạn có chắc chắn muốn thực hiện thao tác này?
            </p>
            
            {{-- Details --}}
            <div id="modalDetails" class="mt-3 p-3 bg-gray-50 rounded-lg border border-gray-100 hidden">
                <div class="text-xs text-gray-600 space-y-1.5">
                    <div class="flex justify-between">
                        <span class="font-medium">Refund ID:</span>
                        <span id="detailRefundId" class="text-gray-900"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Số tiền:</span>
                        <span id="detailAmount" class="text-gray-900 font-semibold"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Khách hàng:</span>
                        <span id="detailCustomer" class="text-gray-900"></span>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Actions --}}
        <div class="flex space-x-3">
            <button type="button" 
                    onclick="closeUpdateModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors">
                Hủy
            </button>
            <button type="button" 
                    id="confirmButton" 
                    onclick="confirmUpdateStatus()" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
<i class="fas fa-spinner fa-spin mr-2 hidden" id="confirmButtonSpinner"></i>
                <span id="confirmButtonText">Xác nhận</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Update stats cards from server response
window.updateStatsFromServer = function(stats) {
    const statsMapping = {
        'pending': 'pending',
        'processing': 'processing',
        'refunded': 'refunded',
        'failed': 'failed'
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

// Initialize event listeners
function initializeRefundEventListeners() {
    // Handle pagination clicks
    const paginationLinks = document.querySelectorAll('#refunds-content .pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            if (url) {
                window.loadRefunds(url);
            }
        });
    });
    
    console.log('Refund event listeners initialized');
}

// Global functions for components integration
window.loadRefunds = function(url = null) {
    const form = document.getElementById('filterForm');
    const baseUrl = url || form.dataset.baseUrl || '{{ route("admin.payments.refunds") }}';
    
    // If URL provided with params, use it directly
    if (url && url.includes('?')) {
        loadTable(url);
        return;
    }
    
    // Build URL with form parameters
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        if (value && value.trim() !== '') {
            params.append(key, value);
        }
    }
    
    const finalUrl = baseUrl + (params.toString() ? '?' + params.toString() : '');
    loadTable(finalUrl);
};

// Helper function to load table content
function loadTable(url) {
    const loadingElement = document.getElementById('refunds-loading');
    const contentElement = document.getElementById('refunds-content');
    
    if (!contentElement) return;
    
    // Show loading
    if (loadingElement) loadingElement.classList.remove('hidden');
    if (contentElement) contentElement.classList.add('opacity-50');
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        },
        cache: 'no-cache'
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.text();
    })
    .then(html => {
        if (contentElement) {
            contentElement.innerHTML = html;
            contentElement.classList.remove('opacity-50');
        }
        if (loadingElement) loadingElement.classList.add('hidden');
        
        // Re-initialize event listeners
        if (typeof initializeRefundEventListeners === 'function') {
            initializeRefundEventListeners();
        }
    })
    .catch(error => {
        console.error('Error loading refunds:', error);
        if (loadingElement) loadingElement.classList.add('hidden');
        if (contentElement) contentElement.classList.remove('opacity-50');
        
        if (typeof window.showMessage === 'function') {
            window.showMessage('Có lỗi khi tải dữ liệu', 'error');
        }
    });
}

// Stats card click handlers
window.filterRefunds = function(status) {
    const form = document.getElementById('filterForm');
    const statusInput = form.querySelector('[name="status"]');
    
    if (statusInput) {
        // Update the hidden select value
        statusInput.value = status;
        
        // Update custom dropdown display if it exists
        const customDropdown = statusInput.closest('.custom-dropdown-container');
        if (customDropdown) {
            const trigger = customDropdown.querySelector('.custom-dropdown-trigger span');
            if (trigger) {
                const statusLabels = {
                    'pending': 'Chờ xử lý',
                    'processing': 'Đang xử lý',
                    'refunded': 'Đã hoàn tiền',
                    'failed': 'Thất bại'
                };
                trigger.textContent = statusLabels[status] || status;
            }
        }
    }
    
    // Build URL from form data
    if (form) {
        const formData = new FormData(form);
        const url = '{{ route("admin.payments.refunds") }}?' + new URLSearchParams(formData).toString();
        window.loadRefunds(url);
    }
};

// Search input callback
window.handleRefundSearch = function(searchTerm, inputElement) {
    console.log('Refund search:', searchTerm);
    const form = document.getElementById('filterForm');
    if (form) {
        const formData = new FormData(form);
        const url = '{{ route("admin.payments.refunds") }}?' + new URLSearchParams(formData).toString();
        window.loadRefunds(url);
    }
};

// Dropdown change handler
window.loadRefundsFromDropdown = function() {
    console.log('Loading refunds from dropdown change');
    const form = document.getElementById('filterForm');
    if (form) {
        const formData = new FormData(form);
        const url = '{{ route("admin.payments.refunds") }}?' + new URLSearchParams(formData).toString();
        window.loadRefunds(url);
    }
};

// Modal management
let currentRefundId = null;
let currentStatus = null;

function openUpdateModal(refundId, status, refundData = {}) {
    currentRefundId = refundId;
    currentStatus = status;
    
    const modal = document.getElementById('updateRefundModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalIcon = document.getElementById('modalIcon');
    const confirmButton = document.getElementById('confirmButton');
    const confirmButtonText = document.getElementById('confirmButtonText');
    const modalDetails = document.getElementById('modalDetails');
    const modalInner = modal.querySelector('.bg-white');
    
    // Configure modal based on status
    const statusConfig = {
        'processing': {
            title: 'Xác nhận bắt đầu xử lý',
            message: 'Bạn có chắc chắn muốn bắt đầu xử lý yêu cầu hoàn tiền này? Yêu cầu sẽ chuyển sang trạng thái "Đang xử lý".',
            icon: 'fa-arrow-right',
            iconBg: 'bg-blue-100',
            iconColor: 'text-blue-600',
            buttonText: 'Xử lý',
            buttonClass: 'bg-blue-600 hover:bg-blue-700'
        },
        'refunded': {
            title: 'Xác nhận hoàn tiền',
            message: 'Bạn có chắc chắn muốn hoàn tiền cho khách hàng? Hành động này sẽ tự động hủy đơn hàng và không thể hoàn tác.',
            icon: 'fa-check-circle',
            iconBg: 'bg-green-100',
            iconColor: 'text-green-600',
            buttonText: 'Hoàn tiền',
            buttonClass: 'bg-green-600 hover:bg-green-700'
        },
        'failed': {
            title: 'Xác nhận từ chối',
            message: 'Bạn có chắc chắn muốn từ chối yêu cầu hoàn tiền này? Khách hàng sẽ được thông báo về quyết định này.',
            icon: 'fa-times-circle',
            iconBg: 'bg-red-100',
            iconColor: 'text-red-600',
            buttonText: 'Từ chối',
            buttonClass: 'bg-red-600 hover:bg-red-700'
        }
    };
    
    const config = statusConfig[status];
    if (!config) return;
    
    // Update modal content
    modalTitle.textContent = config.title;
    modalMessage.textContent = config.message;
    modalIcon.className = `flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center ${config.iconBg}`;
    modalIcon.innerHTML = `<i class="fas ${config.icon} ${config.iconColor}"></i>`;
    confirmButtonText.textContent = config.buttonText;
    confirmButton.className = `flex-1 ${config.buttonClass} text-white px-4 py-2 rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed`;
    
    // Show details if available
    if (refundData.amount || refundData.customer) {
        document.getElementById('detailRefundId').textContent = `#${refundId}`;
        document.getElementById('detailAmount').textContent = refundData.amount || 'N/A';
        document.getElementById('detailCustomer').textContent = refundData.customer || 'N/A';
        modalDetails.classList.remove('hidden');
    } else {
        modalDetails.classList.add('hidden');
    }
    
    // Show modal with animation
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal in
    if (modalInner) {
        modalInner.style.transform = 'scale(0.95)';
        modalInner.style.opacity = '0';
        requestAnimationFrame(() => {
            modalInner.style.transition = 'all 0.2s ease-out';
            modalInner.style.transform = 'scale(1)';
            modalInner.style.opacity = '1';
        });
    }
    
    // Focus confirm button for accessibility
    setTimeout(() => {
        confirmButton?.focus();
    }, 100);
}

function closeUpdateModal() {
    const modal = document.getElementById('updateRefundModal');
    const modalInner = modal.querySelector('.bg-white');
    
    // Animate modal out
    if (modalInner) {
        modalInner.style.transition = 'all 0.15s ease-in';
        modalInner.style.transform = 'scale(0.95)';
        modalInner.style.opacity = '0';
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            currentRefundId = null;
            currentStatus = null;
        }, 150);
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        currentRefundId = null;
        currentStatus = null;
    }
}

function confirmUpdateStatus() {
    if (!currentRefundId || !currentStatus) return;
    
    const confirmButton = document.getElementById('confirmButton');
    const confirmButtonText = document.getElementById('confirmButtonText');
    const confirmButtonSpinner = document.getElementById('confirmButtonSpinner');
    
    // Prevent double-click
    if (confirmButton.disabled) {
        return;
    }
    
    // Show loading state immediately
    confirmButton.disabled = true;
    confirmButtonSpinner.classList.remove('hidden');
    
    fetch(`/admin/payments/refunds/${currentRefundId}/status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            refund_status: currentStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        // Hide loading state
        confirmButton.disabled = false;
        confirmButtonSpinner.classList.add('hidden');
        
        if (data.success) {
            // Close modal
            closeUpdateModal();
            
            // Show success message
            if (typeof window.showMessage === 'function') {
                window.showMessage(data.message, 'success');
            }
            
            // Update stats if available
            if (data.stats && window.updateStatsFromServer) {
                window.updateStatsFromServer(data.stats);
            }
            
            // Update status badge in table without reload
            if (data.status && currentRefundId) {
                const row = document.querySelector(`tr[data-refund-id="${currentRefundId}"]`);
                if (row) {
                    // Update status badge
                    const statusCell = row.querySelector('.status-badge');
                    if (statusCell) {
                        const statusBadges = {
                            'pending': '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Chờ xử lý</span>',
                            'processing': '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><i class="fas fa-spinner mr-1"></i>Đang xử lý</span>',
                            'refunded': '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Đã hoàn tiền</span>',
                            'failed': '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800"><i class="fas fa-times-circle mr-1"></i>Thất bại</span>'
                        };
                        statusCell.innerHTML = statusBadges[data.status] || statusCell.innerHTML;
                    }
                    
                    // Update action column
                    const actionCells = row.querySelectorAll('td');
                    const actionCell = actionCells[actionCells.length - 1]; // Last column is action
                    if (actionCell) {
                        const actionButtons = {
                            'pending': `
                                <div class="flex items-center justify-center gap-1">
                                    <button onclick="updateRefundStatus(${currentRefundId}, 'processing')" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition-colors"
                                            title="Bắt đầu xử lý yêu cầu hoàn tiền">
                                        <i class="fas fa-arrow-right mr-1"></i>
                                        Xử lý
                                    </button>
                                    <div class="relative group">
                                        <button class="inline-flex items-center px-1 py-1 text-xs text-gray-500 hover:text-gray-700 rounded"
                                                title="Thao tác nhanh">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="absolute right-0 top-full mt-1 w-32 bg-white rounded-md shadow-lg border border-gray-200 z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                            <button onclick="updateRefundStatus(${currentRefundId}, 'refunded')" 
                                                    class="block w-full text-left px-3 py-2 text-xs text-green-700 hover:bg-green-50">
                                                <i class="fas fa-check mr-1"></i>Hoàn tiền ngay
                                            </button>
                                            <button onclick="updateRefundStatus(${currentRefundId}, 'failed')" 
                                                    class="block w-full text-left px-3 py-2 text-xs text-red-700 hover:bg-red-50">
                                                <i class="fas fa-times mr-1"></i>Từ chối ngay
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `,
                            'processing': `
                                <div class="flex items-center justify-center gap-1">
                                    <button onclick="updateRefundStatus(${currentRefundId}, 'refunded')" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 transition-colors"
                                            title="Chấp nhận và thực hiện hoàn tiền">
                                        <i class="fas fa-check mr-1"></i>
                                        Hoàn tiền
                                    </button>
                                    <button onclick="updateRefundStatus(${currentRefundId}, 'failed')" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 transition-colors"
                                            title="Từ chối yêu cầu hoàn tiền">
                                        <i class="fas fa-times mr-1"></i>
                                        Từ chối
                                    </button>
                                </div>
                            `,
                            'refunded': `
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Hoàn thành
                                </span>
                            `,
                            'failed': `
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Đã từ chối
                                </span>
                            `
                        };
                        actionCell.innerHTML = actionButtons[data.status] || actionCell.innerHTML;
                    }
                }
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Hide loading state
        confirmButton.disabled = false;
        confirmButtonSpinner.classList.add('hidden');
        
        // Close modal
        closeUpdateModal();
        
        // Show error message
        if (typeof window.showMessage === 'function') {
            window.showMessage(error.message || 'Có lỗi xảy ra khi cập nhật trạng thái', 'error');
        }
    });
}

function updateRefundStatus(refundId, status, refundData = {}) {
    if (!status) return;
    
    // Open modal instead of alert
    openUpdateModal(refundId, status, refundData);
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUpdateModal();
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeRefundEventListeners();
});
</script>
@endpush

@endsection
