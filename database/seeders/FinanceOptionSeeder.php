<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinanceOption;

class FinanceOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            [
                'name' => 'Trả góp 12 tháng',
                'code' => 'FIN-12M',
                'bank_name' => 'Vietcombank',
                'description' => 'Khoản vay 12 tháng, lãi suất cố định.',
                'interest_rate' => 9.50,
                'processing_fee' => 500000,
                'min_tenure' => 6,
                'max_tenure' => 12,
                'min_down_payment' => 20.00,
                'min_loan_amount' => 200000000.00,
                'max_loan_amount' => 2000000000.00,
                'requirements' => 'CMND/CCCD, Hộ khẩu, Chứng minh thu nhập.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Trả góp 36 tháng',
                'code' => 'FIN-36M',
                'bank_name' => 'Techcombank',
                'description' => 'Khoản vay 36 tháng, lãi suất ưu đãi.',
                'interest_rate' => 10.50,
                'processing_fee' => 700000,
                'min_tenure' => 12,
                'max_tenure' => 36,
                'min_down_payment' => 30.00,
                'min_loan_amount' => 300000000.00,
                'max_loan_amount' => 3000000000.00,
                'requirements' => 'CMND/CCCD, Hộ khẩu, Hợp đồng lao động, Sao kê lương.',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($options as $o) {
            FinanceOption::updateOrCreate(['code' => $o['code']], $o);
        }
    }
}


