<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt lịch lái thử xe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #10b981;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f9fafb;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .booking-details {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚗 Xác nhận đặt lịch lái thử xe!</h1>
        <p>Mã đặt lịch: #{{ $testDrive->id }}</p>
    </div>

    <div class="content">
        <h2>Xin chào {{ $testDrive->name }},</h2>
        
        <p>Cảm ơn bạn đã đặt lịch lái thử xe tại Showroom Car. Lịch hẹn của bạn đã được xác nhận.</p>

        <div class="booking-details">
            <h3>📅 Chi tiết lịch hẹn</h3>
            
            <div class="item">
                <span><strong>Mã đặt lịch:</strong></span>
                <span>#{{ $testDrive->id }}</span>
            </div>
            
            <div class="item">
                <span><strong>Ngày đặt:</strong></span>
                <span>{{ $testDrive->created_at->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="item">
                <span><strong>Ngày lái thử:</strong></span>
                <span>{{ optional($testDrive->preferred_date)->format('d/m/Y') }}</span>
            </div>
            
            <div class="item">
                <span><strong>Giờ lái thử:</strong></span>
                <span>{{ $testDrive->preferred_time }}</span>
            </div>
            
            <div class="item">
                <span><strong>Trạng thái:</strong></span>
                <span>
                    @switch($testDrive->status)
                        @case('pending')
                            ⏳ Chờ xác nhận
                            @break
                        @case('confirmed')
                            ✅ Đã xác nhận
                            @break
                        @case('completed')
                            ✅ Đã hoàn thành
                            @break
                        @case('cancelled')
                            ❌ Đã hủy
                            @break
                        @default
                            {{ $testDrive->status }}
                    @endswitch
                </span>
            </div>
        </div>

        <div class="booking-details">
            <h3>🚗 Thông tin xe</h3>
            <p><strong>Dòng xe:</strong> {{ $testDrive->carVariant->carModel->carBrand->name ?? 'N/A' }}</p>
            <p><strong>Model:</strong> {{ $testDrive->carVariant->carModel->name ?? 'N/A' }}</p>
            <p><strong>Phiên bản:</strong> {{ $testDrive->carVariant->name ?? 'N/A' }}</p>
        </div>

        <div class="booking-details">
            <h3>👤 Thông tin liên hệ</h3>
            <p><strong>Họ tên:</strong> {{ $testDrive->name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $testDrive->phone }}</p>
            <p><strong>Email:</strong> {{ $testDrive->email }}</p>
            @if($testDrive->notes)
            <p><strong>Ghi chú:</strong> {{ $testDrive->notes }}</p>
            @endif
        </div>

        <div class="booking-details">
            <h3>📍 Địa điểm</h3>
            <p><strong>Showroom:</strong> Showroom Car</p>
            <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận XYZ, TP.HCM</p>
            <p><strong>Điện thoại:</strong> 0123 456 789</p>
        </div>

        <p><strong>Lưu ý quan trọng:</strong></p>
        <ul>
            <li>Vui lòng đến trước 15 phút so với giờ hẹn</li>
            <li>Mang theo CMND/CCCD để làm thủ tục</li>
            <li>Nếu có thay đổi, vui lòng liên hệ sớm nhất</li>
        </ul>

        <p>Chúng tôi rất mong được gặp bạn và hỗ trợ bạn trải nghiệm xe một cách tốt nhất!</p>

        <p>Trân trọng,<br>
        <strong>Showroom Car Team</strong></p>
    </div>

    <div class="footer">
        <p>Email này được gửi tự động, vui lòng không trả lời.</p>
        <p>© {{ date('Y') }} Showroom Car. Tất cả quyền được bảo lưu.</p>
    </div>
</body>
</html> 