@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tin nhắn Liên hệ</h1>
        <p class="text-sm text-gray-600 mt-1">Quản lý tin nhắn từ khách hàng</p>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên, email, tiêu đề..." class="flex-1 rounded-lg border-gray-300">
            <select name="status" class="rounded-lg border-gray-300">
                <option value="">Tất cả</option>
                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Chưa đọc</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Đã đọc</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i>Lọc
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người gửi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nội dung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày gửi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trạng thái</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($messages as $message)
                    <tr class="hover:bg-gray-50 {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($message->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 {{ !$message->is_read ? 'font-bold' : '' }}">
                                        {{ $message->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $message->email }}</div>
                                    @if($message->phone)
                                    <div class="text-xs text-gray-500">{{ $message->phone }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 {{ !$message->is_read ? 'font-semibold' : '' }}">
                                {{ $message->subject }}
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-md">
                            <p class="text-sm text-gray-600 truncate">{{ $message->message }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $message->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.contact-messages.mark-read', $message) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors {{ $message->is_read ? 'bg-gray-100 text-gray-800 hover:bg-gray-200' : 'bg-blue-100 text-blue-800 hover:bg-blue-200' }}">
                                    <i class="fas {{ $message->is_read ? 'fa-envelope-open' : 'fa-envelope' }} mr-1"></i>
                                    {{ $message->is_read ? 'Đã đọc' : 'Chưa đọc' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-blue-600 hover:text-blue-900" title="Xem">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="mailto:{{ $message->email }}" class="text-green-600 hover:text-green-900" title="Trả lời email">
                                    <i class="fas fa-reply"></i>
                                </a>
                                <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('Xóa tin nhắn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
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
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
