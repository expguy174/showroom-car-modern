<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Tiền mặt',
                'code' => 'CASH',
                'provider' => 'Nội bộ',
                'type' => 'offline',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 0,
                'config' => [
                    'description' => 'Thanh toán bằng tiền mặt tại showroom',
                    'instructions' => 'Vui lòng mang đầy đủ giấy tờ và tiền mặt đến showroom'
                ],
                'notes' => 'Phương thức thanh toán truyền thống'
            ],
            [
                'name' => 'Chuyển khoản ngân hàng',
                'code' => 'BANK_TRANSFER',
                'provider' => 'Ngân hàng',
                'type' => 'offline',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 0,
                'config' => [
                    'bank_accounts' => [
                        [
                            'bank_name' => 'Vietcombank',
                            'account_number' => '1234567890',
                            'account_name' => 'CÔNG TY TNHH SHOWROOM Ô TÔ',
                            'branch' => 'Chi nhánh Hà Nội'
                        ],
                        [
                            'bank_name' => 'BIDV',
                            'account_number' => '0987654321',
                            'account_name' => 'CÔNG TY TNHH SHOWROOM Ô TÔ',
                            'branch' => 'Chi nhánh TP.HCM'
                        ]
                    ],
                    'description' => 'Chuyển khoản trực tiếp vào tài khoản ngân hàng',
                    'instructions' => 'Vui lòng ghi rõ mã đơn hàng trong nội dung chuyển khoản'
                ],
                'notes' => 'Phương thức thanh toán an toàn và phổ biến'
            ],
            [
                'name' => 'Thẻ tín dụng/ghi nợ',
                'code' => 'CREDIT_CARD',
                'provider' => 'VNPay',
                'type' => 'online',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 1.5,
                'config' => [
                    'supported_cards' => ['Visa', 'Mastercard', 'JCB', 'American Express'],
                    'description' => 'Thanh toán bằng thẻ tín dụng hoặc thẻ ghi nợ',
                    'instructions' => 'Thẻ phải được phát hành tại Việt Nam'
                ],
                'notes' => 'Phí giao dịch 1.5% giá trị đơn hàng'
            ],
            [
                'name' => 'Ví điện tử',
                'code' => 'E_WALLET',
                'provider' => 'VNPay',
                'type' => 'online',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 1.0,
                'config' => [
                    'supported_wallets' => ['VNPay', 'Momo', 'ZaloPay', 'ShopeePay'],
                    'description' => 'Thanh toán qua ví điện tử',
                    'instructions' => 'Đảm bảo tài khoản ví có đủ số dư'
                ],
                'notes' => 'Phí giao dịch 1% giá trị đơn hàng'
            ],
            [
                'name' => 'Trả góp 0%',
                'code' => 'INSTALLMENT_0',
                'provider' => 'Ngân hàng đối tác',
                'type' => 'offline',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 0,
                'config' => [
                    'tenure_options' => [6, 12, 24, 36],
                    'min_amount' => 50000000,
                    'max_amount' => 2000000000,
                    'description' => 'Trả góp 0% lãi suất trong thời gian khuyến mãi',
                    'instructions' => 'Áp dụng cho khách hàng có thu nhập ổn định'
                ],
                'notes' => 'Chương trình khuyến mãi có thời hạn'
            ],
            [
                'name' => 'Trả góp thường',
                'code' => 'INSTALLMENT_NORMAL',
                'provider' => 'Ngân hàng đối tác',
                'type' => 'offline',
                'is_active' => true,
                'fee_flat' => 0,
                'fee_percent' => 0,
                'config' => [
                    'tenure_options' => [12, 24, 36, 48, 60, 72],
                    'min_amount' => 30000000,
                    'max_amount' => 2000000000,
                    'interest_rate' => 8.5,
                    'description' => 'Trả góp với lãi suất cạnh tranh',
                    'instructions' => 'Hồ sơ vay sẽ được thẩm định bởi ngân hàng'
                ],
                'notes' => 'Lãi suất từ 8.5% - 12% tùy theo hồ sơ'
            ]
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
