<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Bảo dưỡng định kỳ',
                'code' => 'maintenance',
                'description' => 'Dịch vụ bảo dưỡng định kỳ theo khuyến nghị của nhà sản xuất',
                'category' => 'maintenance',
                'duration_minutes' => 180, // 3 hours
                'price' => 1200000,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'requirements' => 'Thay dầu nhớt và bộ lọc, Kiểm tra hệ thống phanh, Kiểm tra hệ thống điện',
                'notes' => 'Gói bảo dưỡng toàn diện cho xe',
            ],
            [
                'name' => 'Sửa chữa chuyên nghiệp',
                'code' => 'repair',
                'description' => 'Sửa chữa các sự cố với đội ngũ kỹ thuật viên chuyên môn cao',
                'category' => 'repair',
                'duration_minutes' => 480, // 1-3 days average
                'price' => 2000000, // Base price, varies by issue
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
                'requirements' => 'Chẩn đoán lỗi bằng thiết bị hiện đại, Sửa chữa động cơ và hộp số',
                'notes' => 'Giá có thể thay đổi tùy theo mức độ hư hỏng',
            ],
            [
                'name' => 'Bảo hiểm xe hơi',
                'code' => 'insurance',
                'description' => 'Gói bảo hiểm toàn diện với mức phí hợp lý và quyền lợi tối ưu',
                'category' => 'maintenance', // Map to existing enum
                'duration_minutes' => 120, // 1-2 hours
                'price' => 1500000, // Annual premium
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'requirements' => 'Bảo hiểm bắt buộc trách nhiệm dân sự, Bảo hiểm tự nguyện toàn diện',
                'notes' => 'Phí bảo hiểm hàng năm',
            ],
            [
                'name' => 'Tài chính linh hoạt',
                'code' => 'finance',
                'description' => 'Giải pháp tài chính đa dạng với lãi suất cạnh tranh',
                'category' => 'maintenance', // Map to existing enum
                'duration_minutes' => 180, // 1-3 days processing
                'price' => 0, // No direct cost
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'requirements' => 'Vay trả góp xe hơi, Lãi suất cạnh tranh từ 0%',
                'notes' => 'Tư vấn tài chính miễn phí',
            ],
            [
                'name' => 'Phụ kiện chính hãng',
                'code' => 'accessories',
                'description' => 'Cung cấp đầy đủ phụ kiện chính hãng với chất lượng cao',
                'category' => 'cosmetic', // Map to existing enum
                'duration_minutes' => 60, // 30 minutes - 2 hours
                'price' => 500000, // Base price
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 5,
                'requirements' => 'Phụ kiện nội thất cao cấp, Phụ kiện ngoại thất và bảo vệ',
                'notes' => 'Bảo hành chính hãng',
            ],
            [
                'name' => 'Tư vấn chuyên nghiệp',
                'code' => 'consultation',
                'description' => 'Dịch vụ tư vấn chuyên nghiệp về xe hơi và dịch vụ',
                'category' => 'diagnostic', // Map to existing enum
                'duration_minutes' => 60, // 30 minutes - 2 hours
                'price' => 0, // Free consultation
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 6,
                'requirements' => 'Tư vấn chọn xe phù hợp, Tư vấn bảo dưỡng và sửa chữa',
                'notes' => 'Hỗ trợ 24/7',
            ],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(['code' => $s['code']], $s);
        }
    }
}


