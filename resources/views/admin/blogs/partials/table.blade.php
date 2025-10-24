{{-- Blogs Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bài viết</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đăng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tác giả</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($blogs as $blog)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-10 w-10">
                                @php $img = $blog->image_url ?? null; @endphp
                                @if($img)
                                <img class="h-10 w-10 rounded-lg object-cover" src="{{ $img }}" alt="{{ $blog->title }}">
                                @else
                                <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-gray-400 text-sm"></i>
                                </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ $blog->title }}</div>
                                @if($blog->excerpt)
                                <div class="text-xs text-gray-500 truncate">{{ Str::limit($blog->excerpt, 60) }}</div>
                                @endif
                                <div class="text-xs text-gray-500">ID: #{{ $blog->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($blog->is_published)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Đã đăng
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-edit mr-1"></i>Bản nháp
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($blog->published_at)
                        {{ $blog->published_at->format('d/m/Y H:i') }}
                        @else
                        <span class="text-gray-400">Chưa đăng</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ optional($blog->admin)->name ?? 'Admin' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.blogs.edit', $blog) }}" 
                               class="text-blue-600 hover:text-blue-900" 
                               title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="text-red-600 hover:text-red-900 delete-btn" 
                                    data-blog-id="{{ $blog->id }}"
                                    data-blog-title="{{ addslashes($blog->title) }}"
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
                            <i class="fas fa-newspaper text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg font-medium">Không tìm thấy bài viết nào</p>
                            <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tạo bài viết mới</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($blogs->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$blogs" />
    </div>
    @endif
</div>
