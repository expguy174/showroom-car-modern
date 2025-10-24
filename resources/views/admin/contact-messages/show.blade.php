@extends('layouts.admin')

@section('title', 'Chi tiết Tin nhắn')

@section('content')
{{-- Flash Messages --}}
<x-admin.flash-messages 
    :show-icons="true"
    :dismissible="true"
    position="top-right"
    :auto-hide="5000" />

{{-- Page Header --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between space-y-4 sm:space-y-0">
        <div class="flex items-start space-x-4">
            {{-- Avatar --}}
            <div class="flex-shrink-0">
                @if($contactMessage->user && $contactMessage->user->userProfile && $contactMessage->user->userProfile->avatar_path)
                    <img class="h-20 w-20 rounded-full object-cover border-2 border-gray-200" 
                         src="{{ Storage::url($contactMessage->user->userProfile->avatar_path) }}" 
                         alt="{{ $contactMessage->name }}">
                @else
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        <span class="text-white font-bold text-2xl">
                            {{ strtoupper(substr($contactMessage->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>
            
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $contactMessage->name }}</h1>
                <div class="mt-1 space-y-1">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-envelope mr-1"></i>{{ $contactMessage->email }}
                    </p>
                    @if($contactMessage->phone)
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-phone mr-1"></i>{{ $contactMessage->phone }}
                    </p>
                    @endif
                </div>
                <div class="flex items-center mt-3 space-x-2 flex-wrap gap-2">
                    {{-- Contact Type Badge --}}
                    @if($contactMessage->contact_type === 'user')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-user mr-1"></i>Người dùng
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-user-plus mr-1"></i>Khách
                        </span>
                    @endif
                    
                    {{-- Status Badge --}}
                    @switch($contactMessage->status)
                        @case('new')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-envelope mr-1"></i>Mới
                            </span>
                            @break
                        @case('in_progress')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-spinner mr-1"></i>Đang xử lý
                            </span>
                            @break
                        @case('resolved')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Đã giải quyết
                            </span>
                            @break
                        @case('closed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times-circle mr-1"></i>Đã đóng
                            </span>
                            @break
                    @endswitch
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left Column - Message Content (2/3) --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Subject --}}
        @if($contactMessage->subject)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-2">
                <i class="fas fa-heading text-blue-600 mr-2"></i>
                Tiêu đề
            </h2>
            <p class="text-gray-900 text-lg">{{ $contactMessage->subject }}</p>
        </div>
        @endif
        
        {{-- Message Content --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-comment-dots text-blue-600 mr-2"></i>
                Nội dung tin nhắn
            </h2>
            <div class="prose max-w-none">
                <p class="text-gray-900 whitespace-pre-line">{{ $contactMessage->message }}</p>
            </div>
        </div>
        
        {{-- Contact Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Thông tin liên hệ
            </h2>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if($contactMessage->topic)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Chủ đề</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $contactMessage->topic_display }}</dd>
                </div>
                @endif
                
                @if($contactMessage->source)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nguồn</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($contactMessage->source) }}</dd>
                </div>
                @endif
                
                @if($contactMessage->showroom)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Showroom</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <div class="font-medium">{{ $contactMessage->showroom->name }}</div>
                        @if($contactMessage->showroom->address)
                            <div class="text-xs text-gray-500 mt-0.5">{{ $contactMessage->showroom->address }}</div>
                        @endif
                        @if($contactMessage->showroom->phone)
                            <div class="text-xs text-gray-500 mt-0.5">
                                <i class="fas fa-phone mr-1"></i>{{ $contactMessage->showroom->phone }}
                            </div>
                        @endif
                    </dd>
                </div>
                @endif
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Thời gian gửi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $contactMessage->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
        
        {{-- Metadata --}}
        @if($contactMessage->metadata)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-database text-blue-600 mr-2"></i>
                Metadata
            </h2>
            <pre class="bg-gray-50 rounded-lg p-4 overflow-x-auto text-sm">{{ json_encode($contactMessage->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
        @endif
    </div>
    
    {{-- Right Column - Sidebar (1/3) --}}
    <div class="space-y-6">
        {{-- Actions - Workflow --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-tasks text-blue-600 mr-2"></i>
                Thao tác
            </h2>
            <div class="space-y-3">
                {{-- Workflow Buttons based on current status --}}
                @switch($contactMessage->status)
                    @case('new')
                        {{-- New → In Progress --}}
                        <form action="{{ route('admin.contact-messages.update-status', $contactMessage) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-arrow-right mr-2"></i>Bắt đầu xử lý
                            </button>
                        </form>
                        @break
                        
                    @case('in_progress')
                        {{-- In Progress → Resolved --}}
                        <form action="{{ route('admin.contact-messages.update-status', $contactMessage) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="resolved">
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check-circle mr-2"></i>Đánh dấu đã giải quyết
                            </button>
                        </form>
                        @break
                        
                    @case('resolved')
                        {{-- Resolved → Closed --}}
                        <form action="{{ route('admin.contact-messages.update-status', $contactMessage) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="closed">
                            <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-archive mr-2"></i>Đóng tin nhắn
                            </button>
                        </form>
                        @break
                        
                    @case('closed')
                        {{-- No action available for closed status --}}
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-check-circle text-3xl mb-2"></i>
                            <p class="text-sm">Tin nhắn đã được đóng</p>
                        </div>
                        @break
                @endswitch
                
                {{-- Delete --}}
                <div class="pt-3 border-t border-gray-200">
                    <button type="button" 
                            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors delete-show-btn"
                            data-message-id="{{ $contactMessage->id }}"
                            data-message-name="{{ addslashes($contactMessage->name) }}"
                            data-message-subject="{{ addslashes($contactMessage->subject) }}"
                            title="Xóa">
                        <i class="fas fa-trash mr-2"></i>Xóa tin nhắn
                    </button>
                </div>
            </div>
        </div>
        
        {{-- System Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Thông tin hệ thống
            </h2>
            
            {{-- Timestamps Section --}}
            <div class="mb-4 pb-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-clock mr-2"></i>Thời gian
                </h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-xs text-gray-500">Tạo lúc</dt>
                        <dd class="text-sm text-gray-900">{{ $contactMessage->created_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Cập nhật</dt>
                        <dd class="text-sm text-gray-900">{{ $contactMessage->updated_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                    @if($contactMessage->handled_at)
                    <div>
                        <dt class="text-xs text-gray-500">Xử lý lúc</dt>
                        <dd class="text-sm text-gray-900">{{ $contactMessage->handled_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            
            {{-- Handler Info Section --}}
            @if($contactMessage->handledBy)
            <div class="mb-4 pb-4 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-user-check mr-2"></i>Người xử lý
                </h3>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-xs text-gray-500">Tên</dt>
                        <dd class="text-sm text-gray-900">{{ $contactMessage->handledBy->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ $contactMessage->handledBy->email }}</dd>
                    </div>
                </dl>
            </div>
            @endif
            
            {{-- Tracking Info Section --}}
            @if($contactMessage->ip_address || $contactMessage->user_agent)
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-chart-line mr-2"></i>Thông tin theo dõi
                </h3>
                <dl class="space-y-2">
                    @if($contactMessage->ip_address)
                    <div>
                        <dt class="text-xs text-gray-500">Địa chỉ IP</dt>
                        <dd class="text-sm text-gray-900 font-mono">{{ $contactMessage->ip_address }}</dd>
                    </div>
                    @endif
                    
                    @if($contactMessage->user_agent)
                    <div>
                        <dt class="text-xs text-gray-500">User Agent</dt>
                        <dd class="text-sm text-gray-900 break-all">{{ $contactMessage->user_agent }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif
        </div>
    </div>
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
// Handle delete button from show page
document.addEventListener('DOMContentLoaded', function() {
    const deleteShowBtn = document.querySelector('.delete-show-btn');
    if (deleteShowBtn) {
        deleteShowBtn.addEventListener('click', function(e) {
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
    }
});

// Delete confirmation function for show page
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
            
            // Redirect to index page after delete
            setTimeout(() => {
                window.location.href = '{{ route("admin.contact-messages.index") }}';
            }, 1500);
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
</script>
@endpush

@endsection
