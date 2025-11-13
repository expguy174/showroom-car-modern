# 📥 HƯỚNG DẪN SETUP SAU KHI CLONE CODE TỪ GITHUB

## ❓ CÂU HỎI THƯỜNG GẶP

### Q: Khi clone code về máy mới, MailHog đã được setup sẵn chưa?
**A: CHƯA!** Bạn cần setup MailHog trên máy mới.

### Q: Tại sao?
**A:** Vì:
- MailHog binary (`MailHog.exe`) **KHÔNG được commit vào Git**
- MailHog được lưu tại `C:\Users\[Username]\mailhog\` (ngoài project)
- Chỉ có **script setup** được commit vào Git
- Mỗi máy cần download MailHog binary riêng

---

## 🚀 SETUP SAU KHI CLONE CODE

### Bước 1: Clone code và cài đặt dependencies
```bash
git clone [your-repo-url]
cd showroom-car-modern
composer install
npm install
```

### Bước 2: Copy file .env
```bash
cp .env.example .env
php artisan key:generate
```

### Bước 3: Cấu hình database trong .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Bước 4: Chạy migrations
```bash
php artisan migrate --seed
```

### Bước 5: Setup MailHog (QUAN TRỌNG!)

**Cách nhanh nhất (Khuyến nghị):**
```powershell
powershell -ExecutionPolicy Bypass -File setup-mailhog-quick.ps1
```

Script này sẽ:
- ✅ Tự động download MailHog (nếu chưa có)
- ✅ Khởi động MailHog tự động
- ✅ Mở web UI tự động

### Bước 6: Cấu hình email trong .env
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

### Bước 7: Clear cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Bước 8: Test MailHog
```bash
php artisan email:test test@example.com --type=verify-email
```

Mở http://localhost:8025 để xem email.

---

## 📋 CHECKLIST SAU KHI CLONE

- [ ] Đã clone code từ GitHub
- [ ] Đã chạy `composer install`
- [ ] Đã copy `.env.example` thành `.env`
- [ ] Đã cấu hình database trong `.env`
- [ ] Đã chạy `php artisan migrate --seed`
- [ ] Đã setup MailHog (chạy script setup)
- [ ] Đã cấu hình email trong `.env`
- [ ] Đã chạy `php artisan config:clear`
- [ ] Đã test email thành công

---

## 🔍 GIẢI THÍCH CHI TIẾT

### File nào được commit vào Git?
✅ **Được commit:**
- `setup-mailhog-quick.ps1` - Script setup nhanh (khuyến nghị)
- `MAILHOG_SETUP_COMPLETE.md` - Hướng dẫn sử dụng
- `SETUP_SAU_KHI_CLONE.md` - Hướng dẫn setup sau khi clone
- `app/Console/Commands/TestEmailNotifications.php` - Command test email
- Tất cả code Laravel

❌ **KHÔNG được commit:**
- `MailHog.exe` - Binary của MailHog (lưu tại `$env:USERPROFILE\mailhog\`)
- `.env` - File cấu hình (có thể có `.env.example`)
- `vendor/` - Dependencies (đã có `composer.json`)
- `node_modules/` - Dependencies (đã có `package.json`)

### Tại sao MailHog không được commit?
1. **Kích thước lớn**: MailHog binary khá lớn (~10MB)
2. **Platform-specific**: Mỗi OS cần binary khác nhau
3. **Không cần thiết**: Có thể download tự động bằng script
4. **Best practice**: Binary files không nên commit vào Git

---

## 🎯 QUY TRÌNH TỰ ĐỘNG HÓA (TÙY CHỌN)

Bạn có thể tạo script `setup-new-machine.ps1` để tự động hóa toàn bộ:

```powershell
# Setup script cho máy mới
Write-Host "Setting up project on new machine..." -ForegroundColor Green

# 1. Install dependencies
Write-Host "Installing Composer dependencies..." -ForegroundColor Yellow
composer install

# 2. Setup .env
if (-not (Test-Path .env)) {
    Write-Host "Creating .env file..." -ForegroundColor Yellow
    Copy-Item .env.example .env
    php artisan key:generate
}

# 3. Setup MailHog
Write-Host "Setting up MailHog..." -ForegroundColor Yellow
powershell -ExecutionPolicy Bypass -File setup-mailhog-quick.ps1

# 4. Clear cache
Write-Host "Clearing cache..." -ForegroundColor Yellow
php artisan config:clear
php artisan cache:clear

Write-Host "Setup complete!" -ForegroundColor Green
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Configure database in .env" -ForegroundColor White
Write-Host "2. Run: php artisan migrate --seed" -ForegroundColor White
Write-Host "3. Test: php artisan email:test test@example.com --type=verify-email" -ForegroundColor White
```

---

## 📝 TÓM TẮT

✅ **Sau khi clone code:**
1. Chạy `composer install`
2. Copy `.env.example` thành `.env` và cấu hình
3. **Chạy script setup MailHog** (QUAN TRỌNG!)
4. Cấu hình email trong `.env`
5. `php artisan config:clear`
6. Test email

❌ **MailHog KHÔNG tự động có sẵn** vì:
- Binary không được commit vào Git
- Mỗi máy cần download riêng
- Script setup sẽ tự động download khi cần

---

## 🎉 KẾT LUẬN

**Có, bạn CẦN setup MailHog lại trên máy mới!**

Nhưng rất đơn giản:
```powershell
powershell -ExecutionPolicy Bypass -File setup-mailhog-quick.ps1
```

Chỉ 1 lệnh là xong! 🚀

