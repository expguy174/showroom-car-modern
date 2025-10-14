# 📧 EMAIL NOTIFICATIONS SETUP GUIDE

## ✅ ĐÃ HOÀN THÀNH

### 1. Mail Classes (6 classes)
- ✅ `OrderStatusChanged.php` - Cập nhật trạng thái đơn hàng
- ✅ `OrderCancelled.php` - Hủy đơn hàng
- ✅ `PaymentStatusChanged.php` - Cập nhật thanh toán
- ✅ `InstallmentPaid.php` - Thanh toán kỳ trả góp
- ✅ `InstallmentReminder.php` - Nhắc nhở trước hạn
- ✅ `InstallmentOverdue.php` - Cảnh báo quá hạn

### 2. Email Templates (7 templates)
- ✅ `layout.blade.php` - Layout chung
- ✅ `order-status-changed.blade.php`
- ✅ `order-cancelled.blade.php`
- ✅ `payment-status-changed.blade.php`
- ✅ `installment-paid.blade.php`
- ✅ `installment-reminder.blade.php`
- ✅ `installment-overdue.blade.php`

### 3. Integration
- ✅ SendsOrderNotifications Trait - Gửi cả in-app + email
- ✅ InstallmentController - Gửi email khi admin xác nhận
- ✅ Console Commands - Gửi email tự động (reminder, overdue)
- ✅ TestEmailNotifications Command - Test dễ dàng

---

## 🚀 SETUP CHO DEMO (5 PHÚT)

### BƯỚC 1: Đăng ký Mailtrap (FREE)

1. Vào: https://mailtrap.io/
2. Click "Sign Up" (FREE tier)
3. Verify email
4. Login vào Mailtrap

### BƯỚC 2: Tạo Inbox & Lấy Credentials

1. Click "Create Inbox" hoặc dùng inbox có sẵn
2. Click vào inbox vừa tạo
3. Tab "SMTP Settings" → Click "Show Credentials"
4. Copy 4 thông tin:
   - Host: `sandbox.smtp.mailtrap.io`
   - Port: `2525`
   - Username: `xxxxxxxxxx`
   - Password: `xxxxxxxxxx`

### BƯỚC 3: Cập nhật .env

Mở file `.env` và cập nhật:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=paste_your_username_here
MAIL_PASSWORD=paste_your_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@showroom-demo.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**LƯU Ý:** Thay `paste_your_username_here` và `paste_your_password_here` bằng credentials từ Mailtrap!

### BƯỚC 4: Clear Cache

```bash
php artisan config:clear
```

### BƯỚC 5: Test Email

```bash
php artisan email:test your-email@example.com
```

**Kết quả mong đợi:**
```
Testing email notifications to: your-email@example.com

→ Sent: OrderStatusChanged
→ Sent: OrderCancelled
→ Sent: PaymentStatusChanged
→ Sent: InstallmentPaid (regular)
→ Sent: InstallmentPaid (last installment)
→ Sent: InstallmentReminder
→ Sent: InstallmentOverdue

✓ Test completed! Check your inbox.
```

### BƯỚC 6: Kiểm tra trong Mailtrap

1. Vào Mailtrap inbox: https://mailtrap.io/inboxes
2. Bạn sẽ thấy 7 emails vừa gửi
3. Click vào từng email để xem preview
4. ✅ **SẴN SÀNG CHO DEMO!**

---

## 🎬 HƯỚNG DẪN DEMO/THUYẾT TRÌNH

### Chuẩn bị trước:

1. ✅ Clear Mailtrap inbox (delete old emails)
2. ✅ Mở 2 browser tabs:
   - Tab 1: Admin panel của bạn
   - Tab 2: Mailtrap inbox
3. ✅ Test 1 lần để chắc chắn works

### Demo Flow:

#### Scenario 1: Admin xác nhận thanh toán kỳ trả góp

```
1. Giới thiệu: "Khi admin xác nhận thanh toán kỳ trả góp..."
2. Vào Admin → Installments → Chọn order → Mark as paid
3. Switch sang Mailtrap tab
4. Refresh → Email xuất hiện!
5. Click email → Show HTML preview
6. Giải thích: "Khách hàng nhận email real-time như này..."
```

#### Scenario 2: Auto reminder

```bash
1. Giải thích: "Hệ thống tự động nhắc nhở 3 ngày trước..."
2. Run command: php artisan installments:remind-upcoming
3. Show Mailtrap → Email reminder appeared!
```

#### Scenario 3: Admin cập nhật trạng thái đơn hàng

```
1. Admin → Orders → Update status
2. Mailtrap → Email notification!
```

---

## 🧪 TEST COMMANDS

### Test tất cả emails:
```bash
php artisan email:test demo@example.com
```

### Test từng loại:
```bash
php artisan email:test demo@example.com --type=order-status
php artisan email:test demo@example.com --type=installment-paid
php artisan email:test demo@example.com --type=installment-reminder
```

### Test auto commands:
```bash
php artisan installments:check-overdue
php artisan installments:remind-upcoming
```

---

## 📋 CHECKLIST TRƯỚC KHI DEMO

- [ ] Đã đăng ký Mailtrap
- [ ] Đã copy credentials vào .env
- [ ] Đã chạy `php artisan config:clear`
- [ ] Đã test: `php artisan email:test demo@example.com`
- [ ] Thấy 7 emails trong Mailtrap inbox
- [ ] Clear inbox cho presentation
- [ ] Mở 2 tabs: Admin + Mailtrap
- [ ] **SẴN SÀNG!** 🎉

---

## 💡 TIPS CHO PRESENTATION TỐT

✅ Giải thích business value trước technical
✅ Show real-time email (impressive!)
✅ Highlight: Automatic, Professional, User-friendly
✅ Demo nhiều scenarios khác nhau
✅ Mention: Works with any SMTP provider (SendGrid, Mailgun, etc.)

---

## 🆘 TROUBLESHOOTING

### Lỗi: "Connection refused"
```bash
# Check config
php artisan config:show mail

# Clear và test lại
php artisan config:clear
php artisan email:test test@example.com
```

### Email không thấy trong Mailtrap
- Check .env có đúng credentials không
- Check đúng inbox không
- Refresh page
- Check spam folder (không có trong Mailtrap)

### "Class 'Mail' not found"
```bash
composer dump-autoload
```

---

## 📞 SUPPORT

Nếu gặp vấn đề, check:
1. File .env có đúng không
2. Đã clear cache chưa: `php artisan config:clear`
3. Mailtrap credentials có đúng không
4. Internet connection có ổn không

---

**🎯 TẤT CẢ ĐÃ SẴN SÀNG! CHỈ CẦN SETUP MAILTRAP VÀ TEST THÔI!**
php artisan email:test demo@example.com
php artisan email:test demo@example.com --type=order-status
php artisan email:test demo@example.com --type=order-cancelled
php artisan email:test demo@example.com --type=payment-status
php artisan email:test demo@example.com --type=installment-paid
php artisan email:test demo@example.com --type=installment-reminder
php artisan email:test demo@example.com --type=installment-overdue