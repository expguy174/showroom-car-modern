@php 
    // Normalize price data
    $originalPriceBeforeDiscount = (float) ($item->item->base_price ?? 0);
    $currentPrice = (float) ($item->item->current_price ?? 0);
    $hasDiscount = $currentPrice > 0 && $originalPriceBeforeDiscount > $currentPrice;
    $discountPercentage = $hasDiscount && $originalPriceBeforeDiscount > 0
        ? (int) round((($originalPriceBeforeDiscount - $currentPrice) / $originalPriceBeforeDiscount) * 100)
        : 0;
    $discountAmount = $hasDiscount ? max(0, $originalPriceBeforeDiscount - $currentPrice) : 0;

    // Accessory unit equals current price (no color/addon adjustments)
    $baseUnit = $currentPrice;
    $displayUnit = $baseUnit;
    $itemTotal = $displayUnit * $item->quantity; 

    // Image (align with components/accessory-card)
    $galleryRaw = $item->item->gallery ?? null;
    $gallery = is_array($galleryRaw) ? $galleryRaw : (json_decode($galleryRaw ?? '[]', true) ?: []);
    $firstGalleryImg = $gallery[0] ?? null;
    $imageUrl = null;
    if ($firstGalleryImg) {
        $imageUrl = $firstGalleryImg;
    } elseif (!empty($item->item->image_url)) {
        $imageUrl = filter_var($item->item->image_url, FILTER_VALIDATE_URL) ? $item->item->image_url : asset('storage/'.$item->item->image_url);
    } else {
        $imageUrl = asset('images/default-accessory.jpg');
    }
@endphp

<!-- Desktop Table Layout - HIDDEN on mobile via CSS -->
<tr class="cart-item-desktop desktop-only" data-id="{{ $item->id }}" data-item-type="{{ $item->item_type }}" data-color-id="{{ $item->color_id }}" data-base-unit="{{ (int) $baseUnit }}" data-original-price="{{ (int) $originalPriceBeforeDiscount }}" data-current-price="{{ (int) $currentPrice }}">
    <td class="align-middle text-center" data-label="Ảnh">
        <a href="{{ route('accessories.show', $item->item->slug ?? $item->item->id) }}" class="inline-block w-24 h-20 rounded-lg overflow-hidden bg-gray-100 border mx-auto">
            <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $item->item->name }}" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Image';">
        </a>
    </td>
    <td class="align-top" data-label="Sản phẩm">
        <div class="min-w-0">
            <div class="font-bold text-gray-900 truncate">
                <a href="{{ route('accessories.show', $item->item->slug ?? $item->item->id) }}" class="hover:underline">{{ $item->item->name }}</a>
            </div>
            @if(!empty($item->item->brand))
                <div class="text-sm text-gray-600 truncate">{{ $item->item->brand }}</div>
            @endif
            @if(!empty($item->item->short_description))
                <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit(strip_tags($item->item->short_description), 90) }}</div>
            @endif
        </div>
    </td>
    <td class="align-top hidden md:table-cell mobile-hide" data-label="Số lượng">
        <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-decrease" data-id="{{ $item->id }}" aria-label="Giảm">-</button>
            <input type="number" value="{{ $item->quantity }}" min="1" step="1" inputmode="numeric" pattern="[0-9]*" class="w-14 text-center border-0 cart-qty-input" data-update-url="{{ route('user.cart.update', $item->id) }}" data-csrf="{{ csrf_token() }}" data-id="{{ $item->id }}">
            <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-increase" data-id="{{ $item->id }}" aria-label="Tăng">+</button>
        </div>
    </td>

    <td class="text-right align-top hidden md:table-cell mobile-hide" data-label="Giá">
        <div class="text-[11px] text-gray-400 line-through js-price-original" @if(!($originalPriceBeforeDiscount>0)) style="display:none" @endif>
            Gốc: <span class="js-price-original-val">{{ number_format($originalPriceBeforeDiscount,0,',','.') }}</span> đ
        </div>
        <div class="text-[11px] text-red-600 js-price-discount" @if(!($discountPercentage > 0)) style="display:none" @endif>
            Giảm giá: -<span class="js-price-discount-percent">{{ number_format($discountPercentage,0) }}</span>% (<span class="js-price-discount-amount">{{ number_format($discountAmount,0,',','.') }}</span> đ)
        </div>
        <div class="text-[11px] text-gray-700 js-price-current" @if(!($currentPrice>0)) style="display:none" @endif>
            Hiện tại: <span class="js-price-current-val">{{ number_format($currentPrice,0,',','.') }}</span> đ
        </div>
        <div class="border-t border-gray-300 pt-2 mt-2">
            <div class="text-gray-900 font-bold whitespace-nowrap text-sm sm:text-base"><span class="item-total whitespace-nowrap" data-id="{{ $item->id }}">{{ number_format($itemTotal,0,',','.') }}</span> đ</div>
        </div>
    </td>
    <td class="text-center align-middle hidden md:table-cell mobile-hide" data-label="Thao tác" style="vertical-align: middle;">
        <div class="flex items-center justify-center">
            <button type="button" aria-label="Xóa" title="Xóa" class="remove-item-btn w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center" data-id="{{ $item->id }}" data-url="{{ route('user.cart.remove', $item->id) }}" data-csrf="{{ csrf_token() }}">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    </td>
</tr>

<!-- Mobile Card Layout - ONLY visible on mobile -->
<tr class="cart-item-row mobile-only" data-id="{{ $item->id }}" data-item-type="{{ $item->item_type }}" data-color-id="{{ $item->color_id }}" data-base-unit="{{ (int) $baseUnit }}" data-original-price="{{ (int) $originalPriceBeforeDiscount }}" data-current-price="{{ (int) $currentPrice }}">
    <td colspan="5" class="p-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mx-4 mb-4 overflow-hidden">
            <!-- Product Header -->
            <div class="p-4 pb-3">
                <div class="flex items-center gap-3">
                    <!-- Product Image -->
                    <a href="{{ route('accessories.show', $item->item->slug ?? $item->item->id) }}" class="flex-shrink-0 w-20 h-16 rounded-xl overflow-hidden bg-gray-100 border flex items-center justify-center">
                        <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $item->item->name }}" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x600?text=No+Image';">
                    </a>
                    
                    <!-- Product Info -->
                    <div class="min-w-0 flex-1">
                        <div class="font-bold text-gray-900 text-base leading-tight">
                            <a href="{{ route('accessories.show', $item->item->slug ?? $item->item->id) }}" class="hover:text-blue-600 transition-colors">{{ $item->item->name }}</a>
                        </div>
                        @if(!empty($item->item->brand))
                            <div class="text-sm text-gray-600 mt-1">{{ $item->item->brand }}</div>
                        @endif
                        @if(!empty($item->item->short_description))
                            <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit(strip_tags($item->item->short_description), 80) }}</div>
                        @endif
                    </div>
                    
                    <!-- Delete Button -->
                    <button type="button" aria-label="Xóa" title="Xóa" class="remove-item-btn flex-shrink-0 w-8 h-8 rounded-full bg-red-50 text-red-500 flex items-center justify-center" data-id="{{ $item->id }}" data-url="{{ route('user.cart.remove', $item->id) }}" data-csrf="{{ csrf_token() }}">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <!-- Quantity Control -->
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-600 font-medium">Số lượng:</span>
                        <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
                            <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-decrease transition-colors" data-id="{{ $item->id }}" aria-label="Giảm">-</button>
                            <input type="number" value="{{ $item->quantity }}" min="1" step="1" inputmode="numeric" pattern="[0-9]*" class="w-12 text-center border-0 cart-qty-input bg-transparent text-sm font-medium" data-update-url="{{ route('user.cart.update', $item->id) }}" data-csrf="{{ csrf_token() }}" data-id="{{ $item->id }}">
                            <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-increase transition-colors" data-id="{{ $item->id }}" aria-label="Tăng">+</button>
                        </div>
                    </div>
                    
                    <!-- Total Price Info -->
                    <div class="text-right min-w-0 flex-1">
                        <div class="space-y-1">
                            <div class="text-[11px] text-gray-400 line-through js-price-original" @if(!($originalPriceBeforeDiscount>0)) style="display:none" @endif>
                                Gốc: <span class="js-price-original-val">{{ number_format($originalPriceBeforeDiscount,0,',','.') }}</span> đ
                            </div>
                            <div class="text-[11px] text-red-600 js-price-discount" @if(!($discountPercentage > 0)) style="display:none" @endif>
                                Giảm giá: -<span class="js-price-discount-percent">{{ number_format($discountPercentage,0) }}</span>% (<span class="js-price-discount-amount">{{ number_format($discountAmount,0,',','.') }}</span> đ)
                            </div>
                            <div class="text-[11px] text-gray-700 js-price-current" @if(!($currentPrice>0)) style="display:none" @endif>
                                Hiện tại: <span class="js-price-current-val">{{ number_format($currentPrice,0,',','.') }}</span> đ
                            </div>
                            <div class="text-lg font-bold text-blue-600 mt-2">Giá: <span class="item-total" data-id="{{ $item->id }}">{{ number_format($itemTotal,0,',','.') }}</span> đ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
