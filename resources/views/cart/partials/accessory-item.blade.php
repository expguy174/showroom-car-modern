@php 
    $unit = $item->item->price ?? 0;
    $itemTotal = $unit * $item->quantity; 
    $imageUrl = asset('images/default-accessory.jpg');
    if ($item->item->image_url) {
        $imageUrl = filter_var($item->item->image_url, FILTER_VALIDATE_URL) ? $item->item->image_url : asset('storage/'.$item->item->image_url);
    }
@endphp

<tr class="cart-item-row" data-id="{{ $item->id }}">
    <td class="align-top" data-label="Ảnh">
        <a href="{{ route('accessories.show', $item->item->slug ?? $item->item->id) }}" class="block w-24 h-20 rounded-lg overflow-hidden bg-gray-100 border">
            <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $item->item->name }}">
        </a>
    </td>
    <td class="align-top" data-label="Sản phẩm">
        <div class="min-w-0">
            <div class="font-bold text-gray-900 truncate">
                <a href="{{ route('accessories.show', $item->item->slug ?? $item->item->id) }}" class="hover:underline">{{ $item->item->name }}</a>
            </div>
            @if($item->item->brand)
                <div class="text-sm text-gray-600 truncate">{{ $item->item->brand }}</div>
            @endif
            @if($item->item->short_description)
                <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($item->item->short_description, 90) }}</div>
            @endif
        </div>
    </td>
    <td class="align-top text-center" data-label="Số lượng">
        <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
            <button type="button" class="quantity-control px-2 py-1 text-gray-700 hover:bg-gray-100 qty-decrease" data-id="{{ $item->id }}" aria-label="Giảm">-</button>
            <input type="number" value="{{ $item->quantity }}" min="1" step="1" inputmode="numeric" pattern="[0-9]*" class="w-12 text-center border-0 cart-qty-input" data-update-url="{{ route('cart.update', $item->id) }}" data-csrf="{{ csrf_token() }}" data-id="{{ $item->id }}">
            <button type="button" class="quantity-control px-2 py-1 text-gray-700 hover:bg-gray-100 qty-increase" data-id="{{ $item->id }}" aria-label="Tăng">+</button>
        </div>
    </td>
    <td class="text-center align-top" data-label="Giá">
        <div class="text-gray-900 font-bold"><span class="item-price" data-price="{{ (int) $unit }}">{{ number_format($unit) }}</span> đ</div>
    </td>
    <td class="text-center align-top" data-label="Tổng">
        <div class="text-gray-900 font-bold"><span class="item-total" data-id="{{ $item->id }}">{{ number_format($itemTotal) }}</span> đ</div>
    </td>
    <td class="text-center align-top" data-label="Thao tác">
        <button type="button" aria-label="Xóa" title="Xóa" class="remove-item-btn btn-delete" data-id="{{ $item->id }}" data-url="{{ route('cart.remove', $item->id) }}" data-csrf="{{ csrf_token() }}">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
