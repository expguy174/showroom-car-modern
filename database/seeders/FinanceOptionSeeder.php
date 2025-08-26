<?php

namespace Database\Seeders;

use App\Models\FinanceOption;
use Illuminate\Database\Seeder;

class FinanceOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financeOptions = [
            [
                'name' => 'Gói Vay Vietcombank',
                'code' => 'VCB_001',
                'bank_name' => 'Vietcombank',
                'description' => 'Gói vay xe hơi với lãi suất ưu đãi từ Vietcombank',
                'interest_rate' => 8.5,
                'processing_fee' => 500000,
                'min_tenure' => 12,
                'max_tenure' => 84,
                'min_down_payment' => 20,
                'min_loan_amount' => 100000000,
                'max_loan_amount' => 2000000000,
                'requirements' => [
                    'Độ tuổi: 21-65 tuổi',
                    'Thu nhập tối thiểu: 15 triệu VNĐ/tháng',
                    'Thời gian công tác: Tối thiểu 12 tháng',
                    'Hồ sơ cần thiết: CMND/CCCD, sổ hộ khẩu, hợp đồng lao động, sao kê lương 3 tháng gần nhất'
                ],
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subMonths(5)
            ],
            [
                'name' => 'Gói Vay BIDV',
                'code' => 'BIDV_001',
                'bank_name' => 'BIDV',
                'description' => 'Gói vay xe hơi linh hoạt từ BIDV với thủ tục đơn giản',
                'interest_rate' => 9.0,
                'processing_fee' => 300000,
                'min_tenure' => 12,
                'max_tenure' => 72,
                'min_down_payment' => 25,
                'min_loan_amount' => 50000000,
                'max_loan_amount' => 1500000000,
                'requirements' => [
                    'Độ tuổi: 22-60 tuổi',
                    'Thu nhập tối thiểu: 12 triệu VNĐ/tháng',
                    'Thời gian công tác: Tối thiểu 6 tháng',
                    'Hồ sơ cần thiết: CMND/CCCD, sổ hộ khẩu, hợp đồng lao động, sao kê lương 6 tháng gần nhất'
                ],
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now()->subYears(3),
                'updated_at' => now()->subMonths(5)
            ],
            [
                'name' => 'Gói Vay Techcombank',
                'code' => 'TCB_001',
                'bank_name' => 'Techcombank',
                'description' => 'Gói vay xe hơi với lãi suất cạnh tranh từ Techcombank',
                'interest_rate' => 8.8,
                'processing_fee' => 400000,
                'min_tenure' => 12,
                'max_tenure' => 60,
                'min_down_payment' => 25,
                'min_loan_amount' => 75000000,
                'max_loan_amount' => 1000000000,
                'requirements' => [
                    'Độ tuổi: 21-65 tuổi',
                    'Thu nhập tối thiểu: 10 triệu VNĐ/tháng',
                    'Thời gian công tác: Tối thiểu 12 tháng',
                    'Hồ sơ cần thiết: CMND/CCCD, sổ hộ khẩu, hợp đồng lao động, sao kê lương 3 tháng gần nhất'
                ],
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subMonths(4)
            ],
            [
                'name' => 'Gói Vay MB Bank',
                'code' => 'MBB_001',
                'bank_name' => 'MB Bank',
                'description' => 'Gói vay xe hơi linh hoạt với điều kiện dễ dàng từ MB Bank',
                'interest_rate' => 9.2,
                'processing_fee' => 250000,
                'min_tenure' => 12,
                'max_tenure' => 84,
                'min_down_payment' => 20,
                'min_loan_amount' => 30000000,
                'max_loan_amount' => 1800000000,
                'requirements' => [
                    'Độ tuổi: 20-65 tuổi',
                    'Thu nhập tối thiểu: 8 triệu VNĐ/tháng',
                    'Thời gian công tác: Tối thiểu 6 tháng',
                    'Hồ sơ cần thiết: CMND/CCCD, sổ hộ khẩu, hợp đồng lao động, sao kê lương 3 tháng gần nhất'
                ],
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subMonths(4)
            ],
            [
                'name' => 'Gói Vay 0% Lãi Suất',
                'code' => 'ZERO_001',
                'bank_name' => 'Đối tác đa dạng',
                'description' => 'Gói vay xe hơi với lãi suất 0% trong thời gian khuyến mãi',
                'interest_rate' => 0.0,
                'processing_fee' => 1000000,
                'min_tenure' => 6,
                'max_tenure' => 36,
                'min_down_payment' => 30,
                'min_loan_amount' => 20000000,
                'max_loan_amount' => 500000000,
                'requirements' => [
                    'Độ tuổi: 21-60 tuổi',
                    'Thu nhập tối thiểu: 15 triệu VNĐ/tháng',
                    'Thời gian công tác: Tối thiểu 12 tháng',
                    'Hồ sơ cần thiết: CMND/CCCD, sổ hộ khẩu, hợp đồng lao động, sao kê lương 6 tháng gần nhất'
                ],
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subMonths(3)
            ],
            [
                'name' => 'Gói Vay Doanh Nghiệp',
                'code' => 'CORP_001',
                'bank_name' => 'Vietcombank',
                'description' => 'Gói vay xe hơi dành cho doanh nghiệp với lãi suất ưu đãi',
                'interest_rate' => 7.5,
                'processing_fee' => 2000000,
                'min_tenure' => 12,
                'max_tenure' => 60,
                'min_down_payment' => 30,
                'min_loan_amount' => 500000000,
                'max_loan_amount' => 5000000000,
                'requirements' => [
                    'Doanh nghiệp hoạt động tối thiểu 2 năm',
                    'Doanh thu tối thiểu: 10 tỷ VNĐ/năm',
                    'Có báo cáo tài chính 2 năm gần nhất',
                    'Hồ sơ cần thiết: Giấy phép kinh doanh, báo cáo tài chính, hợp đồng mua bán xe'
                ],
                'is_active' => true,
                'sort_order' => 6,
                'created_at' => now()->subYears(2),
                'updated_at' => now()->subMonths(2)
            ]
        ];

        foreach ($financeOptions as $option) {
            FinanceOption::create($option);
        }
    }
}
