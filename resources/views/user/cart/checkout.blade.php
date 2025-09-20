@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-credit-card text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Thanh toán</h1>
                        <p class="text-gray-600">Hoàn tất thông tin để đặt hàng</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('user.cart.index') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay về giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Steps -->
    <div class="bg-white border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div>
                <div class="flex items-center justify-center space-x-8">
                    <a href="{{ route('user.cart.index') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                        <span class="font-medium text-gray-500">Giỏ hàng</span>
                    </a>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                        <span class="font-semibold text-blue-600">Thanh toán</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                        <span class="font-medium text-gray-500">Hoàn tất</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Use only global toast system for errors; no inline alert box here --}}

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 space-y-6">
            <!-- Customer info / Address -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-4 md:px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Thông tin nhận hàng</h2>
                </div>
                <div class="px-4 md:px-6 py-4">
                    <form id="checkout-form" action="{{ route('user.cart.checkout') }}" method="POST" class="space-y-4" novalidate>
                        @csrf
                        <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
                        <input type="hidden" name="promotion_code" id="applied_promotion_code" value="">
                        @if(request()->has('debug'))
                            <input type="hidden" name="debug" value="1">
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                                <input type="text" name="name" value="{{ old('name', optional($user?->userProfile)->name) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone', optional($user?->userProfile)->phone ?? optional(($addresses ?? collect())->firstWhere('is_default', true) ?: ($addresses ?? collect())->first())->phone ?? '') }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                        </div>
                        <div class="pt-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Địa chỉ thanh toán <span class="text-red-500">*</span></label>
                            @if(($addresses ?? collect())->count() > 0)
                            <select name="billing_address_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">— Chọn địa chỉ —</option>
                                @foreach($addresses as $addr)
                                    <option value="{{ $addr->id }}" @selected(old('billing_address_id') == $addr->id || $addr->is_default)>
                                        {{ $addr->contact_name }} - {{ $addr->address }} @if($addr->is_default) (Mặc định) @endif
                                    </option>
                                @endforeach
                            </select>
                            @else
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center gap-2 text-yellow-800">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="text-sm font-medium">Chưa có địa chỉ đã lưu</span>
                                </div>
                                <p class="text-sm text-yellow-700 mt-1">Vui lòng thêm địa chỉ trước khi thanh toán.</p>
                                <a href="{{ route('user.addresses.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-yellow-800 hover:text-yellow-900 mt-2">
                                    <i class="fas fa-plus"></i>
                                    Thêm địa chỉ
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Payment Type Selection -->
                        <div class="pt-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hình thức thanh toán</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                <label class="relative flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-400 cursor-pointer">
                                    <input type="radio" name="payment_type" value="full" class="text-indigo-600 focus:ring-indigo-500" checked />
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">Thanh toán toàn bộ</div>
                                        <div class="text-xs text-gray-500">Thanh toán một lần</div>
                                    </div>
                                </label>
                                <label class="relative flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-400 cursor-pointer">
                                    <input type="radio" name="payment_type" value="finance" class="text-indigo-600 focus:ring-indigo-500" />
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">Trả góp</div>
                                        <div class="text-xs text-gray-500">Vay ngân hàng</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Finance Options (Hidden by default) -->
                        <div id="finance-options-section" class="pt-2 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gói tài chính</label>
                            <div class="space-y-3 mb-4">
                                @foreach(($financeOptions ?? []) as $fo)
                                <label class="relative flex items-start gap-3 p-4 rounded-lg border border-gray-200 hover:border-indigo-400 cursor-pointer">
                                    <input type="radio" name="finance_option_id" value="{{ $fo->id }}" 
                                           data-interest-rate="{{ $fo->interest_rate }}"
                                           data-min-tenure="{{ $fo->min_tenure }}"
                                           data-max-tenure="{{ $fo->max_tenure }}"
                                           data-min-down-payment="{{ $fo->min_down_payment }}"
                                           class="mt-1 text-indigo-600 focus:ring-indigo-500" />
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="text-sm font-semibold text-gray-900">{{ $fo->name }}</div>
                                            <div class="text-sm font-semibold text-indigo-600">{{ $fo->interest_rate }}%/năm</div>
                                        </div>
                                        <div class="text-xs text-gray-600 mb-2">{{ $fo->bank_name }}</div>
                                        <div class="grid grid-cols-2 gap-4 text-xs text-gray-500">
                                            <div>
                                                <span class="font-medium">Thời hạn:</span> {{ $fo->min_tenure }}-{{ $fo->max_tenure }} tháng
                                            </div>
                                            <div>
                                                <span class="font-medium">Trả trước:</span> Từ {{ $fo->min_down_payment }}%
                                            </div>
                                        </div>
                                        @if($fo->description)
                                        <div class="text-xs text-gray-500 mt-1">{{ $fo->description }}</div>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            <!-- Finance Details -->
                            <div id="finance-details" class="hidden space-y-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Trả trước (%)</label>
                                        <input type="number" name="down_payment_percent" min="20" max="80" value="30" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Thời hạn (tháng)</label>
                                        <select name="tenure_months" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="12">12 tháng</option>
                                            <option value="24">24 tháng</option>
                                            <option value="36" selected>36 tháng</option>
                                            <option value="48">48 tháng</option>
                                            <option value="60">60 tháng</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                    <div class="text-center p-3 bg-white rounded-lg">
                                        <div class="text-gray-500">Trả trước</div>
                                        <div id="down-payment-amount" class="font-semibold text-gray-900">0 đ</div>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg">
                                        <div class="text-gray-500">Số tiền vay</div>
                                        <div id="loan-amount" class="font-semibold text-gray-900">0 đ</div>
                                    </div>
                                    <div class="text-center p-3 bg-white rounded-lg">
                                        <div class="text-gray-500">Trả hàng tháng</div>
                                        <div id="monthly-payment" class="font-semibold text-indigo-600">0 đ</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Costs Info -->
                            <div id="additional-costs-info" class="mt-4 p-3 bg-amber-50 rounded-lg border border-amber-200 hidden">
                                <div class="text-xs text-amber-800 mb-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <span class="font-medium">Lưu ý về chi phí bổ sung:</span>
                                </div>
                                <div class="text-xs text-amber-700 space-y-1">
                                    <div id="tax-info" class="hidden">• Thuế: <span id="tax-amount">0 đ</span> (thanh toán riêng)</div>
                                    <div id="shipping-info" class="hidden">• Phí vận chuyển: <span id="shipping-amount">0 đ</span> (thanh toán riêng)</div>
                                    <div class="mt-1 font-medium">→ Trả góp chỉ áp dụng cho giá trị sản phẩm</div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment methods -->
                        <div class="pt-2">
                            <label id="payment-method-label" class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach(($paymentMethods ?? []) as $pm)
                                <label class="relative flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-400 cursor-pointer">
                                    <input type="radio" name="payment_method_id" value="{{ $pm->id }}" class="text-indigo-600 focus:ring-indigo-500" />
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $pm->name }}</div>
                                        @if($pm->provider)
                                        <div class="text-xs text-gray-500">{{ $pm->provider }}</div>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ghi chú</label>
                            <textarea name="note" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Yêu cầu giao hàng, xuất hóa đơn...">{{ old('note') }}</textarea>
                        </div>

                        <label class="inline-flex items-start gap-3 text-sm text-gray-700">
                            <input type="checkbox" name="terms_accepted" value="1" class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" required />
                            <span>Tôi đồng ý với các điều khoản sử dụng và chính sách bảo mật.</span>
                        </label>

                        <!-- Submit button moved to summary only -->
                    </form>
                </div>
            </div>

            <!-- Products moved into summary on the right -->
        </div>

        <!-- Summary -->
        <aside class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm lg:sticky lg:top-4">
                <div class="px-4 md:px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Thông tin đơn hàng</h2>
                </div>
                <div class="px-4 md:px-6 py-4 space-y-4">
                    @php $itemsCount = $cartItems->sum('quantity'); @endphp
                    <div class="flex items-center justify-between text-sm text-gray-700">
                        <span class="inline-flex items-center gap-2"><i class="fas fa-box"></i> Sản phẩm ({{ $itemsCount }})</span>
                    </div>
                    <div class="divide-y rounded-lg border border-gray-100 max-h-[320px] md:max-h-none overflow-y-auto md:overflow-visible pr-1 md:pr-0">
                        @foreach($cartItems->sortBy(function($item){ return $item->item_type === 'car_variant' ? 0 : 1; }) as $ci)
                            @php
                                $model = $ci->item;
                                $baseUnit = ($ci->item_type === 'car_variant' && method_exists($model, 'getPriceWithColorAdjustment'))
                                    ? $model->getPriceWithColorAdjustment($ci->color_id)
                                    : ($model->current_price ?? 0);
                                // add-ons from session meta
                                $meta = session('cart_item_meta.' . $ci->id, []);
                                $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                $selFeats = !empty($featIds) ? \App\Models\CarVariantFeature::whereIn('id', $featIds)->get() : collect();
                                $addonSum = 0;
                                foreach($selFeats as $sf){ $addonSum += (float)($sf->price ?? 0); }
                                $displayUnit = $baseUnit + $addonSum;
                                $line = $displayUnit * $ci->quantity;
                                $baseLine = $baseUnit * $ci->quantity;
                                $addonLine = $addonSum * $ci->quantity;
                                $img = null;
                                if ($ci->item_type === 'car_variant' && $model?->images?->isNotEmpty()) {
                                    $f = $model->images->first();
                                    $img = $f->image_url ?: ($f->image_path ? asset('storage/'.$f->image_path) : null);
                                } elseif ($ci->item_type === 'accessory') {
                                    // Accessory image logic (same as cart accessory-item.blade.php)
                                    $galleryRaw = $model->gallery ?? null;
                                    $gallery = is_array($galleryRaw) ? $galleryRaw : (json_decode($galleryRaw ?? '[]', true) ?: []);
                                    $firstGalleryImg = $gallery[0] ?? null;
                                    if ($firstGalleryImg) {
                                        $img = $firstGalleryImg;
                                    } elseif (!empty($model->image_url)) {
                                        $img = filter_var($model->image_url, FILTER_VALIDATE_URL) ? $model->image_url : asset('storage/'.$model->image_url);
                                    } else {
                                        $img = asset('images/default-accessory.jpg');
                                    }
                                }
                            @endphp
                            <div class="px-3 py-3 flex items-center gap-3 flex-wrap">
                                <div class="w-14 h-12 rounded-md bg-gray-100 overflow-hidden flex-shrink-0">
                                    @if($img)
                                        <img src="{{ $img }}" class="w-full h-full object-cover" alt="{{ $model?->name }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-[11px]">No image</div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900" title="{{ $model?->name }}" style="display:-webkit-box;-webkit-line-clamp:2;line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $model?->name }}</div>
                                    @if($ci->item_type === 'car_variant')
                                    @php 
                                        $meta = session('cart_item_meta.' . $ci->id, []);
                                        $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                        $selFeats = !empty($featIds) ? \App\Models\CarVariantFeature::whereIn('id', $featIds)->get() : collect();
                                            $colorName = $ci->color?->color_name;
                                            $colorHex = $colorName ? \App\Helpers\ColorHelper::getColorHex($colorName) : null;
                                    @endphp
                                        <div class="mt-0.5 flex items-center gap-2 text-[11px] text-gray-500 whitespace-normal break-words">
                                            <span>SL: {{ $ci->quantity }}</span>
                                            <span>•</span>
                                            <span class="inline-flex items-center gap-1">
                                                <span>Màu:</span>
                                                @if($colorName)
                                                    <span class="inline-flex items-center gap-1">
                                                        <span class="inline-block w-3 h-3 rounded-full border" style="background-color: {{ $colorHex }}; border-color: #e5e7eb"></span>
                                                        <span class="text-gray-700">{{ $colorName }}</span>
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">Chưa chọn</span>
                                                @endif
                                            </span>
                                        </div>
                                    @if($selFeats->count() > 0)
                                        <div class="mt-1 space-y-1">
                                                <div class="text-[11px] text-gray-600">Tùy chọn:
                                                    @foreach($selFeats as $sf)
                                                        <span class="inline-flex items-center gap-1 mr-2">{{ $sf->feature_name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="mt-0.5 flex items-center gap-2 text-[11px] text-gray-500">
                                            <span>SL: {{ $ci->quantity }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right sm:shrink-0 sm:min-w-[140px]">
                                    <div class="text-xs text-gray-500 whitespace-nowrap leading-none">Đơn giá</div>
                                    <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none">{{ number_format($displayUnit,0,',','.') }} đ</div>
                                    <div class="text-xs text-gray-500 whitespace-nowrap leading-none mt-2">Tổng</div>
                                    <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none">{{ number_format($line,0,',','.') }} đ</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @php 
                        // Calculate subtotal without discount
                        $subtotal = 0.0;
                        foreach ($cartItems as $ci) {
                            $unit = 0.0;
                            if ($ci->item_type === 'car_variant') {
                                $base = method_exists($ci->item,'getPriceWithColorAdjustment') ? (float) $ci->item->getPriceWithColorAdjustment($ci->color_id) : (float) ($ci->item->current_price ?? 0);
                                $meta = session('cart_item_meta.' . $ci->id, []);
                            $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                $featSum = !empty($featIds) ? (float) \App\Models\CarVariantFeature::whereIn('id', $featIds)->sum('price') : 0.0;
                                $unit = max(0.0, $base + $featSum);
                            } else {
                                $unit = (float) ($ci->item->current_price ?? 0);
                            }
                            $subtotal += $unit * (int) $ci->quantity;
                        }
                        $taxRate = 0.10; // 10% VAT
                        $taxAmount = (int) round($subtotal * $taxRate);
                    @endphp
                    @php
                        $defaultShippingMethod = 'standard';
                        $defaultShippingFee = 30000; // đơn giản: tiêu chuẩn 30k, nhanh 50k
                        $grandWithTax = $subtotal + $taxAmount;
                        $grandWithShip = $grandWithTax + $defaultShippingFee;
                    @endphp
                    <div class="space-y-2 rounded-lg border border-gray-100 p-3 bg-gray-50/70">
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span>Tạm tính</span>
                            <span id="subtotal-amount" data-subtotal="{{ (int) $subtotal }}" class="whitespace-nowrap tabular-nums">{{ number_format($subtotal,0,',','.') }} đ</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span>Thuế VAT (10%)</span>
                            <span id="tax-amount" data-tax="{{ (int) $taxAmount }}" class="whitespace-nowrap tabular-nums">{{ number_format($taxAmount,0,',','.') }} đ</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <label for="shipping-method" class="inline-flex items-center gap-2">
                                <i class="fas fa-truck"></i>
                                <span>Vận chuyển</span>
                            </label>
                            <select id="shipping-method" name="shipping_method" form="checkout-form" class="text-sm border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="standard" @selected($defaultShippingMethod==='standard') data-fee="30000">Tiêu chuẩn (30.000đ)</option>
                                <option value="express" @selected($defaultShippingMethod==='express') data-fee="50000">Nhanh (50.000đ)</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span>Phí vận chuyển</span>
                            <span id="shipping-fee-amount" data-shipping="{{ (int) $defaultShippingFee }}" class="whitespace-nowrap tabular-nums">{{ number_format($defaultShippingFee,0,',','.') }} đ</span>
                        </div>
                        
                        <!-- Promotion Code Section -->
                        <div class="border-t pt-3 mt-3">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-tags text-orange-500"></i>
                                <span class="text-sm font-medium text-gray-700">Mã khuyến mãi</span>
                            </div>
                            <div class="flex gap-2">
                                <input type="text" id="promotion-code" placeholder="Nhập mã khuyến mãi" 
                                       class="flex-1 text-sm border-gray-300 rounded-md focus:border-orange-500 focus:ring-orange-500">
                                <button type="button" onclick="applyPromotionCode()" 
                                        class="px-3 py-2 bg-orange-600 text-white text-sm rounded-md hover:bg-orange-700 font-medium">
                                    Áp dụng
                                </button>
                            </div>
                            <div id="promotion-result" class="mt-2 hidden">
                                <div id="promotion-success" class="hidden text-sm text-green-700 bg-green-50 p-2 rounded">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    <span id="promotion-message"></span>
                                </div>
                                <div id="promotion-error" class="hidden text-sm text-red-700 bg-red-50 p-2 rounded">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span id="promotion-error-message"></span>
                                </div>
                            </div>
                            <div id="applied-promotion" class="hidden mt-2 p-2 bg-green-50 rounded-md border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-tag text-green-600"></i>
                                        <span class="text-sm font-medium text-green-800" id="applied-code"></span>
                                    </div>
                                    <button type="button" onclick="removePromotionCode()" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="text-xs text-green-700 mt-1" id="applied-description"></div>
                            </div>
                            <div id="discount-line" class="hidden flex items-center justify-between text-sm text-green-700 mt-2">
                                <span>Giảm giá</span>
                                <span id="discount-amount" class="whitespace-nowrap tabular-nums">-0 đ</span>
                            </div>
                        </div>
                        
                        <div class="border-t pt-2 flex items-center justify-between text-base font-bold text-gray-900">
                            <span>Tổng cộng</span>
                            <span id="grand-total-amount" class="whitespace-nowrap tabular-nums">{{ number_format($grandWithShip,0,',','.') }} đ</span>
                        </div>
                    </div>
                    <button form="checkout-form" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-semibold shadow">
                        <i class="fas fa-check"></i>
                        Đặt hàng
                    </button>
                </div>
            </div>
        </aside>
    </div>
</div>

<!-- Toast Notifications -->
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.showMessage === 'function') {
        window.showMessage('{{ session('success') }}', 'success');
    }
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.showMessage === 'function') {
        window.showMessage('{{ session('error') }}', 'error');
    }
});
</script>
@endif

@if(session('warning'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.showMessage === 'function') {
        window.showMessage('{{ session('warning') }}', 'warning');
    }
});
</script>
@endif

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.showMessage === 'function') {
        @foreach($errors->all() as $error)
            window.showMessage('{{ $error }}', 'error');
        @endforeach
    }
});
</script>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom client-side validation using global toast
    const form = document.getElementById('checkout-form');
    if (form) {
        form.addEventListener('submit', function(e){
            // 0) Validate phone locally to avoid native tooltip
            const phoneEl = form.querySelector('input[name="phone"]');
            if (phoneEl) { try { phoneEl.setCustomValidity(''); } catch(_) {} }
            const phoneVal = (phoneEl?.value || '').trim();
            const phoneRegex = /^[0-9+\-\s()]+$/;
            if (!phoneVal || phoneVal.length < 10 || phoneVal.length > 15 || !phoneRegex.test(phoneVal)) {
                e.preventDefault();
                if (typeof window.showMessage === 'function') {
                    const msg = !phoneVal ? 'Vui lòng nhập số điện thoại.'
                        : (!phoneRegex.test(phoneVal) ? 'Số điện thoại không hợp lệ.'
                        : (phoneVal.length < 10 ? 'Số điện thoại phải có ít nhất 10 ký tự.' : 'Số điện thoại không được vượt quá 15 ký tự.'));
                    window.showMessage(msg, 'error');
                } else { alert('Số điện thoại không hợp lệ.'); }
                return false;
            }
            // 1) Validate billing address (must select from saved addresses)
            const billingSelect = document.querySelector('select[name="billing_address_id"]');
            const hasBillingAddress = billingSelect && billingSelect.value && billingSelect.value.trim() !== '';

            if (!hasBillingAddress) {
                e.preventDefault();
                if (typeof window.showMessage === 'function') {
                    window.showMessage('Vui lòng chọn địa chỉ thanh toán', 'error');
                } else {
                    alert('Vui lòng chọn địa chỉ thanh toán');
                }
                billingSelect?.focus();
                return false;
            }

            // 2) Validate payment type
            const paymentType = document.querySelector('input[name="payment_type"]:checked');
            if (!paymentType) {
                e.preventDefault();
                if (typeof window.showMessage === 'function') {
                    window.showMessage('Vui lòng chọn hình thức thanh toán', 'error');
                } else {
                    alert('Vui lòng chọn hình thức thanh toán');
                }
                document.querySelector('input[name="payment_type"]')?.focus();
                return false;
            }

            // 3) Validate finance options if payment type is finance
            if (paymentType.value === 'finance') {
                const financeOption = document.querySelector('input[name="finance_option_id"]:checked');
                if (!financeOption) {
                    e.preventDefault();
                    if (typeof window.showMessage === 'function') {
                        window.showMessage('Vui lòng chọn gói tài chính', 'error');
                    } else {
                        alert('Vui lòng chọn gói tài chính');
                    }
                    document.querySelector('input[name="finance_option_id"]')?.focus();
                    return false;
                }

                // Validate down payment percentage
                const downPaymentInput = document.querySelector('input[name="down_payment_percent"]');
                const downPaymentValue = parseFloat(downPaymentInput?.value || '0');
                if (!downPaymentValue || downPaymentValue < 20 || downPaymentValue > 80) {
                    e.preventDefault();
                    if (typeof window.showMessage === 'function') {
                        window.showMessage('Trả trước phải từ 20% đến 80%', 'error');
                    } else {
                        alert('Trả trước phải từ 20% đến 80%');
                    }
                    downPaymentInput?.focus();
                    return false;
                }

                // Validate tenure
                const tenureSelect = document.querySelector('select[name="tenure_months"]');
                if (!tenureSelect?.value) {
                    e.preventDefault();
                    if (typeof window.showMessage === 'function') {
                        window.showMessage('Vui lòng chọn thời hạn vay', 'error');
                    } else {
                        alert('Vui lòng chọn thời hạn vay');
                    }
                    tenureSelect?.focus();
                    return false;
                }
            }

            // 4) Validate payment method
            const picked = document.querySelector('input[name="payment_method_id"]:checked');
            if (!picked) {
                e.preventDefault();
                if (typeof window.showMessage === 'function') {
                    window.showMessage('Vui lòng chọn phương thức thanh toán', 'error');
                } else {
                    alert('Vui lòng chọn phương thức thanh toán');
                }
                document.querySelector('input[name="payment_method_id"]')?.focus();
                return false;
            }

            // 5) Validate terms acceptance
            const terms = document.querySelector('input[name="terms_accepted"]');
            if (!terms || !terms.checked) {
                e.preventDefault();
                if (typeof window.showMessage === 'function') {
                    window.showMessage('Bạn phải đồng ý với điều khoản sử dụng', 'error');
                } else {
                    alert('Bạn phải đồng ý với điều khoản sử dụng');
                }
                terms?.focus();
                return false;
            }
        });
    }
    const select = document.getElementById('shipping-method');
    const subtotalEl = document.getElementById('subtotal-amount');
    const discountEl = document.getElementById('discount-amount');
    const taxEl = document.getElementById('tax-amount');
    const shipEl = document.getElementById('shipping-fee-amount');
    const grandEl = document.getElementById('grand-total-amount');

    function format(n){ return new Intl.NumberFormat('vi-VN').format(n) + ' đ'; }
    function recalc(){
        const subtotal = parseInt(subtotalEl.dataset.subtotal || '0');
        const tax = parseInt(taxEl.dataset.tax || '0');
        const option = select.options[select.selectedIndex];
        const fee = parseInt(option.getAttribute('data-fee') || '0');
        const discount = parseInt(document.getElementById('discount-amount')?.textContent?.replace(/[^\d]/g, '') || '0');
        
        shipEl.dataset.shipping = String(fee);
        shipEl.textContent = format(fee);
        const grand = Math.max(0, subtotal + tax + fee - discount);
        grandEl.textContent = format(grand);
        
        // Recalculate finance if finance is selected
        if (document.querySelector('input[name="payment_type"]:checked')?.value === 'finance') {
            calculateFinance();
        }
    }
    select?.addEventListener('change', function() {
        recalc();
        // Update discount amount if free shipping promotion is applied
        updateDiscountDisplayOnShippingChange();
    });
    recalc();

    // Finance options handling
    const paymentTypeRadios = document.querySelectorAll('input[name="payment_type"]');
    const financeSection = document.getElementById('finance-options-section');
    const financeDetails = document.getElementById('finance-details');
    const financeRadios = document.querySelectorAll('input[name="finance_option_id"]');
    const downPaymentInput = document.querySelector('input[name="down_payment_percent"]');
    const tenureSelect = document.querySelector('select[name="tenure_months"]');

    // Show/hide finance options based on payment type
    paymentTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const paymentMethodLabel = document.getElementById('payment-method-label');
            if (this.value === 'finance') {
                financeSection.classList.remove('hidden');
                if (paymentMethodLabel) {
                    paymentMethodLabel.textContent = 'Phương thức thanh toán (cho khoản trả trước)';
                }
            } else {
                financeSection.classList.add('hidden');
                financeDetails.classList.add('hidden');
                if (paymentMethodLabel) {
                    paymentMethodLabel.textContent = 'Phương thức thanh toán';
                }
            }
        });
    });

    // Show finance details when a finance option is selected
    financeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                financeDetails.classList.remove('hidden');
                updateFinanceInputs();
                calculateFinance();
            }
        });
    });

    // Recalculate when finance inputs change
    downPaymentInput?.addEventListener('input', calculateFinance);
    tenureSelect?.addEventListener('change', calculateFinance);

    function updateFinanceInputs() {
        const selectedFinanceOption = document.querySelector('input[name="finance_option_id"]:checked');
        if (!selectedFinanceOption) return;

        // Get finance option details from data attributes
        const minTenure = parseInt(selectedFinanceOption.dataset.minTenure);
        const maxTenure = parseInt(selectedFinanceOption.dataset.maxTenure);
        const minDownPayment = parseFloat(selectedFinanceOption.dataset.minDownPayment);
        
        // Update tenure select options
        if (tenureSelect) {
            tenureSelect.innerHTML = '';
            
            // Generate comprehensive options based on min/max tenure range
            const options = new Set();
            
            // Add all multiples of 6 within range (standard increments)
            for (let months = 6; months <= 60; months += 6) {
                if (months >= minTenure && months <= maxTenure) {
                    options.add(months);
                }
            }
            
            // Add all multiples of 12 within range (yearly increments)
            for (let months = 12; months <= 60; months += 12) {
                if (months >= minTenure && months <= maxTenure) {
                    options.add(months);
                }
            }
            
            // Add specific common options within range
            const commonOptions = [3, 6, 9, 12, 15, 18, 21, 24, 30, 36, 42, 48, 54, 60, 66, 72, 84, 96];
            commonOptions.forEach(months => {
                if (months >= minTenure && months <= maxTenure) {
                    options.add(months);
                }
            });
            
            // Ensure min and max tenure are always included
            options.add(minTenure);
            options.add(maxTenure);
            
            // Convert to sorted array
            const sortedOptions = Array.from(options).sort((a, b) => a - b);
            
            // Smart default selection
            let defaultSelected = false;
            sortedOptions.forEach(months => {
                const option = document.createElement('option');
                option.value = months;
                option.textContent = months + ' tháng';
                
                // Selection priority: 36 > 24 > 12 > middle option > first option
                if (!defaultSelected) {
                    if (months === 36) {
                        option.selected = true;
                        defaultSelected = true;
                    } else if (months === 24 && !sortedOptions.includes(36)) {
                        option.selected = true;
                        defaultSelected = true;
                    } else if (months === 12 && !sortedOptions.includes(36) && !sortedOptions.includes(24)) {
                        option.selected = true;
                        defaultSelected = true;
                    } else if (months === sortedOptions[Math.floor(sortedOptions.length / 2)] && !sortedOptions.includes(36) && !sortedOptions.includes(24) && !sortedOptions.includes(12)) {
                        option.selected = true;
                        defaultSelected = true;
                    } else if (months === sortedOptions[0] && sortedOptions.length === 1) {
                        option.selected = true;
                        defaultSelected = true;
                    }
                }
                
                tenureSelect.appendChild(option);
            });
            
            // Fallback: select first option if nothing selected
            if (!defaultSelected && sortedOptions.length > 0) {
                tenureSelect.options[0].selected = true;
            }
        }
        
        // Update down payment input
        if (downPaymentInput) {
            downPaymentInput.min = minDownPayment;
            downPaymentInput.value = minDownPayment;
        }
    }

    function calculateFinance() {
        const selectedFinanceOption = document.querySelector('input[name="finance_option_id"]:checked');
        if (!selectedFinanceOption) return;

        const subtotal = parseInt(subtotalEl.dataset.subtotal || '0');
        const tax = parseInt(taxEl.dataset.tax || '0');
        const shipping = parseInt(shipEl.dataset.shipping || '0');
        const discount = parseInt(document.getElementById('discount-amount')?.textContent?.replace(/[^\d]/g, '') || '0');
        
        // Finance calculation should be based on product value only (excluding tax, shipping, and discount)
        const financeableAmount = subtotal - discount; // Product value after discount
        const totalAmount = subtotal + tax + shipping; // For display purposes

        const downPaymentPercent = parseFloat(downPaymentInput?.value || '30');
        const tenureMonths = parseInt(tenureSelect?.value || '36');
        
        // Get interest rate from data attribute
        const interestRate = parseFloat(selectedFinanceOption.dataset.interestRate) / 100;

        const downPaymentAmount = Math.round(financeableAmount * (downPaymentPercent / 100));
        const loanAmount = financeableAmount - downPaymentAmount;
        
        // Calculate monthly payment using compound interest formula
        const monthlyRate = interestRate / 12;
        const monthlyPayment = monthlyRate > 0 
            ? Math.round(loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, tenureMonths)) / (Math.pow(1 + monthlyRate, tenureMonths) - 1))
            : Math.round(loanAmount / tenureMonths);

        // Update display
        document.getElementById('down-payment-amount').textContent = format(downPaymentAmount);
        document.getElementById('loan-amount').textContent = format(loanAmount);
        document.getElementById('monthly-payment').textContent = format(monthlyPayment);
        
        // Show additional costs info if tax or shipping exists
        const additionalCostsEl = document.getElementById('additional-costs-info');
        if (additionalCostsEl) {
            if (tax > 0 || shipping > 0) {
                additionalCostsEl.classList.remove('hidden');
                
                const taxInfoEl = document.getElementById('tax-info');
                const taxAmountEl = document.getElementById('tax-amount');
                if (taxInfoEl && taxAmountEl) {
                    if (tax > 0) {
                        taxAmountEl.textContent = format(tax);
                        taxInfoEl.classList.remove('hidden');
                    } else {
                        taxInfoEl.classList.add('hidden');
                    }
                }
                
                const shippingInfoEl = document.getElementById('shipping-info');
                const shippingAmountEl = document.getElementById('shipping-amount');
                if (shippingInfoEl && shippingAmountEl) {
                    if (shipping > 0) {
                        shippingAmountEl.textContent = format(shipping);
                        shippingInfoEl.classList.remove('hidden');
                    } else {
                        shippingInfoEl.classList.add('hidden');
                    }
                }
            } else {
                additionalCostsEl.classList.add('hidden');
            }
        }
    }

    // Promotion Code Functions
    window.applyPromotionCode = function() {
        const codeInput = document.getElementById('promotion-code');
        const code = codeInput.value.trim().toUpperCase();
        
        if (!code) {
            showPromotionError('Vui lòng nhập mã khuyến mãi');
            return;
        }
        
        // Check CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken || !csrfToken.content) {
            showPromotionError('Lỗi bảo mật: Vui lòng tải lại trang và thử lại');
            return;
        }
        
        // Get current order total for validation
        const subtotalEl = document.getElementById('subtotal-amount');
        const orderTotal = subtotalEl ? parseInt(subtotalEl.dataset.subtotal) : 0;
        
        // Show loading
        const applyBtn = document.querySelector('button[onclick="applyPromotionCode()"]');
        const originalText = applyBtn.innerHTML;
        applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        applyBtn.disabled = true;
        
        // Call validation API
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('code', code);
        formData.append('order_total', orderTotal);
        
        // Add current shipping method
        const shippingSelect = document.getElementById('shipping-method');
        if (shippingSelect) {
            formData.append('shipping_method', shippingSelect.value);
        }
        
        fetch('{{ route("user.promotions.validate-code") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                // Check if discount amount is 0 (promotion not applicable to current shipping method)
                if (data.promotion.discount_amount === 0 && data.promotion.type === 'free_shipping') {
                    const shippingSelect = document.getElementById('shipping-method');
                    const shippingMethod = shippingSelect ? shippingSelect.value : 'standard';
                    const methodName = shippingMethod === 'express' ? 'vận chuyển nhanh' : 'vận chuyển tiêu chuẩn';
                    showPromotionError(`Mã khuyến mãi này không áp dụng cho ${methodName}. Vui lòng chọn phương thức vận chuyển phù hợp.`);
                } else {
                    showPromotionSuccess(data.message, data.promotion);
                    updateOrderTotals(data.promotion.discount_amount, data.promotion.type);
                    
                    // Store promotion code in hidden input for form submission
                    const promotionCodeInput = document.getElementById('applied_promotion_code');
                    if (promotionCodeInput) {
                        promotionCodeInput.value = data.promotion.code;
                    }
                }
            } else {
                showPromotionError(data.message);
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            showPromotionError('Có lỗi xảy ra khi kiểm tra mã khuyến mãi. Vui lòng thử lại.');
        })
        .finally(() => {
            applyBtn.innerHTML = originalText;
            applyBtn.disabled = false;
        });
    };
    
    window.removePromotionCode = function() {
        // Clear current promotion
        currentPromotion = null;
        
        const promotionCode = document.getElementById('promotion-code');
        const appliedPromotion = document.getElementById('applied-promotion');
        const discountLine = document.getElementById('discount-line');
        const promotionResult = document.getElementById('promotion-result');
        
        if (promotionCode) promotionCode.value = '';
        if (appliedPromotion) appliedPromotion.classList.add('hidden');
        if (discountLine) discountLine.classList.add('hidden');
        if (promotionResult) promotionResult.classList.add('hidden');
        
        // Reset totals
        updateOrderTotals(0);
        
        // Clear promotion code from hidden input
        const promotionCodeInput = document.getElementById('applied_promotion_code');
        if (promotionCodeInput) {
            promotionCodeInput.value = '';
        }
    };
    
    function showPromotionSuccess(message, promotion) {
        // Store current promotion globally
        currentPromotion = promotion;
        
        // Safely get elements and check if they exist
        const promotionResult = document.getElementById('promotion-result');
        const promotionSuccess = document.getElementById('promotion-success');
        const promotionError = document.getElementById('promotion-error');
        const promotionSuccessMessage = document.getElementById('promotion-message');
        const appliedPromotion = document.getElementById('applied-promotion');
        const appliedCode = document.getElementById('applied-code');
        const appliedDescription = document.getElementById('applied-description');
        const discountLine = document.getElementById('discount-line');
        const discountAmount = document.getElementById('discount-amount');
        
        if (promotionResult) promotionResult.classList.remove('hidden');
        if (promotionSuccess) promotionSuccess.classList.remove('hidden');
        if (promotionError) promotionError.classList.add('hidden');
        if (promotionSuccessMessage) promotionSuccessMessage.textContent = message;
        
        // Show applied promotion
        if (appliedPromotion) appliedPromotion.classList.remove('hidden');
        if (appliedCode) appliedCode.textContent = promotion.code;
        
        // Build description with max discount info
        let description = promotion.description || promotion.name;
        if (promotion.max_discount_amount && (promotion.type === 'percentage' || promotion.type === 'brand_specific')) {
            description += ` (Tối đa ${formatNumber(promotion.max_discount_amount)}đ)`;
        }
        if (appliedDescription) appliedDescription.textContent = description;
        
        // Show discount line with correct amount
        if (discountLine) discountLine.classList.remove('hidden');
        
        // For free shipping, calculate discount based on selected shipping method
        let displayAmount = promotion.discount_amount;
        if (promotion.type === 'free_shipping') {
            const shippingSelect = document.getElementById('shipping-method');
            if (shippingSelect && shippingSelect.selectedIndex >= 0) {
                const selectedOption = shippingSelect.options[shippingSelect.selectedIndex];
                displayAmount = parseInt(selectedOption.dataset.fee) || 30000;
            }
        }
        
        if (discountAmount) discountAmount.textContent = '-' + formatNumber(displayAmount) + ' đ';
    }
    
    function showPromotionError(message) {
        const promotionResult = document.getElementById('promotion-result');
        const promotionError = document.getElementById('promotion-error');
        const promotionSuccess = document.getElementById('promotion-success');
        const promotionErrorMessage = document.getElementById('promotion-error-message');
        const appliedPromotion = document.getElementById('applied-promotion');
        const discountLine = document.getElementById('discount-line');
        
        if (promotionResult) promotionResult.classList.remove('hidden');
        if (promotionError) promotionError.classList.remove('hidden');
        if (promotionSuccess) promotionSuccess.classList.add('hidden');
        if (promotionErrorMessage) promotionErrorMessage.textContent = message;
        
        // Hide applied promotion
        if (appliedPromotion) appliedPromotion.classList.add('hidden');
        if (discountLine) discountLine.classList.add('hidden');
    }
    
    // Global variable to store current promotion
    let currentPromotion = null;
    
    function updateDiscountDisplayOnShippingChange() {
        if (currentPromotion && currentPromotion.type === 'free_shipping') {
            // Re-validate promotion with new shipping method
            const promotionCode = document.getElementById('promotion-code');
            if (promotionCode && promotionCode.value) {
                applyPromotionCode();
            }
        }
    }
    
    function updateOrderTotals(discountAmount, promotionType = null) {
        const subtotalEl = document.getElementById('subtotal-amount');
        const taxEl = document.getElementById('tax-amount');
        const shippingEl = document.getElementById('shipping-fee-amount');
        const grandTotalEl = document.getElementById('grand-total-amount');
        
        if (!subtotalEl || !taxEl || !shippingEl || !grandTotalEl) return;
        
        const subtotal = parseInt(subtotalEl.dataset.subtotal);
        const tax = parseInt(taxEl.dataset.tax);
        let shipping = parseInt(shippingEl.dataset.shipping);
        
        // Handle free shipping promotions
        if (promotionType === 'free_shipping') {
            shipping = 0; // Free shipping
            shippingEl.textContent = '0 đ';
            shippingEl.classList.add('line-through', 'text-green-600');
        } else {
            // Reset shipping display
            shippingEl.textContent = formatNumber(shipping) + ' đ';
            shippingEl.classList.remove('line-through', 'text-green-600');
        }
        
        let newGrandTotal = subtotal + tax + shipping;
        
        // Apply discount (except for free shipping which is already handled)
        if (promotionType !== 'free_shipping') {
            newGrandTotal = Math.max(0, newGrandTotal - discountAmount);
        }
        
        grandTotalEl.textContent = formatNumber(newGrandTotal) + ' đ';
        
        // Recalculate finance details if finance is selected
        if (document.querySelector('input[name="payment_type"]:checked')?.value === 'finance') {
            calculateFinance();
        }
    }
    
    function formatNumber(num) {
        return new Intl.NumberFormat('vi-VN').format(num);
    }

});
</script>
@endpush
