<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\CarVariant;
use App\Models\Accessory;
use App\Models\PaymentMethod;
use App\Models\Address;
use App\Models\FinanceOption;
use App\Models\Showroom;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $carVariants = CarVariant::all();
        $accessories = Accessory::all();
        $paymentMethods = PaymentMethod::all();
        $financeOptions = FinanceOption::all();
        $showrooms = Showroom::all();
        $addresses = Address::all();
        $adminsAndStaff = User::whereIn('role', ['admin', 'manager', 'sales_person'])->get();
        $sales1 = User::where('email', 'sales1@showroom.com')->first();
        $sales2 = User::where('email', 'sales2@showroom.com')->first();
        $manager = User::where('email', 'manager@showroom.com')->first();
        $admin = User::where('email', 'admin@showroom.com')->first();

        $orders = [
            // VIP Customer Order
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'order_number' => 'ORD-2024-001',
                'status' => 'delivered',
                'total_price' => 2400000000,
                'subtotal' => 2400000000,
                'tax_total' => 120000000,
                'discount_total' => 0,
                'shipping_fee' => 0,
                'grand_total' => 2520000000,
                'note' => 'Khách hàng VIP - ưu tiên cao',
                'payment_status' => 'paid',
                'payment_method_id' => $paymentMethods->where('code', 'CASH')->first()->id,
                'transaction_id' => 'TXN-2024-001',
                'paid_at' => now()->subDays(25),
                'source' => 'walk_in',
                'tracking_number' => 'TRK-2024-001',
                'ip_address' => '113.23.45.12',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125 Safari/537.36',
                'referrer' => 'https://zalo.me/oa/123456789',
                'customer_notes' => 'Giao nhanh, kiểm tra kỹ ngoại thất.',
                'internal_notes' => 'Ưu tiên xếp lịch giao sáng thứ 2.',
                'delivery_date' => now()->subDays(25),
                'delivery_address' => '123 Đường Lê Lợi, Phường Bến Thành, Quận 1, TP. Hồ Chí Minh',
                'delivery_notes' => 'Giao xe tại showroom, kiểm tra giấy tờ trước khi bàn giao',
                'has_trade_in' => false,
                'billing_address_id' => $addresses->where('user_id', $users->where('email', 'vip@example.com')->first()->id)->first()->id ?? null,
                'shipping_address_id' => $addresses->where('user_id', $users->where('email', 'vip@example.com')->first()->id)->first()->id ?? null,
                'finance_option_id' => $financeOptions->where('code', 'VCB_001')->first()->id ?? null,
                'down_payment_amount' => 500000000,
                'monthly_payment_amount' => 35000000,
                'loan_term_months' => 36,
                'interest_rate' => 8.5,
                'sales_person_id' => $sales1?->id,
                'showroom_id' => $showrooms->where('code', 'HN_CENTER')->first()->id ?? null,
                'created_by' => $admin?->id,
                'updated_by' => $manager?->id,
                'cancelled_by' => null,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(25)
            ],
            [
                'user_id' => $users->where('email', 'vip@example.com')->first()->id,
                'order_number' => 'ORD-2024-002',
                'status' => 'confirmed',
                'total_price' => 1700000000,
                'subtotal' => 1700000000,
                'tax_total' => 85000000,
                'discount_total' => 15000000,
                'shipping_fee' => 0,
                'grand_total' => 1775000000,
                'note' => 'Đặt xe thứ 2 cho gia đình',
                'payment_status' => 'pending',
                'payment_method_id' => $paymentMethods->where('code', 'BANK_TRANSFER')->first()->id,
                'transaction_id' => 'TXN-2024-002',
                'source' => 'website',
                'tracking_number' => 'TRK-2024-002',
                'ip_address' => '14.169.88.201',
                'user_agent' => 'Mozilla/5.0 (Linux; Android 13; SM-S918B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125 Mobile Safari/537.36',
                'referrer' => 'https://www.facebook.com/?utm_source=fb_ads&utm_medium=cpc&utm_campaign=summer_sale',
                'customer_notes' => 'Muốn nhận xe cuối tuần.',
                'internal_notes' => 'Chờ xác nhận đủ tiền trước khi giao.',
                'estimated_delivery' => now()->addDays(15),
                'delivery_address' => '25 Ngô Quyền, Phường Hàng Bài, Quận Hoàn Kiếm, Hà Nội',
                'delivery_notes' => 'Giao xe tại nhà, hẹn giờ 9h sáng, gọi trước 30 phút',
                'has_trade_in' => true,
                'trade_in_brand' => 'Mercedes-Benz',
                'trade_in_model' => 'C-Class',
                'trade_in_year' => 2020,
                'trade_in_value' => 1500000000,
                'trade_in_condition' => 'Tốt, đã sử dụng 3 năm',
                'billing_address_id' => $addresses->where('user_id', $users->where('email', 'vip@example.com')->first()->id)->first()->id ?? null,
                'shipping_address_id' => $addresses->where('user_id', $users->where('email', 'vip@example.com')->first()->id)->first()->id ?? null,
                'finance_option_id' => $financeOptions->where('code', 'BIDV_001')->first()->id ?? null,
                'down_payment_amount' => 300000000,
                'monthly_payment_amount' => 28000000,
                'loan_term_months' => 48,
                'interest_rate' => 9.0,
                'sales_person_id' => $sales2?->id,
                'showroom_id' => $showrooms->where('code', 'HN_TAYHO')->first()->id ?? null,
                'created_by' => $manager?->id,
                'updated_by' => $sales2?->id,
                'cancelled_by' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2)
            ],

            // Regular Customer 1 Orders
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'order_number' => 'ORD-2024-003',
                'status' => 'delivered',
                'total_price' => 800000000,
                'subtotal' => 800000000,
                'tax_total' => 40000000,
                'discount_total' => 10000000,
                'shipping_fee' => 0,
                'grand_total' => 830000000,
                'note' => 'Xe gia đình, cần giao sớm',
                'payment_status' => 'paid',
                'payment_method_id' => $paymentMethods->where('code', 'INSTALLMENT_0')->first()->id,
                'transaction_id' => 'TXN-2024-003',
                'paid_at' => now()->subDays(40),
                'source' => 'website',
                'tracking_number' => 'TRK-2024-003',
                'ip_address' => '113.176.97.33',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
                'referrer' => 'https://www.google.com/search?q=mua+xe+gia+%C4%91%C3%ACnh&utm_source=google&utm_medium=organic',
                'customer_notes' => 'Lắp thêm camera hành trình.',
                'internal_notes' => 'Đã upsell gói film cách nhiệt.',
                'delivery_date' => now()->subDays(40),
                'delivery_address' => '321 Lý Thường Kiệt, Phường 7, Quận Tân Bình, TP. Hồ Chí Minh',
                'delivery_notes' => 'Giao xe tại showroom, dán film cách nhiệt trước khi giao',
                'has_trade_in' => false,
                'billing_address_id' => $addresses->where('user_id', $users->where('email', 'customer1@example.com')->first()->id)->first()->id ?? null,
                'shipping_address_id' => $addresses->where('user_id', $users->where('email', 'customer1@example.com')->first()->id)->first()->id ?? null,
                'finance_option_id' => $financeOptions->where('code', 'ZERO_001')->first()->id ?? null,
                'down_payment_amount' => 160000000,
                'monthly_payment_amount' => 25000000,
                'loan_term_months' => 24,
                'interest_rate' => 0.0,
                'sales_person_id' => $sales1?->id,
                'showroom_id' => $showrooms->where('code', 'HCM_Q1')->first()->id ?? null,
                'created_by' => $sales1?->id,
                'updated_by' => $sales1?->id,
                'cancelled_by' => null,
                'created_at' => now()->subDays(45),
                'updated_at' => now()->subDays(40)
            ],

            // Regular Customer 2 Orders
            [
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'order_number' => 'ORD-2024-004',
                'status' => 'pending',
                'total_price' => 1150000000,
                'subtotal' => 1150000000,
                'tax_total' => 57500000,
                'discount_total' => 0,
                'shipping_fee' => 0,
                'grand_total' => 1207500000,
                'note' => 'Cần xe đa dụng cho công việc',
                'payment_status' => 'pending',
                'payment_method_id' => $paymentMethods->where('code', 'INSTALLMENT_NORMAL')->first()->id,
                'transaction_id' => 'TXN-2024-004',
                'source' => 'phone',
                'tracking_number' => 'TRK-2024-004',
                'ip_address' => '171.224.55.78',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:125.0) Gecko/20100101 Firefox/125.0',
                'referrer' => 'https://ads.shopee.vn/?utm_source=shopee&utm_medium=cpc&utm_campaign=auto',
                'customer_notes' => 'Cần xe trước chuyến công tác.',
                'internal_notes' => 'Đang thẩm định hồ sơ tại VPBank.',
                'estimated_delivery' => now()->addDays(30),
                'delivery_address' => '987 Điện Biên Phủ, Phường 25, Quận Bình Thạnh, TP. Hồ Chí Minh',
                'delivery_notes' => 'Giao xe tại nhà, cần bãi đỗ trước toà nhà',
                'has_trade_in' => true,
                'trade_in_brand' => 'Ford',
                'trade_in_model' => 'Ranger',
                'trade_in_year' => 2019,
                'trade_in_value' => 800000000,
                'trade_in_condition' => 'Tốt, đã sử dụng 4 năm',
                'billing_address_id' => $addresses->where('user_id', $users->where('email', 'customer2@example.com')->first()->id)->first()->id ?? null,
                'shipping_address_id' => $addresses->where('user_id', $users->where('email', 'customer2@example.com')->first()->id)->first()->id ?? null,
                'finance_option_id' => $financeOptions->where('code', 'BIDV_001')->first()->id ?? null,
                'down_payment_amount' => 200000000,
                'monthly_payment_amount' => 22000000,
                'loan_term_months' => 60,
                'interest_rate' => 9.0,
                'sales_person_id' => $sales2?->id,
                'showroom_id' => $showrooms->where('code', 'HCM_Q7')->first()->id ?? null,
                'created_by' => $sales2?->id,
                'updated_by' => $sales2?->id,
                'cancelled_by' => null,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(8)
            ],
            [
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'order_number' => 'ORD-2024-005',
                'status' => 'cancelled',
                'total_price' => 900000000,
                'subtotal' => 900000000,
                'tax_total' => 45000000,
                'discount_total' => 5000000,
                'shipping_fee' => 0,
                'grand_total' => 940000000,
                'note' => 'Hủy do thay đổi kế hoạch',
                'payment_status' => 'refunded',
                'payment_method_id' => $paymentMethods->where('code', 'CASH')->first()->id,
                'transaction_id' => 'TXN-2024-005',
                'source' => 'website',
                'tracking_number' => 'TRK-2024-005',
                'ip_address' => '27.67.120.14',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 14_5) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.5 Safari/605.1.15',
                'referrer' => 'https://zalo.me/?utm_source=zalo&utm_medium=chat_share',
                'cancelled_at' => now()->subDays(55),
                'cancellation_reason' => 'Khách hàng thay đổi kế hoạch mua xe',
                'delivery_address' => '15 Cầu Giấy, Phường Quan Hoa, Quận Cầu Giấy, Hà Nội',
                'has_trade_in' => false,
                'billing_address_id' => $addresses->where('user_id', $users->where('email', 'customer2@example.com')->first()->id)->first()->id ?? null,
                'shipping_address_id' => $addresses->where('user_id', $users->where('email', 'customer2@example.com')->first()->id)->first()->id ?? null,
                'finance_option_id' => $financeOptions->where('code', 'TCB_001')->first()->id ?? null,
                'down_payment_amount' => 180000000,
                'monthly_payment_amount' => 21000000,
                'loan_term_months' => 48,
                'interest_rate' => 8.8,
                'sales_person_id' => $sales1?->id,
                'showroom_id' => $showrooms->where('code', 'DN_CENTER')->first()->id ?? null,
                'created_by' => $sales1?->id,
                'updated_by' => $sales1?->id,
                'cancelled_by' => $manager?->id,
                'created_at' => now()->subDays(60),
                'updated_at' => now()->subDays(55)
            ]
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
