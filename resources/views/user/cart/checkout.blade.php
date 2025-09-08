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
            <div class="max-w-4xl mx-auto">
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

    {{-- Removed inline alert boxes; rely on global toast system --}}
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-rose-50 text-rose-700 border border-rose-200">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                        <input type="hidden" name="checkout_token" value="{{ $checkoutToken ?? '' }}">
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
                                <input type="text" name="phone" value="{{ old('phone', optional(($addresses ?? collect())->firstWhere('is_default', true) ?: ($addresses ?? collect())->first())->phone) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                        </div>
                        <div class="pt-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Địa chỉ thanh toán</label>
                            @if(($addresses ?? collect())->count() > 0)
                            <select name="billing_address_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">— Chọn địa chỉ đã lưu —</option>
                                @foreach($addresses as $addr)
                                    <option value="{{ $addr->id }}" @selected(old('billing_address_id') == $addr->id)>
                                        {{ $addr->contact_name }} - {{ $addr->address }} @if($addr->is_default) (Mặc định) @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="text-center text-gray-500 text-sm">hoặc</div>
                            @endif
                            <textarea name="billing_address" rows="3" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nhập địa chỉ thanh toán mới...">{{ old('billing_address') }}</textarea>
                        </div>

                        <!-- Payment methods -->
                        <div class="pt-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom client-side validation using global toast
    const form = document.getElementById('checkout-form');
    if (form) {
        form.addEventListener('submit', function(e){
            // 1) Validate billing address (either selected saved address or entered new address)
            const billingSelect = document.querySelector('select[name="billing_address_id"]');
            const billingTextarea = document.querySelector('textarea[name="billing_address"]');
            const hasSavedAddress = billingSelect && billingSelect.value && billingSelect.value.trim() !== '';
            const hasTypedAddress = billingTextarea && billingTextarea.value && billingTextarea.value.trim() !== '';
            const hasBillingAddress = !!(hasSavedAddress || hasTypedAddress);

            if (!hasBillingAddress) {
                e.preventDefault();
                if (typeof window.showMessage === 'function') {
                    window.showMessage('Vui lòng chọn hoặc nhập địa chỉ thanh toán', 'error');
                } else {
                    alert('Vui lòng chọn hoặc nhập địa chỉ thanh toán');
                }
                return false;
            }

            // 2) Validate payment method
            const picked = document.querySelector('input[name="payment_method_id"]:checked');
            if (!picked) {
                e.preventDefault();
                if (typeof window.showMessage === 'function') {
                    window.showMessage('Vui lòng chọn phương thức thanh toán', 'error');
                } else {
                    alert('Vui lòng chọn phương thức thanh toán');
                }
                return false;
            }

            // 3) Validate terms acceptance
            const terms = document.querySelector('input[name="terms_accepted"]');
            if (!terms || !terms.checked) {
                e.preventDefault();
                if (typeof window.showMessage === 'function') {
                    window.showMessage('Bạn phải đồng ý với điều khoản sử dụng', 'error');
                } else {
                    alert('Bạn phải đồng ý với điều khoản sử dụng');
                }
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
        shipEl.dataset.shipping = String(fee);
        shipEl.textContent = format(fee);
        const grand = Math.max(0, subtotal + tax + fee);
        grandEl.textContent = format(grand);
    }
    select?.addEventListener('change', recalc);
    recalc();
});
</script>


