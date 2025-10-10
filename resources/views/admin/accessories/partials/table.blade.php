<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200" style="table-layout: fixed;">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 30%;">Phụ kiện</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Danh mục</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 20%;">Giá bán</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Trạng thái</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Thao tác</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($accessories as $accessory)
            <tr class="hover:bg-gray-50 transition-colors">
                {{-- Debug: Check accessory data --}}
                @if(config('app.debug'))
                    <!-- DEBUG: Accessory ID {{ $accessory->id ?? 'NULL' }}, Gallery: {{ json_encode($accessory->gallery ?? 'NULL') }} -->
                @endif
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            @php
                                $resolveImage = function($value, $fallbackText = 'No Image'){
                                    $val = trim((string) $value);
                                    if ($val === '') {
                                        return 'https://via.placeholder.com/400x400/111827/ffffff?text=' . urlencode($fallbackText);
                                    }
                                    if (filter_var($val, FILTER_VALIDATE_URL)) {
                                        return $val;
                                    }
                                    return 'https://placehold.co/400x400/111827/ffffff?text=' . urlencode($val);
                                };
                                
                                // Get gallery images from JSON field - SAFE VERSION
                                $galleryRaw = $accessory->gallery ?? null;
                                $gallery = [];
                                
                                // Safe gallery processing
                                try {
                                    if (is_array($galleryRaw)) {
                                        $gallery = $galleryRaw;
                                    } elseif (is_string($galleryRaw) && !empty($galleryRaw)) {
                                        $decoded = json_decode($galleryRaw, true);
                                        $gallery = is_array($decoded) ? $decoded : [];
                                    }
                                } catch (Exception $e) {
                                    $gallery = [];
                                }
                                
                                // Get primary image URL from gallery - EXTRA SAFE
                                $primaryImageUrl = '';
                                if (is_array($gallery) && !empty($gallery)) {
                                    // First, try to find primary image
                                    $primaryImage = null;
                                    foreach ($gallery as $img) {
                                        if (is_array($img) && isset($img['is_primary']) && $img['is_primary']) {
                                            $primaryImage = $img;
                                            break;
                                        }
                                    }
                                    
                                    // If no primary, use first image
                                    if (!$primaryImage && isset($gallery[0])) {
                                        $primaryImage = $gallery[0];
                                    }
                                    
                                    // Extract URL from image
                                    if ($primaryImage) {
                                        if (is_array($primaryImage)) {
                                            // New format: array with url/title/etc
                                            $primaryImageUrl = $primaryImage['url'] ?? $primaryImage['file'] ?? '';
                                        } elseif (is_string($primaryImage)) {
                                            // Old format: direct URL string
                                            $primaryImageUrl = $primaryImage;
                                        }
                                    }
                                }
                                
                                $mainImage = $resolveImage(
                                    $primaryImageUrl,
                                    $accessory->name ?? 'No Image'
                                );
                            @endphp
                            
                            <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" 
                                 src="{{ $mainImage }}" 
                                 alt="{{ $accessory->name }}"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="h-12 w-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center text-white font-semibold text-sm" style="display: none;">
                                {{ strtoupper(substr($accessory->name, 0, 2)) }}
                            </div>
                        </div>
                        <div class="ml-4 min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $accessory->name }}</div>
                            <div class="text-sm text-gray-500 truncate">
                                SKU: {{ $accessory->sku }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $accessory->vietnamese_category }}</div>
                    @if($accessory->vietnamese_subcategory)
                        <div class="text-sm text-gray-500">{{ $accessory->vietnamese_subcategory }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        <div class="font-semibold text-lg text-blue-600">
                            {{ number_format($accessory->current_price) }}đ
                        </div>
                        @if($accessory->is_on_sale && $accessory->current_price < $accessory->base_price)
                            <div class="text-xs text-gray-500 line-through">
                                {{ number_format($accessory->base_price) }}đ
                            </div>
                            <div class="text-xs text-orange-600 font-medium">
                                <i class="fas fa-tags mr-1"></i>
                                Giảm {{ number_format((($accessory->base_price - $accessory->current_price) / $accessory->base_price) * 100, 1) }}%
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col items-start gap-1 w-full">
                        <!-- Main Status - Using StatusToggle Component -->
                        <div class="w-full">
                            <x-admin.status-toggle 
                                :item-id="$accessory->id"
                                :current-status="$accessory->is_active"
                                entity-type="accessory" />
                        </div>
                        
                        <!-- Additional Badges -->
                        @if($accessory->is_featured)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-star mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Nổi bật</span>
                            </span>
                        @endif
                        
                        @if($accessory->is_on_sale)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-orange-100 text-orange-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-percentage mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Khuyến mãi</span>
                            </span>
                        @endif
                        
                        @if($accessory->is_new_arrival)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-blue-100 text-blue-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-plus-circle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Mới</span>
                            </span>
                        @endif
                        
                        @if($accessory->is_bestseller)
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-purple-100 text-purple-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-fire mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Bán chạy</span>
                            </span>
                        @endif

                        @if($accessory->stock_status === 'out_of_stock')
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-red-100 text-red-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-exclamation-triangle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Hết hàng</span>
                            </span>
                        @elseif($accessory->stock_status === 'low_stock')
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-md font-medium bg-yellow-100 text-yellow-800 whitespace-nowrap min-h-[20px]">
                                <i class="fas fa-exclamation-circle mr-1.5 w-3 h-3 flex-shrink-0"></i>
                                <span>Sắp hết</span>
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                    <x-admin.table-actions 
                        :item="$accessory"
                        show-route="admin.accessories.show"
                        edit-route="admin.accessories.edit"
                        delete-route="admin.accessories.destroy"
                        :has-toggle="true"
                        :delete-data="[
                            'category' => $accessory->category ?? '',
                            'stock-quantity' => $accessory->stock_quantity
                        ]" />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-cogs text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-500 text-lg">Không tìm thấy phụ kiện nào</p>
                        <p class="text-gray-400 text-sm mt-1">Thử thay đổi bộ lọc hoặc tìm kiếm</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($accessories->hasPages())
<div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
    <x-admin.pagination :paginator="$accessories->appends(request()->query())" />
</div>
@endif
