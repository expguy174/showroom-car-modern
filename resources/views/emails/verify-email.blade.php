<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực email</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Xác thực địa chỉ email</h1>
    </div>
    
    <div style="background: #f9fafb; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e5e7eb;">
        <p style="margin: 0 0 20px 0;">Xin chào,</p>
        
        <p style="margin: 0 0 20px 0;">
            Cảm ơn bạn đã đăng ký tài khoản! Vui lòng nhấp vào nút bên dưới để xác thực địa chỉ email của bạn:
        </p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $verificationUrl }}" 
               style="display: inline-block; background: #667eea; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Xác thực email
            </a>
        </div>
        
        <p style="margin: 20px 0 0 0; font-size: 14px; color: #6b7280;">
            Nếu nút không hoạt động, bạn có thể sao chép và dán liên kết sau vào trình duyệt:
        </p>
        
        <p style="margin: 10px 0 20px 0; word-break: break-all; font-size: 12px; color: #667eea;">
            {{ $verificationUrl }}
        </p>
        
        <p style="margin: 20px 0 0 0; font-size: 14px; color: #6b7280;">
            Liên kết này sẽ hết hạn sau 60 phút.
        </p>
        
        <p style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af;">
            Nếu bạn không đăng ký tài khoản này, vui lòng bỏ qua email này.
        </p>
    </div>
</body>
</html>

