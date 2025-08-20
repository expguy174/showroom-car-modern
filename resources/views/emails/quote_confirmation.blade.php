<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Xác nhận yêu cầu báo giá</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;color:#111;margin:0;padding:0;background:#f6f7fb}
        .container{max-width:640px;margin:0 auto;background:#fff;padding:24px}
        .btn{display:inline-block;background:#2563EB;color:#fff;padding:10px 16px;border-radius:6px;text-decoration:none}
        .muted{color:#6b7280;font-size:12px}
    </style>
    </head>
<body>
    <div class="container">
        <h2>Xin chào {{ $lead->full_name }},</h2>
        <p>Chúng tôi đã nhận được yêu cầu báo giá của bạn với mã <strong>#{{ $lead->lead_number }}</strong>.</p>
        <p>Thông tin liên hệ:</p>
        <ul>
            <li>Số điện thoại: <strong>{{ $lead->phone }}</strong></li>
            <li>Email: <strong>{{ $lead->email ?? 'N/A' }}</strong></li>
        </ul>
        @if(!empty($lead->lead_description))
            <p>Nội dung: {{ $lead->lead_description }}</p>
        @endif
        <p>Chúng tôi sẽ liên hệ lại bạn trong thời gian sớm nhất để tư vấn chi tiết.</p>
        <p>Cảm ơn bạn đã quan tâm AutoLux!</p>
        <p class="muted">Đây là email tự động, vui lòng không trả lời trực tiếp.</p>
    </div>
</body>
</html>


