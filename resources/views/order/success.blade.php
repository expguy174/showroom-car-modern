@extends('layouts.app')

@section('title', 'Hoàn tất đơn hàng')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Hoàn tất đơn hàng</h1>
                        <p class="text-gray-600">Đơn hàng đã được tạo thành công</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('cart.index') }}" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
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
                    <a href="{{ route('cart.index') }}" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                        <span class="font-medium text-gray-500">Giỏ hàng</span>
                    </a>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                        <span class="font-medium text-gray-500">Thanh toán</span>
                    </div>
                    <div class="flex-1 h-px bg-gray-200"></div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                        <span class="font-semibold text-emerald-600">Hoàn tất</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fas fa-check"></i></div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-extrabold text-gray-900">Cảm ơn bạn! Đơn hàng đã được tạo</h1>
                        <div class="text-sm text-gray-600">Mã đơn: <span class="font-semibold text-indigo-700">{{ $order->order_number ?? ('#'.$order->id) }}</span></div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="md:col-span-3 space-y-4">
                    <div class="p-4 rounded-xl border bg-white">
                        <div class="text-sm font-semibold text-gray-700 mb-2">Trạng thái</div>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-{{ $order->payment_status === 'paid' ? 'emerald' : 'amber' }}-50 text-{{ $order->payment_status === 'paid' ? 'emerald' : 'amber' }}-700">Thanh toán: {{ $order->payment_status_display }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-100 text-gray-700">Đơn: {{ $order->status_display }}</span>
                        </div>
                    </div>

                    <div class="rounded-xl border overflow-hidden">
                        <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-800 flex items-center justify-between">
                            <span>Thông tin đơn hàng</span>
                            @php $itemsCount = $order->items->sum('quantity'); @endphp
                            <span class="text-sm text-gray-500">Sản phẩm ({{ $itemsCount }})</span>
                        </div>
                        <div class="divide-y">
                            @foreach($order->items as $it)
                                @php
                                    $model = $it->item;
                                    $unit = $it->price;
                                    $line = $it->line_total ?: ($unit * $it->quantity);
                                    $img = null;
                                    if ($it->item_type === 'car_variant' && $model?->images?->isNotEmpty()) {
                                        $f = $model->images->first();
                                        $img = $f->image_url ?: ($f->image_path ? asset('storage/'.$f->image_path) : null);
                                    } elseif ($it->item_type === 'accessory') {
                                        $img = $model?->image_url ? (filter_var($model->image_url, FILTER_VALIDATE_URL) ? $model->image_url : asset('storage/'.$model->image_url)) : null;
                                    }
                                @endphp
                                <div class="px-4 py-3 flex items-center gap-3 flex-wrap">
                                    <div class="w-16 h-12 rounded-md bg-gray-100 overflow-hidden flex-shrink-0">
                                        @if($img)
                                            <img src="{{ $img }}" class="w-full h-full object-cover" alt="{{ $model?->name ?? $it->item_name }}" />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 text-[11px]">No image</div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-gray-900" title="{{ $model?->name ?? $it->item_name }}" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $model?->name ?? $it->item_name }}</div>
                                        <div class="text-[11px] text-gray-500 whitespace-normal break-words">SL: {{ $it->quantity }}@if($it->color) • Màu: {{ $it->color->color_name }} @endif</div>
                                    </div>
                                    <div class="text-right sm:shrink-0 sm:min-w-[140px]">
                                        <div class="text-xs text-gray-500 whitespace-nowrap leading-none">Đơn giá</div>
                                        <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none">{{ number_format($unit) }} đ</div>
                                        <div class="text-xs text-gray-500 whitespace-nowrap leading-none mt-2">Tổng</div>
                                        <div class="text-sm font-semibold text-gray-900 whitespace-nowrap tabular-nums leading-none">{{ number_format($line) }} đ</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <aside class="md:col-span-2">
                    <div class="rounded-xl border overflow-hidden sticky top-4">
                        <div class="px-4 py-3 border-b bg-gray-50 font-semibold text-gray-800">Tổng kết</div>
                        <div class="px-4 py-4 space-y-3">
                            <div class="text-sm text-gray-700">
                                <div class="font-semibold mb-1">Người nhận</div>
                                <div>{{ $order->name }}</div>
                                <div class="text-gray-500">{{ $order->phone }} • {{ $order->email }}</div>
                            </div>
                            <div class="text-sm text-gray-700">
                                <div class="font-semibold mb-1">Địa chỉ giao</div>
                                <div>{{ $order->shippingAddress->line1 ?? $order->address }}</div>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Tạm tính</span>
                                <span>{{ number_format($order->subtotal ?? 0) }} đ</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Thuế</span>
                                <span>{{ number_format($order->tax_total ?? 0) }} đ</span>
                            </div>
                            <div class="flex items-center justify-between text-sm text-gray-600">
                                <span>Vận chuyển</span>
                                <span>{{ number_format($order->shipping_fee ?? 0) }} đ</span>
                            </div>
                            <div class="border-t pt-3 flex items-center justify-between text-base font-bold text-gray-900">
                                <span>Tổng cộng</span>
                                <span>{{ number_format($order->grand_total ?? $order->total_price) }} đ</span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2">
                                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg border hover:bg-gray-50"><i class="fas fa-home"></i> Trang chủ</a>
                                <a href="{{ route('user.customer-profiles.show-order', $order->id ?? 0) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700"><i class="fas fa-receipt"></i> Chi tiết đơn</a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>
@endsection


