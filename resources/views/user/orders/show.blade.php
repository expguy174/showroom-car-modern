@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6 flex items-center justify-between">
                    <div>
            <h1 class="text-2xl font-bold text-gray-900">Đơn hàng #{{ $order->order_number ?? $order->id }}</h1>
            <div class="text-sm text-gray-500 mt-1">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</div>
        </div>
        <a href="{{ route('user.order.index') }}" class="text-indigo-600 hover:text-indigo-800">Quay lại danh sách</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-4">
            @foreach($order->items as $item)
                @php
                    $model = $item->item; $color = $item->color; $colorName = $color?->color_name;
                    $imageUrl = null;
                    if ($item->item_type === 'car_variant' && $model?->images?->isNotEmpty()) {
                        $first = $model->images->first();
                        $imageUrl = $first->image_url ?: ($first->image_path ? asset('storage/'.$first->image_path) : null);
                    } elseif ($item->item_type === 'accessory') {
                        $imageUrl = $model?->image_url ? (filter_var($model->image_url, FILTER_VALIDATE_URL) ? $model->image_url : asset('storage/'.$model->image_url)) : null;
                        }
                    @endphp
                <div class="flex items-center gap-4 bg-white rounded-xl border border-gray-200 p-4">
                    <div class="w-16 h-16 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $model?->name ?? $item->item_name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-image"></i></div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="font-medium text-gray-900">{{ $model?->name ?? $item->item_name }}</div>
                        <div class="text-sm text-gray-500">x{{ $item->quantity }} @if($colorName) • Màu: {{ $colorName }} @endif</div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold">{{ number_format($item->price) }} đ</div>
                        <div class="text-xs text-gray-500">Tạm tính: {{ number_format(($item->price ?? 0) * (int)$item->quantity) }} đ</div>
                    </div>
                </div>
            @endforeach
                    </div>
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-sm text-gray-500">Phương thức thanh toán</div>
                <div class="text-base font-semibold text-gray-900 mt-1">{{ $order->paymentMethod->name ?? '—' }}</div>
                    </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-sm text-gray-500">Địa chỉ thanh toán</div>
                @php $bill = $order->billingAddress; @endphp
                <div class="mt-1 text-sm text-gray-800">{{ $bill ? trim($bill->address) : '—' }}</div>
                </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="text-sm text-gray-500">Địa chỉ giao hàng</div>
                @php $ship = $order->shippingAddress ?: $order->billingAddress; @endphp
                <div class="mt-1 text-sm text-gray-800">{{ $ship ? trim($ship->address) : '—' }}</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>Tạm tính</span>
                    <span>{{ number_format($order->subtotal ?? ($order->total_price ?? 0)) }} đ</span>
                        </div>
                <div class="flex items-center justify-between text-sm text-gray-600 mt-1">
                    <span>Thuế (VAT)</span>
                    <span>{{ number_format($order->tax_total ?? 0) }} đ</span>
                                                </div>
                <div class="flex items-center justify-between text-sm text-gray-600 mt-1">
                    <span>Phí vận chuyển</span>
                    <span>{{ number_format($order->shipping_fee ?? 0) }} đ</span>
                </div>
                <div class="border-t my-3"></div>
                <div class="flex items-center justify-between text-base font-bold text-gray-900">
                    <span>Tổng cộng</span>
                    <span>{{ number_format($order->grand_total ?? $order->total_price) }} đ</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
