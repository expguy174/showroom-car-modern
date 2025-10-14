@extends('emails.layout')

@section('content')
<h2>🎉 Xác nhận đơn hàng thành công!</h2>

<p>Xin chào {{ $order->user->userProfile->name ?? $order->user->email ?? 'Quý khách' }},</p>

<p>Cảm ơn bạn đã đặt hàng tại {{ config('app.name') }}! Đơn hàng của bạn đã được xác nhận và đang được xử lý.</p>

<div class="info-box">
    <p><strong>Mã đơn hàng:</strong> #{{ $order->order_number }}</p>
    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Phương thức thanh toán:</strong> {{ $order->paymentMethod->name ?? 'N/A' }}</p>
    <p><strong>Trạng thái:</strong> 
        @php
            $statusLabels = [
                'pending' => '⏳ Chờ xử lý',
                'confirmed' => '✅ Đã xác nhận',
                'shipping' => '🚚 Đang giao hàng',
                'delivered' => '📦 Đã giao hàng',
                'cancelled' => '❌ Đã hủy'
            ];
        @endphp
        {{ $statusLabels[$order->status] ?? $order->status }}
    </p>
</div>

<h3>📦 Sản phẩm đã đặt</h3>
<div class="info-box">
    @foreach($order->items as $item)
    <p style="margin: 5px 0; display: flex; justify-content: space-between;">
        <span>{{ $item->item_name ?? 'Sản phẩm' }} x{{ $item->quantity }}</span>
        <strong>{{ number_format($item->line_total ?? ($item->price * $item->quantity)) }} VNĐ</strong>
    </p>
    @endforeach
    <hr style="margin: 10px 0; border: none; border-top: 2px solid #667eea;">
    <p style="margin: 10px 0; display: flex; justify-content: space-between; font-size: 18px;">
        <strong>Tổng cộng:</strong>
        <strong style="color: #667eea;">{{ number_format($order->grand_total ?? $order->total_price) }} VNĐ</strong>
    </p>
</div>

@if($order->shippingAddress || $order->billingAddress)
<h3>📍 Thông tin giao hàng</h3>
<div class="info-box">
    <p><strong>Người nhận:</strong> {{ $order->user->userProfile->name ?? $order->user->email }}</p>
    @if($order->user->userProfile && $order->user->userProfile->phone)
    <p><strong>Số điện thoại:</strong> {{ $order->user->userProfile->phone }}</p>
    @endif
    @if($order->shippingAddress)
    <p><strong>Địa chỉ giao hàng:</strong><br>
    {{ $order->shippingAddress->line1 }}<br>
    {{ $order->shippingAddress->city ?? '' }} {{ $order->shippingAddress->district ?? '' }}<br>
    {{ $order->shippingAddress->province ?? '' }}
    </p>
    @endif
    @if($order->note)
    <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
    @endif
</div>
@endif

<p>Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận và giao hàng.</p>

<a href="{{ route('user.orders.show', $order->id) }}" class="button">Xem chi tiết đơn hàng</a>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection 