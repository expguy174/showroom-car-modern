## Hướng dẫn Migration (Laravel)

Mục tiêu: Chuẩn hoá, dễ đọc và an toàn khi phát triển/triển khai. Hỗ trợ nhu cầu gộp (squash) migration trong giai đoạn chưa phát hành.

### Nguyên tắc chung
- **Không chỉnh sửa migration đã chạy trên môi trường production.** Nếu đã deploy, mọi thay đổi phải tạo migration mới dùng `Schema::table`.
- **Chỉ gộp (merge/squash) các migration “alter” vào file `create_*` khi dự án chưa phát hành** (chỉ local/dev). Sau khi gộp, cần chạy `migrate:fresh` để đồng bộ DB.
- **Đặt đủ ràng buộc trong migration tạo bảng:** foreign key, unique/index, `softDeletes()`, `timestamps()`… nên nằm ngay trong `Schema::create` thay vì tạo file alter rời.
- **Đặt tên, thứ tự nhất quán** để đảm bảo trình tự migrate ổn định giữa các máy/CI.

### Quy ước đặt tên và thứ tự
Trong repo đang dùng hai kiểu tiền tố:
- Kiểu nhóm có thứ tự cố định: `0001_01_01_*`, `0002_01_01_*`, … để gom theo domain và đảm bảo sắp xếp ổn định.
- Kiểu theo mốc thời gian chuẩn Laravel: `YYYY_MM_DD_HHMMSS_*` cho các phần mở rộng muộn hoặc thử nghiệm.

Gợi ý phân nhóm hiện tại (tham khảo):
- `0001_*`: Core hệ thống (users, sessions, cache, jobs, payments methods,...)
- `0002_*`: Danh mục xe (brands, models, variants, images, specs,...)
- `0003_*`: Showroom/đại lý, accessories, reviews, customer profiles
- `0004_*`: Test drives, contact messages, finance options, service appointments
- `0005_*`: Giỏ hàng/đơn hàng (cart, orders, order items, wishlist)
- `0006_*`: Thanh toán (transactions, refunds, installments)
- `0007_*`: Nội dung/thông báo (promotions, notifications, blogs)
- `0008_*`: Logs (order logs)
- `YYYY_*`: Bổ sung phát sinh (ví dụ `2025_08_20_*`)

Khi thêm migration mới, hãy chọn nhóm phù hợp. Nếu bổ sung cho bảng đã tồn tại, dùng timestamp chuẩn.

### Tạo bảng mới (khuyến nghị)
```bash
php artisan make:migration create_example_table
```
Trong file `Schema::create('example', ...)`:
- Thêm đầy đủ cột và ràng buộc ngay từ đầu: `softDeletes()`, `timestamps()`, index/unique/foreign keys.
- Ví dụ: thêm `softDeletes()` ngay trước `timestamps()` để đồng nhất.

### Thay đổi bảng hiện hữu
```bash
php artisan make:migration add_new_column_to_example_table
```
- Dùng `Schema::table('example', function (Blueprint $table) { ... })`.
- Đổi tên cột/bảng cần cài `doctrine/dbal`:
  - `composer require doctrine/dbal --dev`
  - Sau đó mới dùng `$table->renameColumn(...)` hoặc `$table->rename(...)`.

### Gộp (squash) migration trong giai đoạn dev (chưa phát hành)
1. Xác định các file `Schema::table` chỉ nhằm bổ sung nhẹ cho bảng mới tạo gần đây (ví dụ thêm `softDeletes`, index đơn giản...).
2. Chuyển logic đó vào file `create_*` tương ứng (giữ đúng thứ tự cột/ràng buộc).
3. Xoá các file alter tương ứng đã thay thế.
4. Chạy lại cơ sở dữ liệu từ đầu để xác nhận:
```bash
php artisan migrate:fresh --seed
```

Lưu ý: Không áp dụng bước này nếu migration đã chạy ở production.

### Chạy migration
- Chạy/áp dụng migration:
```bash
php artisan migrate
```
- Tạo mới DB từ đầu và seed dữ liệu mẫu (local/dev):
```bash
php artisan migrate:fresh --seed
```
- Rollback 1 batch gần nhất:
```bash
php artisan migrate:rollback
```
- Reset toàn bộ về chưa migrate:
```bash
php artisan migrate:reset
```
- Refresh (rollback rồi migrate lại):
```bash
php artisan migrate:refresh --seed
```

### Cấu hình CSDL local/test
- Dự án có sẵn `database/database.sqlite`. Có thể dùng SQLite cho test nhanh:
  - `.env` (local/test):
    - `DB_CONNECTION=sqlite`
    - `DB_DATABASE=database/database.sqlite`
  - Tạo file nếu chưa có:
```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
```

### Kiểm thử sau khi thay đổi migration
- Chạy seeder để có dữ liệu nền:
```bash
php artisan db:seed
```
- Chạy test:
```bash
php artisan test
```

### Thực hành tốt khi viết migration
- Đặt tên cột rõ ràng, kiểu dữ liệu và độ dài hợp lý.
- Thêm index cho các cột tìm kiếm/lọc thường xuyên.
- Khai báo foreign key với hành vi `onDelete`/`onUpdate` phù hợp (vd: `cascade`, `set null`).
- Tránh logic phức tạp trong migration (tính toán dữ liệu lớn). Nếu bắt buộc, cân nhắc script riêng/v2 data migration.
- Đảm bảo migration chạy được nhiều lần trong local/CI (idempotent theo vòng đời phát triển).

### Cảnh báo an toàn (prod)
- Không xoá/sửa file migration đã chạy ở production.
- Nếu cần thay đổi lớn, tách thành nhiều migration nhỏ, dễ rollback.
- Kiểm tra downtime/lock khi thêm index/foreign key đối với DB lớn; cân nhắc chạy ngoài giờ.

---
Tài liệu này phản ánh quy ước hiện có trong repo và các chỉnh sửa gần đây (ví dụ gộp `softDeletes()` vào `create_customer_profiles`). Cập nhật tài liệu nếu quy ước thay đổi.


