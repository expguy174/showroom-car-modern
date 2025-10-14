@extends('emails.layout')

@section('content')
<h2>🚗 Xác nhận đặt lịch lái thử xe</h2>

<p>Xin chào {{ $testDrive->name }},</p>

<p>Cảm ơn bạn đã đặt lịch lái thử xe tại {{ config('app.name') }}. Lịch hẹn của bạn đã được xác nhận!</p>

<div class="info-box">
    <p><strong>Mã đặt lịch:</strong> #{{ $testDrive->id }}</p>
    <p><strong>Ngày lái thử:</strong> {{ optional($testDrive->preferred_date)->format('d/m/Y') }}</p>
    <p><strong>Giờ lái thử:</strong> {{ $testDrive->preferred_time }}</p>
    <p><strong>Trạng thái:</strong> 
        @php
            $statusLabels = [
                'pending' => '⏳ Chờ xác nhận',
                'confirmed' => '✅ Đã xác nhận',
                'completed' => '✅ Đã hoàn thành',
                'cancelled' => '❌ Đã hủy'
            ];
        @endphp
        {{ $statusLabels[$testDrive->status] ?? $testDrive->status }}
    </p>
</div>

<h3>🚗 Thông tin xe</h3>
<div class="info-box">
    @if($testDrive->carVariant)
    <p><strong>Dòng xe:</strong> {{ $testDrive->carVariant->carModel->carBrand->name ?? 'N/A' }}</p>
    <p><strong>Model:</strong> {{ $testDrive->carVariant->carModel->name ?? 'N/A' }}</p>
    <p><strong>Phiên bản:</strong> {{ $testDrive->carVariant->name ?? 'N/A' }}</p>
    @endif
</div>

<h3>📍 Địa điểm & Liên hệ</h3>
<div class="info-box">
    <p><strong>Showroom:</strong> {{ config('app.name') }}</p>
    <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận XYZ, TP.HCM</p>
    <p><strong>Điện thoại:</strong> 1900-xxxx</p>
</div>

<p><strong>⚠️ Lưu ý quan trọng:</strong></p>
<ul style="color: #4a5568; line-height: 1.8;">
    <li>Vui lòng đến trước 15 phút so với giờ hẹn</li>
    <li>Mang theo CMND/CCCD để làm thủ tục</li>
    <li>Nếu có thay đổi, vui lòng liên hệ sớm nhất</li>
</ul>

<p>Chúng tôi rất mong được gặp bạn và hỗ trợ bạn trải nghiệm xe một cách tốt nhất!</p>

<p>Trân trọng,<br>{{ config('app.name') }}</p>
@endsection 