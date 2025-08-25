@php 
    $unit = ($item->item_type === 'car_variant' && method_exists($item->item, 'getPriceWithColorAdjustment'))
        ? $item->item->getPriceWithColorAdjustment($item->color_id)
        : ($item->item->price ?? 0);
    $itemTotal = $unit * $item->quantity; 
    $imageUrl = asset('images/default-car.jpg');
    if ($item->item_type === 'car_variant' && $item->item->images->isNotEmpty()) {
        $first = $item->item->images->first();
        if ($first->image_url) {
            $imageUrl = filter_var($first->image_url, FILTER_VALIDATE_URL) ? $first->image_url : asset('storage/'.$first->image_url);
        } elseif ($first->image_path) {
            $imageUrl = asset('storage/'.$first->image_path);
        }
    }
@endphp

<tr class="cart-item-row" data-id="{{ $item->id }}">
    <td class="align-top" data-label="Ảnh">
        <a href="{{ route('car-variants.show', $item->item->slug ?? $item->item->id) }}" class="block w-24 h-20 rounded-lg overflow-hidden bg-gray-100 border">
            <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $item->item->name }}">
        </a>
    </td>
    <td class="align-top" data-label="Sản phẩm">
        <div class="min-w-0">
            <div class="font-bold text-gray-900 truncate">
                <a href="{{ route('car-variants.show', $item->item->slug ?? $item->item->id) }}" class="hover:underline">{{ $item->item->name }}</a>
            </div>
            @if($item->item->carModel && $item->item->carModel->carBrand)
                <div class="text-sm text-gray-600 truncate">{{ $item->item->carModel->carBrand->name }} • {{ $item->item->carModel->name }}</div>
            @endif
            @php
                $brief = null;
                if ($item->item->carModel && !empty($item->item->carModel->description)) {
                    $brief = Str::limit(strip_tags($item->item->carModel->description), 90);
                }
            @endphp
            @if($brief)
                <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $brief }}</div>
            @endif
            <div class="mt-2 text-sm text-gray-700">
                Màu đã chọn: <span class="font-medium selected-color-name">{{ $item->color->color_name ?? 'Chưa chọn' }}</span>
            </div>
            @if($item->item_type === 'car_variant' && isset($item->item->colors) && $item->item->colors->count() > 0)
            <div class="mt-1 flex items-center gap-2">
                @foreach($item->item->colors as $color)
                    <button type="button" class="color-option w-6 h-6 rounded-full border-2 {{ $item->color_id == $color->id ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-300' }}" title="{{ $color->color_name }}"
                        data-bg-hex="{{ \App\Helpers\ColorHelper::getColorHex($color->color_name) }}" data-color-id="{{ $color->id }}" data-color-name="{{ $color->color_name }}" data-item-id="{{ $item->id }}"
                        data-update-url="{{ route('user.cart.update', $item->id) }}" data-csrf="{{ csrf_token() }}"></button>
                @endforeach
            </div>
            @endif
        </div>
    </td>
    <td class="align-top" data-label="Số lượng">
        <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-decrease" data-id="{{ $item->id }}" aria-label="Giảm">-</button>
            <input type="number" value="{{ $item->quantity }}" min="1" step="1" inputmode="numeric" pattern="[0-9]*" class="w-14 text-center border-0 cart-qty-input" data-update-url="{{ route('user.cart.update', $item->id) }}" data-csrf="{{ csrf_token() }}" data-id="{{ $item->id }}">
            <button type="button" class="quantity-control px-3 py-2 text-gray-700 hover:bg-gray-100 qty-increase" data-id="{{ $item->id }}" aria-label="Tăng">+</button>
        </div>
    </td>
    <td class="text-right align-top" data-label="Giá">
        <div class="text-gray-900 font-bold"><span class="item-price" data-price="{{ (int) $unit }}">{{ number_format($unit) }}</span> đ</div>
    </td>
    <td class="text-right align-top" data-label="Tổng">
        <div class="text-gray-900 font-bold"><span class="item-total" data-id="{{ $item->id }}">{{ number_format($itemTotal) }}</span> đ</div>
    </td>
    <td class="text-right align-top" data-label="Thao tác">
        <button type="button" aria-label="Xóa" title="Xóa" class="remove-item-btn btn-delete" data-id="{{ $item->id }}" data-url="{{ route('user.cart.remove', $item->id) }}" data-csrf="{{ csrf_token() }}">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
