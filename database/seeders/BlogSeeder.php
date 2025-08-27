<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role','admin')->first() ?? User::first();
        $posts = [
            [
                'admin_id' => $admin?->id,
                'title' => 'Kinh nghiệm mua sedan hạng B tại Việt Nam',
                'content' => 'Những lưu ý khi chọn sedan hạng B: chi phí sử dụng, không gian, trang bị an toàn...',
                'image_path' => null,
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'is_active' => true,
                'status' => 'published',
            ],
            [
                'admin_id' => $admin?->id,
                'title' => 'So sánh các phương án trả góp 12/36 tháng',
                'content' => 'Phân tích nhanh lãi suất, số tiền trả trước, tổng chi phí...',
                'image_path' => null,
                'is_published' => true,
                'published_at' => now()->subDay(),
                'is_active' => true,
                'status' => 'published',
            ],
        ];

        foreach ($posts as $p) {
            Blog::create($p);
        }

        // Thêm nhiều bài viết hơn
        for ($i = 1; $i <= 8; $i++) {
            Blog::create([
                'admin_id' => $admin?->id,
                'title' => 'Tin tức số ' . $i . ': Ưu đãi & mẹo sử dụng xe',
                'content' => 'Nội dung bài viết ' . $i . ' về kinh nghiệm lái xe, bảo dưỡng và ưu đãi thị trường.',
                'image_path' => null,
                'is_published' => true,
                'published_at' => now()->subDays($i),
                'is_active' => true,
                'status' => 'published',
            ]);
        }
    }
}


