@extends('emails.layout')

@section('content')
<h2>📅 Nhắc nhở: Kỳ trả góp sắp đến hạn</h2>

<p>Xin chào {{ $installment->user->userProfile->name ?? 'Quý khách' }},</p>

<p>Đây là email nhắc nhở về kỳ trả góp sắp đến hạn của bạn.</p>

<div class="info-box">
    <p><strong>Đơn hàng:</strong> #{{ $installment->order->order_number }}</p>
    <p><strong>Kỳ:</strong> {{ $installment->installment_number }}/{{ $installment->order->tenure_months }}</p>
    <p><strong>Số tiền:</strong> {{ number_format($installment->amount) }} VNĐ</p>
    <p style="color: #d97706;"><strong>📆 Hạn thanh toán:</strong> {{ $installment->due_date->format('d/m/Y') }} ({{ $daysUntilDue }} ngày nữa)</p>
</div>

<p><strong>Thông tin chuyển khoản:</strong></p>
<div class="info-box">
    <p><strong>Ngân hàng:</strong> Vietcombank</p>
    <p><strong>Số tài khoản:</strong> 1234567890</p>
    <p><strong>Chủ tài khoản:</strong> SHOWROOM CAR</p>
    <p><strong>Nội dung:</strong> {{ $installment->order->order_number }} KY {{ $installment->installment_number }}</p>
</div>

<p>⏰ Vui lòng thanh toán trước ngày <strong>{{ $installment->due_date->format('d/m/Y') }}</strong> để tránh phát sinh phí.</p>

<a href="{{ route('user.orders.show', $installment->order_id) }}" class="button">Xem chi tiết</a>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection
