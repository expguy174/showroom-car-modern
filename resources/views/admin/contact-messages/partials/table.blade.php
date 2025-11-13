<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 20%;">Người gửi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 20%;">Tiêu đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 30%;">Nội dung</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($messages as $message)
            <tr class="hover:bg-gray-50" data-message-id="{{ $message->id }}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-3">
                        {{-- Avatar --}}
                        <div class="flex-shrink-0">
                            @if($message->user && $message->user->userProfile && $message->user->userProfile->avatar_path)
                                <img class="h-10 w-10 rounded-full object-cover" 
                                     src="{{ Storage::url($message->user->userProfile->avatar_path) }}" 
                                     alt="{{ $message->name }}">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($message->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        {{-- Info --}}
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $message->name }}</div>
                            <div class="text-xs text-gray-500 truncate">
                                <i class="fas fa-envelope mr-1"></i>{{ $message->email }}
                            </div>
                            @if($message->phone)
                            <div class="text-xs text-gray-500 truncate">
                                <i class="fas fa-phone mr-1"></i>{{ $message->phone }}
                            </div>
                            @endif
                            <div class="text-xs text-gray-500">
                                <i class="far fa-clock mr-1"></i>{{ $message->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $subjectLabels = [
                            'general' => 'Thông tin chung',
                            'sales' => 'Tư vấn mua hàng',
                            'service' => 'Dịch vụ bảo dưỡng',
                            'finance' => 'Tư vấn tài chính',
                            'complaint' => 'Khiếu nại',
                            'other' => 'Khác',
                        ];
                        $subjectDisplay = $subjectLabels[$message->subject] ?? ucfirst($message->subject);
                    @endphp
                    <div class="text-sm font-medium text-gray-900">{{ $subjectDisplay }}</div>
                </td>
                <td class="px-6 py-4 max-w-md whitespace-nowrap">
                    <p class="text-sm text-gray-900 truncate">{{ $message->message }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @switch($message->status)
                        @case('new')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-envelope mr-1"></i>Mới
                            </span>
                            @break
                        @case('in_progress')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-spinner mr-1"></i>Đang xử lý
                            </span>
                            @break
                        @case('resolved')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Đã giải quyết
                            </span>
                            @break
                        @case('closed')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times-circle mr-1"></i>Đã đóng
                            </span>
                            @break
                    @endswitch
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                    <div class="flex items-center justify-center gap-2">
                        {{-- View button --}}
                        <a href="{{ route('admin.contact-messages.show', $message) }}" 
                           class="text-blue-600 hover:text-blue-900" 
                           title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        {{-- Workflow buttons based on status --}}
                        @switch($message->status)
                            @case('new')
                                <form action="{{ route('admin.contact-messages.update-status', $message) }}" method="POST" class="inline status-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_progress">
                                    <button type="submit" 
                                            class="text-blue-600 hover:text-blue-900" 
                                            title="Bắt đầu xử lý">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </form>
                                @break
                                
                            @case('in_progress')
                                <form action="{{ route('admin.contact-messages.update-status', $message) }}" method="POST" class="inline status-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="resolved">
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-900" 
                                            title="Đánh dấu đã giải quyết">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                @break
                                
                            @case('resolved')
                                <form action="{{ route('admin.contact-messages.update-status', $message) }}" method="POST" class="inline status-form">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="closed">
                                    <button type="submit" 
                                            class="text-purple-600 hover:text-purple-900" 
                                            title="Đóng tin nhắn">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
                                @break
                        @endswitch
                        
                        {{-- Delete button --}}
                        <button type="button" 
                                class="text-red-600 hover:text-red-900 delete-btn" 
                                data-message-id="{{ $message->id }}"
                                data-message-name="{{ addslashes($message->name) }}"
                                data-message-subject="{{ addslashes($subjectDisplay) }}"
                                title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-envelope text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">Không tìm thấy tin nhắn nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm khác</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- Pagination --}}
    @if($messages->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$messages" />
    </div>
    @endif
</div>
