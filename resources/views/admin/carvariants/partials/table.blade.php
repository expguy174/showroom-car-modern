<div class="overflow-x-auto -mx-4 sm:mx-0">
    <div class="inline-block min-w-full align-middle">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <span class="lg:hidden">Sản phẩm</span>
                        <span class="hidden lg:inline">Phiên bản xe</span>
                    </th>
                    <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dòng xe</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá bán</th>
                    <th class="hidden sm:table-cell px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
            </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($carVariants as $variant)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-3 sm:px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 sm:h-12 sm:w-12">
                            @if($variant->images->where('is_main', true)->first())
                                <img class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg object-cover border border-gray-200" 
                                     src="{{ $variant->images->where('is_main', true)->first()->image_url }}" 
                                     alt="{{ $variant->name }}">
                            @else
                                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-semibold text-xs sm:text-sm">
                                    {{ strtoupper(substr($variant->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $variant->name }}</div>
                            <div class="text-xs sm:text-sm text-gray-500 truncate">
                                {{-- Mobile: Show model info --}}
                                <div class="md:hidden">
                                    {{ $variant->carModel->carBrand->name }} {{ $variant->carModel->name }}
                                </div>
                                {{-- Desktop: Show SKU --}}
                                <div class="hidden md:block">
                                    @if($variant->sku)
                                        <span class="inline-flex items-center">
                                            <i class="fas fa-barcode text-gray-400 mr-1"></i>
                                            {{ $variant->sku }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            {{-- Mobile: Show status badges --}}
                            <div class="sm:hidden mt-1 flex flex-wrap gap-1">
                                <x-status-toggle 
                                    :item-id="$variant->id"
                                    :current-status="$variant->is_active" />
                                @if($variant->is_featured)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Nổi bật
                                    </span>
                                @endif
                                @if($variant->is_on_sale)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                        KM
                                    </span>
                                @endif
                                @if($variant->is_new_arrival)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Mới
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
                <td class="hidden md:table-cell px-3 sm:px-6 py-4">
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
                <td class="px-3 sm:px-6 py-4">
                    <div class="text-sm text-gray-900">
                        <div class="font-semibold text-base sm:text-lg text-blue-600">
                            {{ number_format($variant->current_price ?: $variant->base_price) }}đ
                        </div>
                        @if($variant->is_on_sale && $variant->current_price < $variant->base_price)
                            <div class="text-xs text-gray-500 line-through">
                                {{ number_format($variant->base_price) }}đ
                            </div>
                            <div class="text-xs text-orange-600 font-medium">
                                <i class="fas fa-tags mr-1"></i>
                                <span class="hidden sm:inline">Giảm </span>{{ number_format((($variant->base_price - $variant->current_price) / $variant->base_price) * 100, 1) }}%
                            </div>
                        @endif
                    </div>
                </td>
                <td class="hidden sm:table-cell px-3 sm:px-6 py-4">
                    <div class="flex flex-col gap-1">
                        <!-- Main Status - Using StatusToggle Component -->
                        <x-status-toggle 
                            :item-id="$variant->id"
                            :current-status="$variant->is_active" />
                        
                        <!-- Additional Badges -->
                        @if($variant->is_featured)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-star mr-1"></i>
                                <span class="hidden lg:inline">Nổi bật</span>
                                <span class="lg:hidden">⭐</span>
                            </span>
                        @endif
                        @if($variant->is_on_sale)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-tags mr-1"></i>
                                KM
                            </span>
                        @endif
                        @if($variant->is_new_arrival)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-certificate mr-1"></i>
                                <span class="hidden lg:inline">Mới</span>
                                <span class="lg:hidden">🆕</span>
                            </span>
                        @endif
                        @if($variant->is_bestseller)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-pink-100 text-pink-800">
                                <i class="fas fa-fire mr-1"></i>
                                <span class="hidden lg:inline">Hot</span>
                                <span class="lg:hidden">🔥</span>
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-3 sm:px-6 py-4 text-sm font-medium">
                    <div class="flex items-center space-x-1 sm:space-x-2">
                        <!-- View Button -->
                        <a href="{{ route('admin.carvariants.show', $variant) }}" 
                           class="text-green-600 hover:text-green-900 w-4 h-4 flex items-center justify-center" 
                           title="Xem chi tiết">
                            <i class="fas fa-eye w-4 h-4"></i>
                        </a>
                        
                        <!-- Edit Button -->
                        <a href="{{ route('admin.carvariants.edit', $variant) }}" 
                           class="text-blue-600 hover:text-blue-900 w-4 h-4 flex items-center justify-center" 
                           title="Chỉnh sửa">
                            <i class="fas fa-edit w-4 h-4"></i>
                        </a>
                        
                        <!-- Toggle Status Button -->
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
                        
                        <!-- Delete Button -->
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
                        
                        <!-- Hidden Delete Form -->
                        <form id="delete-form-{{ $variant->id }}" action="{{ route('admin.carvariants.destroy', $variant) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-3 sm:px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-cubes text-gray-400 text-3xl sm:text-4xl mb-4"></i>
                        <p class="text-gray-500 text-base sm:text-lg">Không tìm thấy phiên bản xe nào</p>
                        <p class="text-gray-400 text-xs sm:text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

{{-- Pagination --}}
@if($carVariants->hasPages())
<div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    <x-admin.pagination :paginator="$carVariants->appends(request()->query())" />
</div>
@endif
