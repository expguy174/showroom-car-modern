# ✅ MAILHOG SETUP HOÀN TẤT!

## 🎉 MailHog đã được cài đặt và cấu hình thành công!

### 📍 Thông tin kết nối:
- **SMTP Server**: `127.0.0.1:1025`
- **Web UI**: http://localhost:8025
- **Encryption**: Không cần (null)

---

## 🚀 CÁCH SỬ DỤNG:

### 1. Khởi động MailHog (nếu chưa chạy):
```powershell
Start-Process "$env:USERPROFILE\mailhog\MailHog.exe"
```

Hoặc chạy thủ công:
```powershell
cd $env:USERPROFILE\mailhog
.\MailHog.exe
```

### 2. Xem emails:
Mở browser: **http://localhost:8025**

Bạn sẽ thấy:
- Danh sách tất cả emails đã nhận
- Preview HTML
- Raw email content
- Headers

### 3. Test email:
```bash
# Test tất cả emails
php artisan email:test test@example.com

# Hoặc test từng loại
php artisan email:test test@example.com --type=order-status
php artisan email:test test@example.com --type=order-cancelled
php artisan email:test test@example.com --type=payment-status
php artisan email:test test@example.com --type=installment-paid
php artisan email:test test@example.com --type=installment-reminder
php artisan email:test test@example.com --type=installment-overdue
php artisan email:test test@example.com --type=verify-email
```

### 4. Test flow đăng ký:
1. Đăng ký tài khoản mới
2. Email verification sẽ được gửi đến MailHog
3. Mở http://localhost:8025 để xem email
4. Copy verification link và test

---

## ⚙️ CẤU HÌNH HIỆN TẠI (.env):

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@showroom.com
MAIL_FROM_NAME=Showroom
```

---

## 🔄 CHUYỂN ĐỔI:

### Từ MailHog sang Mailtrap:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

### Từ MailHog sang Log:
```env
MAIL_MAILER=log
```

Sau khi thay đổi, chạy:
```bash
php artisan config:clear
```

---

## 📝 LƯU Ý:

- MailHog chỉ chạy khi bạn mở ứng dụng
- Emails sẽ bị xóa khi đóng MailHog
- MailHog chỉ hoạt động local (không cần internet)
- Perfect cho development và testing!

---

## 🎯 BẠN ĐÃ SẴN SÀNG!

Bây giờ bạn có thể:
- ✅ Test tất cả email flows
- ✅ Xem emails trong MailHog web UI
- ✅ Test email verification
- ✅ Không cần lo về giới hạn email!

---

## 🎤 TIPS CHO THUYẾT TRÌNH

### Trước khi thuyết trình:
1. ✅ Khởi động MailHog: `Start-Process "$env:USERPROFILE\mailhog\MailHog.exe"`
2. ✅ Mở MailHog web UI: http://localhost:8025
3. ✅ Test nhanh: `php artisan email:test demo@example.com --type=verify-email`

### Khi thuyết trình:
- Mở 2 tabs: Ứng dụng + MailHog web UI
- Demo đăng ký → Email xuất hiện real-time trong MailHog
- Show HTML preview và verification link

**Happy Testing! 🚀**

