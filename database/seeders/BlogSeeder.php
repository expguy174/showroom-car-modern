<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Admin và một số employees với role phù hợp có thể tạo blogs
        $allowedRoles = ['admin', 'manager', 'technician']; // technician có thể là content editor
        $blogAuthors = User::whereIn('role', $allowedRoles)->get();
        
        if ($blogAuthors->isEmpty()) {
            $this->command->warn('Không tìm thấy user nào có quyền tạo blogs. Bỏ qua tạo blogs.');
            return;
        }

        $this->command->info("Tìm thấy {$blogAuthors->count()} user có quyền tạo blogs:");
        foreach ($blogAuthors as $author) {
            $this->command->info("- {$author->email} ({$author->role})");
        }

        // Tạo blogs cho admin
        $admin = $blogAuthors->where('role', 'admin')->first();
        if ($admin) {
            $adminPosts = [
            [
                'user_id' => $admin->id,
                'title' => 'Kinh nghiệm mua sedan hạng B tại Việt Nam',
                'content' => 'Những lưu ý khi chọn sedan hạng B: chi phí sử dụng, không gian, trang bị an toàn...',
                'image_path' => null,
                'is_active' => true,
                'is_featured' => true,
            ],
                [
                    'user_id' => $admin->id,
                    'title' => 'So sánh các phương án trả góp 12/36 tháng',
                    'content' => 'Phân tích nhanh lãi suất, số tiền trả trước, tổng chi phí...',
                    'image_path' => null,
                    'is_active' => true,
                    'is_featured' => true,
                ],
                [
                    'user_id' => $admin->id,
                    'title' => 'Xu hướng xe điện tại Việt Nam 2024',
                    'content' => 'Phân tích thị trường xe điện và các mẫu xe nổi bật...',
                    'image_path' => null,
                    'is_active' => true,
                ],
            ];

            foreach ($adminPosts as $post) {
                Blog::create($post);
            }
        }

        // Tạo blogs cho manager
        $manager = $blogAuthors->where('role', 'manager')->first();
        if ($manager) {
            $managerPosts = [
                [
                    'user_id' => $manager->id,
                    'title' => 'Chiến lược kinh doanh xe hơi 2024',
                    'content' => 'Những xu hướng và chiến lược kinh doanh xe hơi trong năm 2024...',
                    'image_path' => null,
                    'is_active' => true,
                ],
                [
                    'user_id' => $manager->id,
                    'title' => 'Quản lý đội ngũ bán hàng hiệu quả',
                    'content' => 'Bí quyết quản lý và đào tạo đội ngũ bán hàng chuyên nghiệp...',
                    'image_path' => null,
                    'is_active' => true,
                ],
            ];

            foreach ($managerPosts as $post) {
                Blog::create($post);
            }
        }

        // Tạo blogs cho technician (content editor)
        $technicians = $blogAuthors->where('role', 'technician');
        foreach ($technicians as $tech) {
            $techPosts = [
                [
                    'user_id' => $tech->id,
                    'title' => 'Hướng dẫn bảo dưỡng xe định kỳ',
                    'content' => 'Các bước bảo dưỡng xe định kỳ để đảm bảo an toàn và tuổi thọ xe...',
                    'image_path' => null,
                    'is_active' => true,
                ],
                [
                    'user_id' => $tech->id,
                    'title' => 'Công nghệ xe hơi mới nhất 2024',
                    'content' => 'Tổng hợp những công nghệ xe hơi tiên tiến nhất trong năm 2024...',
                    'image_path' => null,
                    'is_active' => rand(0, 1) == 1, // Một số bài viết sẽ bị ẩn
                ],
            ];

            foreach ($techPosts as $post) {
                Blog::create($post);
            }
        }

        // Tạo thêm blogs ngẫu nhiên
        for ($i = 1; $i <= 5; $i++) {
            $randomAuthor = $blogAuthors->random();
            Blog::create([
                'user_id' => $randomAuthor->id,
                'title' => 'Tin tức số ' . $i . ': Ưu đãi & mẹo sử dụng xe',
                'content' => 'Nội dung bài viết ' . $i . ' về kinh nghiệm lái xe, bảo dưỡng và ưu đãi thị trường.',
                'image_path' => null,
                'is_active' => $i % 3 !== 0, // Một số bài viết sẽ bị ẩn
            ]);
        }

        $this->command->info('Đã tạo ' . Blog::count() . ' blogs từ ' . $blogAuthors->count() . ' tác giả.');
    }
}


