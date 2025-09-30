<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 30%;">Phiên bản xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Dòng xe</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Giá bán</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($carVariants as $variant)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
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
                        <div class="ml-4 min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $variant->name }}</div>
                            <div class="text-sm text-gray-500 truncate">{{ $variant->engine_type ?? 'Chưa cập nhật' }}</div>
                            @if($variant->sku)
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-barcode text-gray-400 mr-1"></i>
                                        {{ $variant->sku }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
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
                <td class="px-6 py-4 whitespace-nowrap">
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
                <td class="px-6 py-4">
                    <div class="flex flex-col items-start gap-1 w-full">
                        <!-- Main Status - Using StatusToggle Component -->
                        <div class="w-full">
                            <x-admin.status-toggle 
                                :item-id="$variant->id"
                                :current-status="$variant->is_active"
                                entity-type="carvariant" />
                        </div>
                        
                        <!-- Additional Badges - Uniform height and spacing -->
                        @if($variant->is_featured)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-star mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Nổi bật</span>
                            </span>
                        @endif
                        
                        @if($variant->is_on_sale)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-orange-100 text-orange-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-percentage mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Khuyến mãi</span>
                            </span>
                        @endif
                        
                        @if($variant->is_new_arrival)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-blue-100 text-blue-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-plus-circle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Mới</span>
                            </span>
                        @endif
                        
                        @if($variant->is_bestseller)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-purple-100 text-purple-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-fire mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Bán chạy</span>
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <x-admin.table-actions 
                        :item="$variant"
                        show-route="admin.carvariants.show"
                        edit-route="admin.carvariants.edit"
                        delete-route="admin.carvariants.destroy"
                        :has-toggle="true"
                        :delete-data="[
                            'model-name' => $variant->carModel->name ?? '',
                            'colors-count' => $variant->colors->count(),
                            'images-count' => $variant->images->count()
                        ]" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
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
    <x-admin.pagination :paginator="$carVariants->appends(request()->query())" />
</div>
@endif
