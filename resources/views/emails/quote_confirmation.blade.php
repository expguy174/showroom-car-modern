@extends('emails.layout')

@section('content')
<h2>💬 Xác nhận yêu cầu báo giá</h2>

<p>Xin chào {{ $lead->name ?? 'Quý khách' }},</p>

<p>Cảm ơn bạn đã quan tâm đến {{ config('app.name') }}! Chúng tôi đã nhận được yêu cầu báo giá của bạn.</p>

<div class="info-box">
    <p><strong>Mã yêu cầu:</strong> #{{ $lead->lead_number }}</p>
    <p><strong>Số điện thoại:</strong> {{ $lead->phone }}</p>
    <p><strong>Email:</strong> {{ $lead->email ?? 'N/A' }}</p>
    @if(!empty($lead->lead_description))
    <p><strong>Nội dung:</strong> {{ $lead->lead_description }}</p>
    @endif
</div>

<p>Đội ngũ tư vấn của chúng tôi sẽ liên hệ lại bạn trong thời gian sớm nhất để cung cấp báo giá chi tiết và tư vấn phù hợp nhất.</p>

<p>Nếu bạn cần hỗ trợ gấp, vui lòng liên hệ:</p>
<div class="info-box">
    <p>📞 Hotline: <strong>1900-xxxx</strong></p>
    <p>📧 Email: <strong>support@showroom.com</strong></p>
</div>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection


