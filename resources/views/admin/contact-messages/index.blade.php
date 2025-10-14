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
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4 mb-6">
        <x-admin.stats-card 
            title="Tổng tin nhắn"
            :value="$messages->total()"
            icon="fas fa-envelope"
            color="blue"
            description="Tất cả tin nhắn"
            dataStat="total" />
            
        <x-admin.stats-card 
            title="Chưa đọc"
            :value="\App\Models\ContactMessage::where('is_read', false)->count()"
            icon="fas fa-envelope"
            color="orange"
            description="Tin nhắn chưa xử lý"
            dataStat="unread" />
            
        <x-admin.stats-card 
            title="Đã đọc"
            :value="\App\Models\ContactMessage::where('is_read', true)->count()"
            icon="fas fa-envelope-open"
            color="gray"
            description="Đã xử lý" />
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
                        'unread' => 'Chưa đọc',
                        'read' => 'Đã đọc'
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
        after-load-callback="initializeEventListeners">
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
// Initialize event listeners
function initializeEventListeners() {
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
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
        });
    });
    
    // Mark as read forms
    document.querySelectorAll('.mark-read-form').forEach(form => {
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
                    if (window.loadMessages) {
                        window.loadMessages();
                    }
                    
                    const data = await response.json();
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
                button.disabled = false;
            }
        });
    });
}

// Dropdown callback
window.loadMessagesFromDropdown = function() {
    const searchForm = document.getElementById('filterForm');
    if (searchForm && window.loadMessages) {
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
    .then(data => {
        if (data.success) {
            if (window.deleteModalManager_deleteMessageModal) {
                window.deleteModalManager_deleteMessageModal.hide();
            }
            
            if (window.loadMessages) {
                window.loadMessages();
            }
            
            if (window.showMessage) {
                window.showMessage(data.message || 'Đã xóa tin nhắn thành công!', 'success');
            }
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeEventListeners();
});
</script>
@endpush

@endsection
