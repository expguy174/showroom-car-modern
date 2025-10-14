@extends('emails.layout')

@section('content')
<h2>Cập nhật trạng thái đơn hàng</h2>

<p>Xin chào {{ $order->user->userProfile->name ?? 'Quý khách' }},</p>

<p>Đơn hàng <strong>#{{ $order->order_number }}</strong> của bạn đã được cập nhật trạng thái.</p>

<div class="info-box">
    <p><strong>Trạng thái mới:</strong> 
        @php
            $statusLabels = [
                'pending' => 'Chờ xử lý',
                'confirmed' => 'Đã xác nhận',
                'shipping' => 'Đang giao hàng',
                'delivered' => 'Đã giao hàng',
                'cancelled' => 'Đã hủy'
            ];
        @endphp
        {{ $statusLabels[$newStatus] ?? $newStatus }}
    </p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->grand_total) }} VNĐ</p>
    @if($order->tracking_number && $newStatus === 'shipping')
    <p><strong>Mã vận đơn:</strong> {{ $order->tracking_number }}</p>
    @endif
</div>

@if($newStatus === 'shipping')
<p>Đơn hàng đang trên đường đến bạn! Vui lòng để ý điện thoại để nhận hàng.</p>
@elseif($newStatus === 'delivered')
<p>Cảm ơn bạn đã mua hàng! Nếu có bất kỳ vấn đề gì, vui lòng liên hệ với chúng tôi.</p>
@endif

<a href="{{ route('user.orders.show', $order->id) }}" class="button">Xem chi tiết đơn hàng</a>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection
