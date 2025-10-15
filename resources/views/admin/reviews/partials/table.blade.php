<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Người đánh giá</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Sản phẩm</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 15%;">Tiêu đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 12%;">Đánh giá</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 23%;">Nội dung</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 10%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap" style="width: 10%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($reviews as $review)
            <tr class="hover:bg-gray-50" data-review-id="{{ $review->id }}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $review->user->userProfile->name ?? $review->user->email }}
                    </div>
                    <div class="text-xs text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900 whitespace-nowrap">
                        {{ $review->reviewable->name ?? 'N/A' }}
                    </div>
                    <div class="text-xs text-gray-500">{{ class_basename($review->reviewable_type) }}</div>
                </td>
                <td class="px-6 py-4">
                    @if($review->title)
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $review->title }}</p>
                    @else
                        <span class="text-sm text-gray-400 italic">Không có tiêu đề</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="ml-2 text-sm text-gray-600">{{ $review->rating }}/5</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-900 truncate">{{ $review->comment }}</p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center status-cell">
                    @if($review->is_approved)
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Đã duyệt
                    </span>
                    @else
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1"></i>Chờ duyệt
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm actions-cell">
                    <div class="flex items-center justify-center gap-2">
                        @if(!$review->is_approved)
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline approve-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-green-600 hover:text-green-900" title="Duyệt">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline reject-form">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-orange-600 hover:text-orange-900" title="Bỏ duyệt">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif
                        <button type="button" 
                                class="text-red-600 hover:text-red-900 delete-btn" 
                                data-review-id="{{ $review->id }}"
                                data-review-name="{{ addslashes($review->user->userProfile->name ?? $review->user->email) }}"
                                title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-star text-4xl mb-2"></i>
                    <p>Chưa có đánh giá nào</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    {{-- Pagination --}}
    @if($reviews->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        <x-admin.pagination :paginator="$reviews" />
    </div>
    @endif
</div>
