@php 
    $baseUnit = ($item->item_type === 'car_variant' && method_exists($item->item, 'getPriceWithColorAdjustment'))
        ? $item->item->getPriceWithColorAdjustment($item->color_id)
        : ($item->item->price ?? 0);
    // Add-on fees from session meta
    $meta = session('cart_item_meta.' . $item->id, []);
    $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
    $optIds = collect($meta['option_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
    $selectedFeatures = !empty($featIds) ? \App\Models\CarVariantFeature::whereIn('id', $featIds)->get() : collect();
    $selectedOptions  = !empty($optIds) ? \App\Models\CarVariantOption::whereIn('id', $optIds)->get() : collect();
    $addonSum = 0;
    foreach ($selectedFeatures as $sf) { $addonSum += (float) ($sf->package_price ?? $sf->price ?? 0); }
    foreach ($selectedOptions as $so) { $addonSum += (float) ($so->package_price ?? $so->price ?? 0); }
    $displayUnit = $baseUnit + $addonSum;
    $itemTotal = $displayUnit * $item->quantity; 
    $baseTotal = $baseUnit * $item->quantity;
    $addonTotal = $addonSum * $item->quantity;
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
            @if($selectedFeatures->count() > 0 || $selectedOptions->count() > 0)
            <div class="mt-2 text-xs text-gray-600 space-y-1">
                @if($selectedFeatures->count() > 0)
                <div>
                    <span class="font-medium text-gray-800">Tính năng đã chọn:</span>
                    <ul class="list-disc list-inside">
                        @foreach($selectedFeatures as $sf)
                            <li>{{ $sf->feature_name }} @php $fee=(float)($sf->package_price ?? $sf->price ?? 0); @endphp @if($fee>0)<span class="text-indigo-700 font-semibold">(+{{ number_format($fee,0,',','.') }}đ)</span>@endif</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if($selectedOptions->count() > 0)
                <div>
                    <span class="font-medium text-gray-800">Tuỳ chọn đã chọn:</span>
                    <ul class="list-disc list-inside">
                        @foreach($selectedOptions as $so)
                            @php $fee=(float)($so->package_price ?? $so->price ?? 0); @endphp
                            <li>{{ $so->option_name }} @if($fee>0)<span class="text-indigo-700 font-semibold">(+{{ number_format($fee,0,',','.') }}đ)</span>@endif</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            @endif
            @if($item->item_type === 'car_variant' && isset($item->item->colors) && $item->item->colors->count() > 0)
            <div class="mt-1 flex items-center gap-2">
                @foreach($item->item->colors as $color)
                    <button type="button" class="color-option w-6 h-6 rounded-full border-2 {{ $item->color_id == $color->id ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-300' }}" title="{{ $color->color_name }}"
                        data-bg-hex="{{ \App\Helpers\ColorHelper::getColorHex($color->color_name) }}" data-color-id="{{ $color->id }}" data-color-name="{{ $color->color_name }}" data-item-id="{{ $item->id }}"
                        data-update-url="{{ route('user.cart.update', $item->id) }}" data-csrf="{{ csrf_token() }}"></button>
                @endforeach
            </div>
            @endif

            @if($item->item_type === 'car_variant' && isset($item->item->featuresRelation) && $item->item->featuresRelation->count() > 0)
            <div class="mt-2 p-2 rounded-lg border border-gray-100 bg-gray-50">
                <div class="text-xs font-semibold text-gray-800 mb-1">Chọn tính năng</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                    @foreach($item->item->featuresRelation as $f)
                        @php $fee=(float)($f->package_price ?? $f->price ?? 0); $inc=(bool)($f->is_included ?? false) || (($f->availability ?? 'standard')==='standard'); @endphp
                        <label class="inline-flex items-center gap-2 text-xs">
                            <input type="checkbox" class="cart-feature" data-update-url="{{ route('user.cart.update', $item->id) }}" data-id="{{ $item->id }}" value="{{ $f->id }}" {{ $inc ? 'checked disabled' : (in_array($f->id, $featIds ?? []) ? 'checked' : '') }}>
                            <span class="text-gray-700">{{ $f->feature_name }}</span>
                            @if($fee>0)<span class="text-indigo-700 font-semibold">+{{ number_format($fee,0,',','.') }}đ</span>@endif
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            @if($item->item_type === 'car_variant' && isset($item->item->options) && $item->item->options->count() > 0)
            <div class="mt-2 p-2 rounded-lg border border-gray-100 bg-gray-50">
                <div class="text-xs font-semibold text-gray-800 mb-1">Chọn tuỳ chọn</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
                    @foreach($item->item->options as $o)
                        @php $fee=(float)($o->package_price ?? $o->price ?? 0); $inc=(bool)($o->is_included ?? false); @endphp
                        <label class="inline-flex items-center gap-2 text-xs">
                            <input type="checkbox" class="cart-option" data-update-url="{{ route('user.cart.update', $item->id) }}" data-id="{{ $item->id }}" value="{{ $o->id }}" {{ $inc ? 'checked disabled' : (in_array($o->id, $optIds ?? []) ? 'checked' : '') }}>
                            <span class="text-gray-700">{{ $o->option_name }}</span>
                            @if($fee>0)<span class="text-indigo-700 font-semibold">+{{ number_format($fee,0,',','.') }}đ</span>@endif
                        </label>
                    @endforeach
                </div>
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
        <div class="text-gray-900 font-bold"><span class="item-price" data-price="{{ (int) $displayUnit }}">{{ number_format($displayUnit) }}</span> đ</div>
        <div class="text-[11px] text-gray-500">Gốc: {{ number_format($baseUnit) }} đ</div>
        @if($addonSum > 0)
        <div class="text-[11px] text-indigo-700">+ Add-on: {{ number_format($addonSum) }} đ</div>
        @endif
    </td>
    <td class="text-right align-top" data-label="Tổng">
        <div class="text-gray-900 font-bold"><span class="item-total" data-id="{{ $item->id }}">{{ number_format($itemTotal) }}</span> đ</div>
        <div class="text-[11px] text-gray-500">Gốc: {{ number_format($baseTotal) }} đ</div>
        @if($addonTotal > 0)
        <div class="text-[11px] text-indigo-700">+ Add-on: {{ number_format($addonTotal) }} đ</div>
        @endif
    </td>
    <td class="text-right align-top" data-label="Thao tác">
        <button type="button" aria-label="Xóa" title="Xóa" class="remove-item-btn btn-delete" data-id="{{ $item->id }}" data-url="{{ route('user.cart.remove', $item->id) }}" data-csrf="{{ csrf_token() }}">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>
