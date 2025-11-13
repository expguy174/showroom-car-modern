@extends('emails.layout')

@section('content')
<h2>Cập nhật thanh toán</h2>

<p>Xin chào {{ $order->user->userProfile->name ?? 'Quý khách' }},</p>

<p>Trạng thái thanh toán cho đơn hàng <strong>#{{ $order->order_number }}</strong> đã được cập nhật.</p>

<div class="info-box">
    <p><strong>Trạng thái mới:</strong> 
        @php
            $statusLabels = [
                'pending' => 'Chờ thanh toán',
            'processing' => 'Đang xử lý',
            'partial' => 'Thanh toán một phần',
                'completed' => 'Đã thanh toán',
                'failed' => 'Thanh toán thất bại',
                'refunded' => 'Đã hoàn tiền',
            'cancelled' => 'Đã hủy',
            ];
        @endphp
        {{ $statusLabels[$newStatus] ?? $newStatus }}
    </p>
    <p><strong>Tổng tiền:</strong> {{ number_format($order->grand_total) }} VNĐ</p>
    @if($newStatus === 'completed' && $order->paid_at)
    <p><strong>Thời gian thanh toán:</strong> {{ $order->paid_at->format('d/m/Y H:i') }}</p>
    @endif
</div>

@if($newStatus === 'completed')
<p>✅ Cảm ơn bạn đã thanh toán! Đơn hàng của bạn đang được xử lý.</p>
@elseif($newStatus === 'partial')
<p>💳 Tiền cọc đã được xác nhận. Đơn hàng có thể giao hàng. Bạn có thể thanh toán các kỳ trả góp theo lịch.</p>
@elseif($newStatus === 'failed')
<p>⚠️ Thanh toán không thành công. Vui lòng thử lại hoặc liên hệ với chúng tôi.</p>
@elseif($newStatus === 'refunded')
<p>Số tiền đã được hoàn lại vào tài khoản của bạn trong vòng 5-7 ngày làm việc.</p>
@endif

<a href="{{ route('user.orders.show', $order->id) }}" class="button">Xem chi tiết</a>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection
