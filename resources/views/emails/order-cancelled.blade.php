@extends('emails.layout')

@section('content')
<h2>Thông báo hủy đơn hàng</h2>

<p>Xin chào {{ $order->user->userProfile->name ?? 'Quý khách' }},</p>

<p>Đơn hàng <strong>#{{ $order->order_number }}</strong> của bạn đã bị hủy.</p>

<div class="info-box">
    <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->grand_total) }} VNĐ</p>
    @if($reason)
    <p><strong>Lý do:</strong> {{ $reason }}</p>
    @endif
</div>

<p>Nếu bạn đã thanh toán, chúng tôi sẽ hoàn tiền trong vòng 5-7 ngày làm việc.</p>

<p>Nếu có thắc mắc, vui lòng liên hệ với chúng tôi qua hotline: <strong>1900-xxxx</strong></p>

<a href="{{ route('user.orders.show', $order->id) }}" class="button">Xem chi tiết</a>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection
