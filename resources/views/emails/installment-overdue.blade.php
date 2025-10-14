@extends('emails.layout')

@section('content')
<h2>⚠️ Cảnh báo: Kỳ trả góp đã quá hạn</h2>

<p>Xin chào {{ $installment->user->userProfile->name ?? 'Quý khách' }},</p>

<p>Kỳ trả góp của bạn đã <strong style="color: #dc2626;">QUÁ HẠN {{ $daysOverdue }} ngày</strong>. Vui lòng thanh toán ngay để tránh ảnh hưởng đến lịch trả góp.</p>

<div class="info-box" style="border-left-color: #dc2626; background-color: #fef2f2;">
    <p><strong>Đơn hàng:</strong> #{{ $installment->order->order_number }}</p>
    <p><strong>Kỳ:</strong> {{ $installment->installment_number }}/{{ $installment->order->tenure_months }}</p>
    <p><strong>Số tiền:</strong> {{ number_format($installment->amount) }} VNĐ</p>
    <p style="color: #dc2626;"><strong>📆 Đã quá hạn:</strong> {{ $installment->due_date->format('d/m/Y') }} ({{ $daysOverdue }} ngày)</p>
</div>

<p><strong>⚠️ LƯU Ý QUAN TRỌNG:</strong></p>
<ul style="color: #991b1b; line-height: 1.8;">
    <li>Thanh toán trễ có thể phát sinh phí phạt</li>
    <li>Ảnh hưởng đến lịch sử tín dụng của bạn</li>
    <li>Có thể ảnh hưởng đến các khoản vay sau này</li>
</ul>

<p><strong>Thông tin chuyển khoản:</strong></p>
<div class="info-box">
    <p><strong>Ngân hàng:</strong> Vietcombank</p>
    <p><strong>Số tài khoản:</strong> 1234567890</p>
    <p><strong>Chủ tài khoản:</strong> SHOWROOM CAR</p>
    <p><strong>Nội dung:</strong> {{ $installment->order->order_number }} KY {{ $installment->installment_number }}</p>
</div>

<p>🆘 Nếu bạn gặp khó khăn về tài chính, vui lòng liên hệ ngay với chúng tôi qua:</p>
<p>📞 Hotline: <strong>1900-xxxx</strong><br>
📧 Email: <strong>support@showroom.com</strong></p>

<a href="{{ route('user.orders.show', $installment->order_id) }}" class="button" style="background-color: #dc2626;">Thanh toán ngay</a>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection
