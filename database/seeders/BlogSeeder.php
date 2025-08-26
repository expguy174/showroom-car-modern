<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;
 

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@showroom.com')->first();

        $searchCommons = function (string $query) {
            return null; // Always use placeholder
        };

        $blogs = [
            [
                'admin_id' => $admin ? $admin->id : null,
                'title' => 'Hướng dẫn chọn xe gia đình phù hợp',
                'content' => 'Khi chọn xe gia đình, bạn cần cân nhắc nhiều yếu tố như không gian, an toàn, tiết kiệm nhiên liệu và ngân sách. Bài viết này sẽ giúp bạn đưa ra quyết định sáng suốt.',
                'image_path' => 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Hướng dẫn chọn xe gia đình phù hợp'),
                'is_published' => true,
                'published_at' => now()->subDays(10),
                'is_active' => true,
                'status' => 'published',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(10)
            ],
            [
                'admin_id' => $admin ? $admin->id : null,
                'title' => 'So sánh các dòng xe SUV phổ biến 2024',
                'content' => 'SUV đang là xu hướng được nhiều người lựa chọn. Chúng tôi sẽ so sánh các mẫu SUV phổ biến về thiết kế, hiệu suất và giá cả.',
                'image_path' => 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('So sánh các dòng xe SUV phổ biến 2024'),
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'is_active' => true,
                'status' => 'published',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(5)
            ],
            [
                'admin_id' => $admin ? $admin->id : null,
                'title' => 'Bảo dưỡng xe định kỳ - Tại sao quan trọng?',
                'content' => 'Bảo dưỡng xe định kỳ không chỉ giúp xe hoạt động tốt mà còn tiết kiệm chi phí sửa chữa trong tương lai. Tìm hiểu lịch trình bảo dưỡng phù hợp.',
                'image_path' => 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Bảo dưỡng xe định kỳ - Tại sao quan trọng?'),
                'is_published' => true,
                'published_at' => now()->subDays(3),
                'is_active' => true,
                'status' => 'published',
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(3)
            ],
            [
                'admin_id' => $admin ? $admin->id : null,
                'title' => 'Xu hướng xe điện tại Việt Nam 2024',
                'content' => 'Xe điện đang trở thành xu hướng toàn cầu. Tại Việt Nam, thị trường xe điện cũng đang phát triển mạnh mẽ với nhiều mẫu xe mới.',
                'image_path' => 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Xu hướng xe điện tại Việt Nam 2024'),
                'is_published' => true,
                'published_at' => now()->subDays(1),
                'is_active' => true,
                'status' => 'published',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(1)
            ],
            [
                'admin_id' => $admin ? $admin->id : null,
                'title' => 'Tips lái xe an toàn trong mùa mưa',
                'content' => 'Lái xe trong mùa mưa đòi hỏi kỹ năng và sự cẩn thận đặc biệt. Bài viết này sẽ chia sẻ những tips hữu ích để đảm bảo an toàn.',
                'image_path' => 'https://placehold.co/1200x800/111827/ffffff?text=' . urlencode('Tips lái xe an toàn trong mùa mưa'),
                'is_published' => true,
                'published_at' => now()->subDays(15),
                'is_active' => true,
                'status' => 'published',
                'created_at' => now()->subDays(16),
                'updated_at' => now()->subDays(15)
            ]
        ];

        foreach ($blogs as $blog) {
            Blog::create($blog);
        }
    }
}
