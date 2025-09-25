<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 280px;">Phiên bản xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 200px;">Mẫu xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 160px;">Giá bán</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 140px;">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 120px;">Màu sắc</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 140px;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($carVariants as $variant)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap" style="width: 280px;">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            @if($variant->images->where('is_main', true)->first())
                                <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" 
                                     src="{{ $variant->images->where('is_main', true)->first()->image_url }}" 
                                     alt="{{ $variant->name }}">
                            @else
                                <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm">
                                    {{ strtoupper(substr($variant->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $variant->name }}</div>
                            <div class="text-sm text-gray-500 truncate">
                                @if($variant->sku)
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-barcode text-gray-400 mr-1"></i>
                                        {{ $variant->sku }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap" style="width: 200px;">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <img class="h-8 w-8 rounded object-contain bg-white p-1 border border-gray-200" 
                                 src="{{ $variant->carModel->carBrand->logo_url }}" 
                                 alt="{{ $variant->carModel->carBrand->name }}">
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $variant->carModel->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $variant->carModel->carBrand->name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap" style="width: 160px;">
                    <div class="text-sm text-gray-900">
                        <div class="font-semibold text-lg text-blue-600">
                            {{ number_format($variant->current_price ?: $variant->base_price) }}đ
                        </div>
                        @if($variant->is_on_sale && $variant->current_price < $variant->base_price)
                            <div class="text-xs text-gray-500 line-through">
                                {{ number_format($variant->base_price) }}đ
                            </div>
                            <div class="text-xs text-orange-600 font-medium">
                                <i class="fas fa-tags mr-1"></i>
                                Giảm {{ number_format((($variant->base_price - $variant->current_price) / $variant->base_price) * 100, 1) }}%
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap" style="width: 140px;">
                    <div class="space-y-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-full {{ $variant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i class="fas {{ $variant->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1"></i>
                            <span class="truncate">{{ $variant->is_active ? 'Hoạt động' : 'Tạm dừng' }}</span>
                        </span>
                        @if($variant->is_featured)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                Nổi bật
                            </span>
                        @endif
                        @if($variant->is_on_sale)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-tags mr-1"></i>
                                Khuyến mãi
                            </span>
                        @endif
                        @if($variant->is_new_arrival)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-certificate mr-1"></i>
                                Mới về
                            </span>
                        @endif
                        @if($variant->is_bestseller)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                <i class="fas fa-fire mr-1"></i>
                                Bán chạy
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" style="width: 120px;">
                    <div class="flex items-center">
                        <i class="fas fa-palette text-gray-400 mr-2 flex-shrink-0"></i>
                        <span class="truncate">{{ $variant->colors()->count() }} màu</span>
                    </div>
                    @if($variant->colors()->count() > 0)
                        <div class="flex items-center mt-1 space-x-1">
                            @foreach($variant->colors()->limit(3)->get() as $color)
                                <div class="w-3 h-3 rounded-full border border-gray-300" 
                                     style="background-color: {{ $color->hex_code ?? '#cccccc' }};"
                                     title="{{ $color->name }}"></div>
                            @endforeach
                            @if($variant->colors()->count() > 3)
                                <span class="text-xs text-gray-400">+{{ $variant->colors()->count() - 3 }}</span>
                            @endif
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="width: 140px;">
                    <div class="flex items-center justify-center space-x-3">
                        @if($variant->is_active)
                            <button class="text-orange-600 hover:text-orange-900 status-toggle w-4 h-4 flex items-center justify-center" 
                                    title="Tạm dừng" 
                                    data-variant-id="{{ $variant->id }}" 
                                    data-status="false">
                                <i class="fas fa-pause w-4 h-4"></i>
                            </button>
                        @else
                            <button class="text-green-600 hover:text-green-900 status-toggle w-4 h-4 flex items-center justify-center" 
                                    title="Kích hoạt" 
                                    data-variant-id="{{ $variant->id }}" 
                                    data-status="true">
                                <i class="fas fa-play w-4 h-4"></i>
                            </button>
                        @endif
                        <a href="{{ route('admin.carvariants.edit', $variant) }}" 
                           class="text-blue-600 hover:text-blue-900 w-4 h-4 flex items-center justify-center" 
                           title="Chỉnh sửa">
                            <i class="fas fa-edit w-4 h-4"></i>
                        </a>
                        <button 
                            class="text-red-600 hover:text-red-900 delete-btn w-4 h-4 flex items-center justify-center" 
                            title="Xóa"
                            data-variant-id="{{ $variant->id }}"
                            data-variant-name="{{ $variant->name }}"
                            data-model-name="{{ $variant->carModel->carBrand->name }} {{ $variant->carModel->name }}"
                            data-colors-count="{{ $variant->colors()->count() }}"
                            data-images-count="{{ $variant->images()->count() }}">
                            <i class="fas fa-trash w-4 h-4"></i>
                        </button>
                        <form id="delete-form-{{ $variant->id }}" action="{{ route('admin.carvariants.destroy', $variant) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-cubes text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Không tìm thấy phiên bản xe nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($carVariants->hasPages())
<div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    {{ $carVariants->appends(request()->query())->links() }}
</div>
@endif
