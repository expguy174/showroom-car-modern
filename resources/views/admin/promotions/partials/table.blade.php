<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khuyến mãi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sử dụng</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($promotions as $promotion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $promotion->name }}</div>
                                <div class="text-sm text-gray-500">{{ $promotion->code }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($promotion->type)
                                @case('percentage')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-percent mr-1"></i>
                                        Giảm theo %
                                    </span>
                                    @break
                                @case('fixed_amount')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-dollar-sign mr-1"></i>
                                        Giảm cố định
                                    </span>
                                    @break
                                @case('free_shipping')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-shipping-fast mr-1"></i>
                                        Miễn phí ship
                                    </span>
                                    @break
                                @case('brand_specific')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-tags mr-1"></i>
                                        Theo thương hiệu
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Khác
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($promotion->type == 'percentage')
                                {{ $promotion->discount_value }}%
                            @elseif($promotion->type == 'fixed_amount')
                                {{ number_format($promotion->discount_value, 0, ',', '.') }}đ
                            @elseif($promotion->type == 'free_shipping')
                                <span class="text-green-600 font-medium">Miễn phí ship</span>
                            @elseif($promotion->type == 'brand_specific')
                                @if($promotion->discount_value > 0)
                                    {{ $promotion->discount_value }}%
                                @else
                                    <span class="text-orange-600 font-medium">Theo thương hiệu</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $promotion->start_date ? $promotion->start_date->format('d/m/Y') : '-' }}</div>
                            <div class="text-gray-500">{{ $promotion->end_date ? $promotion->end_date->format('d/m/Y') : 'Không giới hạn' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ $promotion->usage_count ?? 0 }} / {{ $promotion->usage_limit ?? '∞' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex flex-col items-center gap-1 w-full">
                                <!-- Main Status - Using StatusToggle Component -->
                                <div class="w-full flex justify-center">
                                    <x-admin.status-toggle 
                                        :item-id="$promotion->id"
                                        :current-status="$promotion->is_active"
                                        entity-type="promotion" />
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <div class="flex items-center justify-center space-x-1">
                                {{-- Status Toggle --}}
                                <button type="button" 
                                        class="text-{{ $promotion->is_active ? 'orange' : 'green' }}-600 hover:text-{{ $promotion->is_active ? 'orange' : 'green' }}-900 status-toggle w-4 h-4 flex items-center justify-center"
                                        data-promotion-id="{{ $promotion->id }}"
                                        data-status="{{ $promotion->is_active ? 'false' : 'true' }}"
                                        title="{{ $promotion->is_active ? 'Tạm dừng' : 'Kích hoạt' }}">
                                    <i class="fas fa-{{ $promotion->is_active ? 'pause' : 'play' }} w-4 h-4"></i>
                                </button>

                                {{-- Show Button --}}
                                <a href="{{ route('admin.promotions.show', $promotion->id) }}" 
                                   class="text-gray-600 hover:text-gray-900 w-4 h-4 flex items-center justify-center"
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye w-4 h-4"></i>
                                </a>

                                {{-- Edit Button --}}
                                <a href="{{ route('admin.promotions.edit', $promotion) }}" 
                                   class="text-blue-600 hover:text-blue-900 w-4 h-4 flex items-center justify-center"
                                   title="Chỉnh sửa">
                                    <i class="fas fa-edit w-4 h-4"></i>
                                </a>

                                {{-- Delete Button --}}
                                <button type="button" 
                                        class="text-red-600 hover:text-red-900 w-4 h-4 flex items-center justify-center delete-btn"
                                        data-promotion-id="{{ $promotion->id }}"
                                        data-promotion-name="{{ $promotion->name }}"
                                        data-promotion-code="{{ $promotion->code }}"
                                        data-promotion-type="{{ $promotion->type }}"
                                        data-promotion-value="{{ $promotion->discount_value }}"
                                        data-usage-count="{{ $promotion->usage_count ?? 0 }}"
                                        title="Xóa">
                                    <i class="fas fa-trash w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-tags text-gray-400 text-5xl mb-4"></i>
                                <p class="text-gray-500 text-lg font-medium">Không có khuyến mãi nào</p>
                                <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc để xem kết quả khác</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($promotions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            <x-admin.pagination :paginator="$promotions" />
        </div>
        @endif
    </div>
</div>
