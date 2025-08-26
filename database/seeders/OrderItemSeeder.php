<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\CarVariant;
use App\Models\Accessory;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $carVariants = CarVariant::all();
        $accessories = Accessory::all();

        $orderItems = [
            // VIP Customer Order Items
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-001')->first()->id,
                'item_type' => 'car_variant',
                'item_id' => $carVariants->where('name', 'C-Class C200')->first()->id,
                'item_name' => 'Mercedes-Benz C-Class C200 2024',
                'item_sku' => 'C-CLASS-C200-2024',
                'item_metadata' => ['Màu' => 'Đen bóng', 'Nội thất' => 'Da cao cấp'],
                'quantity' => 1,
                'price' => 2400000000,
                'tax_amount' => 120000000,
                'discount_amount' => 0,
                'line_total' => 2520000000,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(25)
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-001')->first()->id,
                'item_type' => 'accessory',
                'item_id' => $accessories->where('name', 'Bọc ghế da cao cấp')->first()->id,
                'item_name' => 'Bọc ghế da cao cấp',
                'item_sku' => 'LEATHER-SEAT-001',
                'item_metadata' => ['Màu' => 'Đen', 'Chất liệu' => 'Da thật'],
                'quantity' => 1,
                'price' => 5000000,
                'tax_amount' => 250000,
                'discount_amount' => 0,
                'line_total' => 5250000,
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(25)
            ],

            // VIP Customer Order 2 Items
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-002')->first()->id,
                'item_type' => 'car_variant',
                'item_id' => $carVariants->where('name', '3 Series 320i')->first()->id,
                'item_name' => 'BMW 3 Series 320i 2024',
                'item_sku' => '3-SERIES-320I-2024',
                'item_metadata' => ['Màu' => 'Trắng ngọc trai', 'Gói' => 'M Sport'],
                'quantity' => 1,
                'price' => 1700000000,
                'tax_amount' => 85000000,
                'discount_amount' => 15000000,
                'line_total' => 1775000000,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2)
            ],

            // Regular Customer 1 Order Items
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-003')->first()->id,
                'item_type' => 'car_variant',
                'item_id' => $carVariants->where('name', 'Vios G')->first()->id,
                'item_name' => 'Toyota Vios G 2024',
                'item_sku' => 'VIOS-G-2024',
                'item_metadata' => ['Màu' => 'Bạc kim loại', 'Gói' => 'G'],
                'quantity' => 1,
                'price' => 800000000,
                'tax_amount' => 40000000,
                'discount_amount' => 10000000,
                'line_total' => 830000000,
                'created_at' => now()->subDays(45),
                'updated_at' => now()->subDays(40)
            ],

            // Regular Customer 2 Order Items
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-004')->first()->id,
                'item_type' => 'car_variant',
                'item_id' => $carVariants->where('name', 'Ranger XLT')->first()->id,
                'item_name' => 'Ford Ranger XLT 2024',
                'item_sku' => 'RANGER-XLT-2024',
                'item_metadata' => ['Màu' => 'Xám đậm', 'Gói' => 'Wildtrak'],
                'quantity' => 1,
                'price' => 1150000000,
                'tax_amount' => 57500000,
                'discount_amount' => 0,
                'line_total' => 1207500000,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(8)
            ],
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-004')->first()->id,
                'item_type' => 'accessory',
                'item_id' => $accessories->where('name', 'Camera hành trình')->first()->id,
                'item_name' => 'Camera hành trình',
                'item_sku' => 'DASH-CAM-001',
                'item_metadata' => ['Độ phân giải' => 'HD', 'GPS' => 'Có'],
                'quantity' => 1,
                'price' => 3000000,
                'tax_amount' => 150000,
                'discount_amount' => 0,
                'line_total' => 3150000,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(8)
            ],

            // Cancelled Order Items
            [
                'order_id' => $orders->where('order_number', 'ORD-2024-005')->first()->id,
                'item_type' => 'car_variant',
                'item_id' => $carVariants->where('name', 'City G')->first()->id,
                'item_name' => 'Honda City G 2024',
                'item_sku' => 'CITY-G-2024',
                'item_metadata' => ['Màu' => 'Trắng ngọc trai', 'Gói' => 'RS'],
                'quantity' => 1,
                'price' => 900000000,
                'tax_amount' => 45000000,
                'discount_amount' => 5000000,
                'line_total' => 940000000,
                'created_at' => now()->subDays(60),
                'updated_at' => now()->subDays(55)
            ]
        ];

        foreach ($orderItems as $orderItem) {
            OrderItem::create($orderItem);
        }
    }
}
