@extends('emails.layout')

@section('content')
<h2>🔧 Xác nhận lịch hẹn dịch vụ</h2>

<p>Xin chào {{ $appointment->customer_name }},</p>

<p>Lịch hẹn dịch vụ của bạn đã được ghi nhận thành công!</p>

<div class="info-box">
    <p><strong>Mã lịch hẹn:</strong> {{ $appointment->appointment_number }}</p>
    <p><strong>Ngày hẹn:</strong> {{ $appointment->appointment_date }}</p>
    <p><strong>Giờ hẹn:</strong> {{ $appointment->appointment_time }}</p>
    <p><strong>Loại dịch vụ:</strong> {{ $appointment->appointment_type }}</p>
    <p><strong>Trạng thái:</strong> {{ $appointment->status }}</p>
    @if(!empty($appointment->service_description))
    <p><strong>Mô tả:</strong> {{ $appointment->service_description }}</p>
    @endif
</div>

@if($appointment->carVariant)
<h3>🚗 Thông tin xe</h3>
<div class="info-box">
    <p><strong>Xe:</strong> {{ optional($appointment->carVariant->carModel->carBrand)->name }} - {{ optional($appointment->carVariant->carModel)->name }} ({{ $appointment->carVariant->name }})</p>
</div>
@endif

@if($appointment->showroom)
<h3>📍 Địa điểm</h3>
<div class="info-box">
    <p><strong>Showroom:</strong> {{ $appointment->showroom->name }}</p>
</div>
@endif

<p>Chúng tôi sẽ liên hệ để xác nhận và hỗ trợ bạn sớm nhất.</p>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection


