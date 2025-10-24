@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

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
        title="Tin nhắn Liên hệ"
        description="Quản lý tin nhắn từ khách hàng"
        icon="fas fa-envelope">
    </x-admin.page-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng tin nhắn"
            :value="$messages->total()"
            icon="fas fa-envelope"
            color="blue"
            description="Tất cả tin nhắn"
            dataStat="total" />
            
        <x-admin.stats-card 
            title="Mới"
            :value="\App\Models\ContactMessage::where('status', 'new')->count()"
            icon="fas fa-envelope"
            color="orange"
            description="Tin nhắn mới"
            dataStat="new" />
            
        <x-admin.stats-card 
            title="Đang xử lý"
            :value="\App\Models\ContactMessage::where('status', 'in_progress')->count()"
            icon="fas fa-spinner"
            color="blue"
            description="Đang xử lý"
            dataStat="in_progress" />
            
        <x-admin.stats-card 
            title="Đã giải quyết"
            :value="\App\Models\ContactMessage::where('status', 'resolved')->count()"
            icon="fas fa-check-circle"
            color="green"
            description="Hoàn thành"
            dataStat="resolved" />
            
        <x-admin.stats-card 
            title="Đã đóng"
            :value="\App\Models\ContactMessage::where('status', 'closed')->count()"
            icon="fas fa-times-circle"
            color="gray"
            description="Đã đóng"
            dataStat="closed" />
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form id="filterForm" 
              class="grid grid-cols-1 md:grid-cols-[1fr_minmax(min-content,_auto)_auto] gap-4 items-end"
              data-base-url="{{ route('admin.contact-messages.index') }}">
            
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
                <x-admin.search-input 
                    name="search"
                    placeholder="Tìm theo tên, email, tiêu đề..."
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
                        'new' => 'Mới',
                        'in_progress' => 'Đang xử lý',
                        'resolved' => 'Đã giải quyết',
                        'closed' => 'Đã đóng'
                    ]"
                    :selected="request('status')"
                    placeholder="Tất cả"
                    onchange="loadMessagesFromDropdown"
                    :searchable="false"
                    width="w-full" />
            </div>
            
            {{-- Reset --}}
            <div>
                <x-admin.reset-button 
                    formId="#filterForm" 
                    callback="loadMessages" />
            </div>
        </form>
    </div>

    {{-- AJAX Table Component --}}
    <x-admin.ajax-table 
        table-id="messages-content"
        loading-id="loading-state"
        form-id="#filterForm"
        base-url="{{ route('admin.contact-messages.index') }}"
        callback-name="loadMessages"
        empty-message="Không có tin nhắn nào"
        empty-icon="fas fa-envelope"
        after-load-callback="initializeEventListenersAndUpdateStats">
        @include('admin.contact-messages.partials.table', ['messages' => $messages])
    </x-admin.ajax-table>
</div>

{{-- Delete Modal --}}
<x-admin.delete-modal 
    modal-id="deleteMessageModal"
    title="Xác nhận xóa tin nhắn"
    confirm-text="Xóa"
    cancel-text="Hủy"
    delete-callback-name="confirmDeleteMessage"
    entity-type="message" />

@push('scripts')
<script>
// Function to update table row status without reload
function updateTableRowStatus(form, stats) {
    const formData = new FormData(form);
    const newStatus = formData.get('status');
    const messageId = form.action.split('/').slice(-2, -1)[0]; // Lấy ID từ URL, bỏ qua 'update-status'
    
    // Find the table row
    const row = document.querySelector(`tr[data-message-id="${messageId}"]`);
    if (!row) return;
    
    // Update status badge
    const statusCell = row.querySelector('td:nth-child(4)');
    if (statusCell) {
        const statusBadge = statusCell.querySelector('span');
        if (statusBadge) {
            switch(newStatus) {
                case 'in_progress':
                    statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
                    statusBadge.innerHTML = '<i class="fas fa-spinner mr-1"></i>Đang xử lý';
                    break;
                case 'resolved':
                    statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Đã giải quyết';
                    break;
                case 'closed':
                    statusBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                    statusBadge.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Đã đóng';
                    break;
            }
        }
    }
    
    // Update action buttons
    const actionCell = row.querySelector('td:nth-child(5)');
    if (actionCell) {
        const actionDiv = actionCell.querySelector('div');
        if (actionDiv) {
            let newActionHTML = '';
            
            // Keep view and delete buttons
            const viewBtn = actionDiv.querySelector('a[href*="/admin/contact-messages/"]');
            const deleteBtn = actionDiv.querySelector('.delete-btn');
            
            if (viewBtn) {
                newActionHTML += viewBtn.outerHTML;
            }
            
            // Add new action button based on status
            switch(newStatus) {
                case 'in_progress':
                    newActionHTML += `
                        <form action="${form.action}" method="POST" class="inline status-form">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="text-green-600 hover:text-green-900" title="Đánh dấu đã giải quyết">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                    `;
                    break;
                case 'resolved':
                    newActionHTML += `
                        <form action="${form.action}" method="POST" class="inline status-form">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="PATCH">
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="text-purple-600 hover:text-purple-900" title="Đóng tin nhắn">
                                <i class="fas fa-archive"></i>
                            </button>
                        </form>
                    `;
                    break;
            }
            
            if (deleteBtn) {
                newActionHTML += deleteBtn.outerHTML;
            }
            
            actionDiv.innerHTML = newActionHTML;
            
            // Re-attach delete button listeners
            attachDeleteButtonListeners();
            
            // Re-attach event listeners to new forms
            actionDiv.querySelectorAll('.status-form').forEach(newForm => {
                newForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const button = this.querySelector('button');
                    const originalHtml = button.innerHTML;
                    
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    button.disabled = true;
                    
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            body: new FormData(this),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            
                            if (data.stats) {
                                updateStatsCards(data.stats);
                            }
                            
                            updateTableRowStatus(this, data.stats);
                            
                            if (data.message && window.showMessage) {
                                window.showMessage(data.message, 'success');
                            }
                        } else {
                            throw new Error('Request failed');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        if (window.showMessage) {
                            window.showMessage('Có lỗi xảy ra!', 'error');
                        }
                        button.innerHTML = originalHtml;
                    } finally {
                        button.disabled = false;
                    }
                });
            });
        }
    }
}

// Function to update stats cards (giống trang users - không có animation)
function updateStatsCards(stats) {
    if (!stats) return;
    
    // Update each stats card
    Object.keys(stats).forEach(statKey => {
        const cardElement = document.querySelector(`[data-stat="${statKey}"]`);
        if (cardElement) {
            const currentValue = cardElement.textContent;
            const newValue = stats[statKey];
            
            // Only update if value has changed
            if (currentValue !== newValue.toString()) {
                cardElement.textContent = newValue;
            }
        }
    });
}

// Function to fetch and update stats
async function fetchAndUpdateStats() {
    try {
        const response = await fetch('{{ route("admin.contact-messages.stats") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const stats = await response.json();
            updateStatsCards(stats);
        }
    } catch (error) {
        console.error('Error fetching stats:', error);
    }
}

// Function to attach delete button listeners
function attachDeleteButtonListeners() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.removeEventListener('click', handleDeleteButtonClick);
        button.addEventListener('click', handleDeleteButtonClick);
    });
}

function handleDeleteButtonClick(e) {
            e.preventDefault();
            const messageId = this.dataset.messageId;
            const senderName = this.dataset.messageName;
            const subject = this.dataset.messageSubject;
            
            if (window.deleteModalManager_deleteMessageModal) {
                window.deleteModalManager_deleteMessageModal.show({
                    entityName: `tin nhắn từ ${senderName}`,
                    details: `<strong>Tiêu đề:</strong> ${subject}<br>Hành động này không thể hoàn tác.`,
                    deleteUrl: `/admin/contact-messages/${messageId}`
                });
            }
}

// Initialize event listeners
function initializeEventListeners() {
    // Delete buttons
    attachDeleteButtonListeners();
    
    // Status update forms
    document.querySelectorAll('.status-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button');
            const originalHtml = button.innerHTML;
            
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    // Update stats if provided
                    if (data.stats) {
                        updateStatsCards(data.stats);
                    }
                    
                    // Update table row status without full reload
                    updateTableRowStatus(this, data.stats);
                    
                    if (data.message && window.showMessage) {
                        window.showMessage(data.message, 'success');
                    }
                    
                } else {
                    throw new Error('Request failed');
                }
            } catch (error) {
                console.error('Error:', error);
                if (window.showMessage) {
                    window.showMessage('Có lỗi xảy ra!', 'error');
                }
                button.innerHTML = originalHtml;
            } finally {
                button.disabled = false;
            }
        });
    });
}

// Dropdown callback
window.loadMessagesFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadMessages) {
        // Fix status value before creating FormData
        const statusInput = searchForm.querySelector('input[name="status"]');
        if (statusInput) {
            // Map text values to actual values
            const statusMap = {
                'Mới': 'new',
                'Đang xử lý': 'in_progress', 
                'Đã giải quyết': 'resolved',
                'Đã đóng': 'closed'
            };
            
            if (statusMap[statusInput.value]) {
                statusInput.value = statusMap[statusInput.value];
            }
        }
        
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.contact-messages.index") }}?' + new URLSearchParams(formData).toString();
        window.loadMessages(url);
    }
};

// Search callback
window.handleSearch = function(searchTerm, inputElement) {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadMessages) {
        const formData = new FormData(searchForm);
        const url = '{{ route("admin.contact-messages.index") }}?' + new URLSearchParams(formData).toString();
        window.loadMessages(url);
    }
};

// Delete confirmation
window.confirmDeleteMessage = function(data) {
    if (!data || !data.deleteUrl) return;
    
    if (window.deleteModalManager_deleteMessageModal) {
        window.deleteModalManager_deleteMessageModal.setLoading(true);
    }
    
    fetch(data.deleteUrl, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(responseData => {
        if (responseData.success) {
            if (window.deleteModalManager_deleteMessageModal) {
                window.deleteModalManager_deleteMessageModal.hide();
            }
            
            if (window.showMessage) {
                window.showMessage(responseData.message || 'Đã xóa tin nhắn thành công!', 'success');
            }
            
            // Reload table giống trang users
            if (window.loadMessages) {
                window.loadMessages();
            }
        } else {
            throw new Error(responseData.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.deleteModalManager_deleteMessageModal) {
            window.deleteModalManager_deleteMessageModal.setLoading(false);
        }
        if (window.showMessage) {
            window.showMessage('Có lỗi xảy ra khi xóa tin nhắn!', 'error');
        }
    });
};

// Initialize event listeners and update stats after table load
window.initializeEventListenersAndUpdateStats = function() {
    initializeEventListeners();
    attachDeleteButtonListeners();
    fetchAndUpdateStats();
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
@endpush

@endsection
