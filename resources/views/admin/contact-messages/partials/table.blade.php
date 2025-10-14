<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 20%;">Người gửi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 20%;">Tiêu đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 35%;">Nội dung</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 12%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 13%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($messages as $message)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $message->name }}</div>
                    <div class="text-xs text-gray-500">{{ $message->email }}</div>
                    <div class="text-xs text-gray-500">{{ $message->created_at->format('d/m/Y H:i') }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $message->subject }}</div>
                </td>
                <td class="px-6 py-4 max-w-md">
                    <p class="text-sm text-gray-900 truncate">{{ $message->message }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($message->is_read)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-envelope-open mr-1"></i>Đã đọc
                    </span>
                    @else
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        <i class="fas fa-envelope mr-1"></i>Chưa đọc
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-blue-600 hover:text-blue-900" title="Xem">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(!$message->is_read)
                        <form action="{{ route('admin.contact-messages.mark-read', $message) }}" method="POST" class="inline mark-read-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-green-600 hover:text-green-900" title="Đánh dấu đã đọc">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                        <button type="button" 
                                class="text-red-600 hover:text-red-900 delete-btn" 
                                data-message-id="{{ $message->id }}"
                                data-message-name="{{ addslashes($message->name) }}"
                                data-message-subject="{{ addslashes($message->subject) }}"
                                title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-envelope text-4xl mb-2"></i>
                    <p>Chưa có tin nhắn nào</p>
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
