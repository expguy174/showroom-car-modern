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

    @if(session('success'))
        <div class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded-lg bg-rose-50 text-rose-700 border border-rose-200">{{ session('error') }}</div>
    @endif
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
                    <form id="checkout-form" action="{{ route('user.cart.checkout') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Họ và tên</label>
                                <input type="text" name="name" value="{{ old('name', $user?->name) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone', $user?->phone) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user?->email) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                        </div>
                        @if(($addresses ?? collect())->count() > 0)
                        <div class="pt-2 space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Địa chỉ thanh toán</label>
                            <select name="billing_address_id" class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">— Chọn địa chỉ đã lưu —</option>
                                @foreach($addresses as $addr)
                                    <option value="{{ $addr->id }}" @selected(old('billing_address_id') == $addr->id)>
                                        {{ $addr->contact_name }} - {{ $addr->address }} @if($addr->is_default) (Mặc định) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @else
                        <div class="pt-2 p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                            <div class="flex items-center gap-2 text-yellow-800">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span class="text-sm font-medium">Bạn cần thêm địa chỉ vào sổ địa chỉ trước khi thanh toán</span>
                            </div>
                            <div class="mt-2">
                                <a href="{{ route('user.addresses.index') }}" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors">
                                    <i class="fas fa-plus"></i>
                                    Thêm địa chỉ mới
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Payment methods -->
                        <div class="pt-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phương thức thanh toán</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach(($paymentMethods ?? []) as $pm)
                                <label class="relative flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-indigo-400 cursor-pointer">
                                    <input type="radio" name="payment_method_id" value="{{ $pm->id }}" class="text-indigo-600 focus:ring-indigo-500" required />
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
                        <span class="text-gray-500 whitespace-nowrap tabular-nums">{{ number_format($total) }} đ</span>
                    </div>
                    <div class="divide-y rounded-lg border border-gray-100 max-h-[320px] md:max-h-none overflow-y-auto md:overflow-visible pr-1 md:pr-0">
                        @foreach($cartItems as $ci)
                            @php
                                $model = $ci->item;
                                $baseUnit = ($ci->item_type === 'car_variant' && method_exists($model, 'getPriceWithColorAdjustment'))
                                    ? $model->getPriceWithColorAdjustment($ci->color_id)
                                    : ($model->price ?? 0);
                                // add-ons from session meta
                                $meta = session('cart_item_meta.' . $ci->id, []);
                                $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                $optIds = collect($meta['option_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                $selFeats = !empty($featIds) ? \App\Models\CarVariantFeature::whereIn('id', $featIds)->get() : collect();
                                $selOpts  = !empty($optIds) ? \App\Models\CarVariantOption::whereIn('id', $optIds)->get() : collect();
                                $addonSum = 0;
                                foreach($selFeats as $sf){ $addonSum += (float)($sf->package_price ?? $sf->price ?? 0); }
                                foreach($selOpts as $so){ $addonSum += (float)($so->package_price ?? $so->price ?? 0); }
                                $displayUnit = $baseUnit + $addonSum;
                                $line = $displayUnit * $ci->quantity;
                                $baseLine = $baseUnit * $ci->quantity;
                                $addonLine = $addonSum * $ci->quantity;
                                $img = null;
                                if ($ci->item_type === 'car_variant' && $model?->images?->isNotEmpty()) {
                                    $f = $model->images->first();
                                    $img = $f->image_url ?: ($f->image_path ? asset('storage/'.$f->image_path) : null);
                                } elseif ($ci->item_type === 'accessory') {
                                    $img = $model?->image_url ? (filter_var($model->image_url, FILTER_VALIDATE_URL) ? $model->image_url : asset('storage/'.$model->image_url)) : null;
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
                                    <div class="text-[11px] text-gray-500 whitespace-normal break-words">SL: {{ $ci->quantity }}@if($ci->color) • Màu: {{ $ci->color->color_name }} @endif</div>
                                    @php 
                                        $meta = session('cart_item_meta.' . $ci->id, []);
                                        $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                        $optIds = collect($meta['option_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                                        $selFeats = !empty($featIds) ? \App\Models\CarVariantFeature::whereIn('id', $featIds)->get() : collect();
                                        $selOpts  = !empty($optIds) ? \App\Models\CarVariantOption::whereIn('id', $optIds)->get() : collect();
                                    @endphp
                                    @if($selFeats->count() > 0 || $selOpts->count() > 0)
                                        <div class="mt-1 space-y-1">
                                            @if($selFeats->count() > 0)
                                                <div class="text-[11px] text-gray-600">Tính năng: 
                                                    @foreach($selFeats as $sf)
                                                        @php $fee=(float)($sf->package_price ?? $sf->price ?? 0); @endphp
                                                        <span class="inline-flex items-center gap-1 mr-2">{{ $sf->feature_name }}@if($fee>0)<span class="text-indigo-700">(+{{ number_format($fee,0,',','.') }}đ)</span>@endif</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if($selOpts->count() > 0)
                                                <div class="text-[11px] text-gray-600">Tuỳ chọn: 
                                                    @foreach($selOpts as $so)
                                                        @php $fee=(float)($so->package_price ?? $so->price ?? 0); @endphp
                                                        <span class="inline-flex items-center gap-1 mr-2">{{ $so->option_name }}@if($fee>0)<span class="text-indigo-700">(+{{ number_format($fee,0,',','.') }}đ)</span>@endif</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right sm:shrink-0 sm:min-w-[160px]">
                                    <div class="text-xs text-gray-500 whitespace-nowrap leading-none">Đơn giá</div>
                                    <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none">{{ number_format($displayUnit) }} đ</div>
                                    <div class="text-[11px] text-gray-500">Gốc: {{ number_format($baseUnit) }} đ</div>
                                    @if($addonSum > 0)
                                    <div class="text-[11px] text-indigo-700">+ Add-on: {{ number_format($addonSum) }} đ</div>
                                    @endif
                                    <div class="text-xs text-gray-500 whitespace-nowrap leading-none mt-2">Tổng</div>
                                    <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none">{{ number_format($line) }} đ</div>
                                    <div class="text-[11px] text-gray-500">Gốc: {{ number_format($baseLine) }} đ</div>
                                    @if($addonLine > 0)
                                    <div class="text-[11px] text-indigo-700">+ Add-on: {{ number_format($addonLine) }} đ</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @php 
                        $shippingSelected = request()->input('shipping_method', 'standard');
                        $shippingFee = $shippingSelected === 'express' ? 50000 : 30000;
                        // Compute aggregated add-on surcharge across cart
                        $addonCart = 0;
                        foreach ($cartItems as $aci) {
                            $meta = session('cart_item_meta.' . $aci->id, []);
                            $featIds = collect($meta['feature_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                            $optIds = collect($meta['option_ids'] ?? [])->filter()->map(fn($v)=> (int)$v)->unique()->all();
                            $selFeats = !empty($featIds) ? \App\Models\CarVariantFeature::whereIn('id', $featIds)->get() : collect();
                            $selOpts  = !empty($optIds) ? \App\Models\CarVariantOption::whereIn('id', $optIds)->get() : collect();
                            $addonSumUnit = 0; foreach($selFeats as $sf){ $addonSumUnit += (float)($sf->package_price ?? $sf->price ?? 0); } foreach($selOpts as $so){ $addonSumUnit += (float)($so->package_price ?? $so->price ?? 0); }
                            $addonCart += $addonSumUnit * (int) $aci->quantity;
                        }
                        $tax = (int) round($total * 0.1);
                        $grand = $total + $tax + $shippingFee;
                    @endphp
                                            <div class="space-y-2">
                            <div class="text-sm font-semibold text-gray-900">Phương thức vận chuyển</div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="flex-1 inline-flex items-center gap-2 p-2 border rounded-lg cursor-pointer {{ $shippingSelected==='standard' ? 'border-indigo-300 bg-indigo-50/40' : 'border-gray-200' }}">
                                    <input type="radio" name="shipping_method" value="standard" class="text-indigo-600" {{ $shippingSelected==='standard' ? 'checked' : '' }} required>
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-800">Tiêu chuẩn</div>
                                        <div class="text-xs text-gray-500">2-4 ngày • 30.000 đ</div>
                                    </div>
                                </label>
                                <label class="flex-1 inline-flex items-center gap-2 p-2 border rounded-lg cursor-pointer {{ $shippingSelected==='express' ? 'border-indigo-300 bg-indigo-50/40' : 'border-gray-200' }}">
                                    <input type="radio" name="shipping_method" value="express" class="text-indigo-600" {{ $shippingSelected==='express' ? 'checked' : '' }} required>
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-800">Nhanh</div>
                                        <div class="text-xs text-gray-500">24-48h • 50.000 đ</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                    <div class="space-y-2 rounded-lg border border-gray-100 p-3 bg-gray-50/70">
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span>Tạm tính</span>
                            <span id="subtotal-amount" class="whitespace-nowrap tabular-nums" data-subtotal="{{ (int) $total }}">{{ number_format($total) }} đ</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span>Phụ phí add-on</span>
                            <span class="whitespace-nowrap tabular-nums text-indigo-700">+{{ number_format((int) $addonCart) }} đ</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span>Thuế (10%)</span>
                            <span id="tax-amount" class="whitespace-nowrap tabular-nums" data-tax="{{ (int) $tax }}">{{ number_format($tax) }} đ</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-gray-700">
                            <span>Phí vận chuyển</span>
                            <span id="shipping-fee-amount" class="whitespace-nowrap tabular-nums" data-standard="30000" data-express="50000">{{ number_format($shippingFee) }} đ</span>
                        </div>
                        <div class="border-t pt-2 flex items-center justify-between text-base font-bold text-gray-900">
                            <span>Tổng cộng</span>
                            <span id="grand-total-amount" class="whitespace-nowrap tabular-nums" data-grand="{{ (int) $grand }}">{{ number_format($grand) }} đ</span>
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
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle shipping method selection
    const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
    const shippingFeeAmount = document.getElementById('shipping-fee-amount');
    const grandTotalAmount = document.getElementById('grand-total-amount');
    const subtotalAmount = document.getElementById('subtotal-amount');
    const taxAmount = document.getElementById('tax-amount');
    
    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const method = this.value;
            const shippingFee = method === 'express' ? 50000 : 30000;
            const subtotal = parseInt(subtotalAmount.dataset.subtotal);
            const tax = parseInt(taxAmount.dataset.tax);
            const grandTotal = subtotal + tax + shippingFee;
            
            shippingFeeAmount.textContent = new Intl.NumberFormat('vi-VN').format(shippingFee) + ' đ';
            grandTotalAmount.textContent = new Intl.NumberFormat('vi-VN').format(grandTotal) + ' đ';
        });
    });
});
</script>


