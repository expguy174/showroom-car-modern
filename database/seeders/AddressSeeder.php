<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $addresses = [
            // Admin addresses
            [
                'user_id' => $users->where('email', 'admin@showroom.com')->first()->id,
                'type' => 'home',
                'is_default' => true,
                'full_name' => 'Admin System',
                'phone' => '0901234567',
                'address' => '123 Đường Lê Lợi, Tầng 5, Tòa nhà A',
                'city' => 'Hà Nội',
                'state' => 'Hà Nội',
                'postal_code' => '100000',
                'country' => 'Việt Nam',
                'notes' => 'Địa chỉ văn phòng chính'
            ],
            [
                'user_id' => $users->where('email', 'admin@showroom.com')->first()->id,
                'type' => 'work',
                'is_default' => false,
                'full_name' => 'Admin System',
                'phone' => '0901234567',
                'address' => '456 Đường Nguyễn Huệ, Tầng 10, Tòa nhà B',
                'city' => 'Hà Nội',
                'state' => 'Hà Nội',
                'postal_code' => '100000',
                'country' => 'Việt Nam',
                'notes' => 'Địa chỉ chi nhánh'
            ],

            // Sales person addresses
            [
                'user_id' => $users->where('email', 'sales1@showroom.com')->first()->id,
                'type' => 'home',
                'is_default' => true,
                'full_name' => 'Nguyễn Văn A',
                'phone' => '0901234568',
                'address' => '789 Đường Trần Hưng Đạo, Phường 1',
                'city' => 'Hà Nội',
                'state' => 'Hà Nội',
                'postal_code' => '100000',
                'country' => 'Việt Nam',
                'notes' => 'Địa chỉ nhà riêng'
            ],

            // Customer addresses
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'type' => 'home',
                'is_default' => true,
                'full_name' => 'Trần Thị B',
                'phone' => '0901234569',
                'address' => '321 Đường Lý Thường Kiệt, Phường 2',
                'city' => 'Hà Nội',
                'state' => 'Hà Nội',
                'postal_code' => '100000',
                'country' => 'Việt Nam',
                'notes' => 'Địa chỉ giao hàng chính'
            ],
            [
                'user_id' => $users->where('email', 'customer1@example.com')->first()->id,
                'type' => 'work',
                'is_default' => false,
                'full_name' => 'Trần Thị B',
                'phone' => '0901234569',
                'address' => '654 Đường Hai Bà Trưng, Tầng 3, Tòa nhà C',
                'city' => 'Hà Nội',
                'state' => 'Hà Nội',
                'postal_code' => '100000',
                'country' => 'Việt Nam',
                'notes' => 'Địa chỉ văn phòng'
            ],
            [
                'user_id' => $users->where('email', 'customer2@example.com')->first()->id,
                'type' => 'home',
                'is_default' => true,
                'full_name' => 'Lê Văn C',
                'phone' => '0901234570',
                'address' => '987 Đường Điện Biên Phủ, Phường 3',
                'city' => 'Hà Nội',
                'state' => 'Hà Nội',
                'postal_code' => '100000',
                'country' => 'Việt Nam',
                'notes' => 'Địa chỉ nhà riêng'
            ]
        ];

        foreach ($addresses as $address) {
            Address::create($address);
        }
    }
}
