# Migration README

Xem hướng dẫn đầy đủ tại `docs/migrations.md`.

Tóm tắt nội dung:
- Nguyên tắc an toàn: không sửa/xoá migration đã chạy ở production
- Quy ước đặt tên và phân nhóm `0001_*` … `0008_*` và `YYYY_MM_DD_*`
- Viết `Schema::create` đầy đủ (kèm `softDeletes()` và `timestamps()`)
- Viết `Schema::table` khi bổ sung/đổi tên cột (cần `doctrine/dbal` nếu rename)
- Gộp (squash) migration trong giai đoạn dev (chưa phát hành)
- Lệnh thường dùng: migrate, fresh --seed, rollback, refresh, test

Vui lòng đọc `docs/migrations.md` để xem chi tiết và ví dụ.
