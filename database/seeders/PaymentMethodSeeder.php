<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Tiền mặt',
                'code' => 'cash',
                'provider' => null,
                'type' => 'offline',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 0,
                'config' => null,
                'sort_order' => 1,
                'notes' => 'Thanh toán trực tiếp tại showroom',
            ],
            [
                'name' => 'Chuyển khoản',
                'code' => 'bank_transfer',
                'provider' => 'BANK',
                'type' => 'offline',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 0,
                'config' => json_encode(['account_name' => 'CONG TY TNHH SHOWROOM', 'account_number' => '0123456789', 'bank' => 'Vietcombank - CN TP.HCM']),
                'sort_order' => 2,
                'notes' => 'Chuyển khoản ngân hàng',
            ],
            [
                'name' => 'VNPay',
                'code' => 'vnpay',
                'provider' => 'VNPAY',
                'type' => 'online',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 2.2,
                'config' => json_encode(['tmn_code' => env('VNPAY_TMN_CODE'), 'hash_secret' => env('VNPAY_HASH_SECRET')]),
                'sort_order' => 3,
                'notes' => 'Thanh toán trực tuyến qua VNPay',
            ],
            [
                'name' => 'MoMo',
                'code' => 'momo',
                'provider' => 'MOMO',
                'type' => 'online',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 2.2,
                'config' => json_encode(['partner_code' => env('MOMO_PARTNER_CODE'), 'access_key' => env('MOMO_ACCESS_KEY')]),
                'sort_order' => 4,
                'notes' => 'Thanh toán qua ví MoMo',
            ],
        ];

        foreach ($methods as $m) {
            PaymentMethod::updateOrCreate(['code' => $m['code']], $m);
        }
    }
}


