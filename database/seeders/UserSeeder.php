<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Address;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@showroom.vn',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified' => true,
                'email_verified_at' => now(),
                'employee_id' => 'ADM001',
                'department' => 'Quản trị',
                'position' => 'Quản trị viên',
                'hire_date' => now()->subYears(2)->toDateString(),
                'is_active' => true,
                'last_login_at' => now(),
                'profile' => [
                    'profile_type' => 'employee',
                    'name' => 'Quản trị viên',
                    'phone' => '0901234567',
                    'birth_date' => '1990-01-01',
                    'gender' => 'male',
                    'driver_license_number' => null,
                    'driver_license_issue_date' => null,
                    'driver_license_expiry_date' => null,
                    'driver_license_class' => null,
                    'driving_experience_years' => 0,
                    'preferred_car_types' => null,
                    'preferred_brands' => null,
                    'preferred_colors' => null,
                    'budget_min' => null,
                    'budget_max' => null,
                    'purchase_purpose' => null,
                    'customer_type' => 'new',
                    'employee_salary' => 0,
                    'employee_skills' => 'Quản trị hệ thống, báo cáo',
                    'is_vip' => false,
                ],
                'addresses' => [
                    [
                        'type' => 'home',
                        'contact_name' => 'Quản trị viên',
                        'phone' => '0901234567',
                        'address' => '12 Nguyễn Huệ, Quận 1',
                        'city' => 'TP. Hồ Chí Minh',
                        'state' => 'Quận 1',
                        'postal_code' => '700000',
                        'country' => 'Vietnam',
                        'is_default' => true,
                        'notes' => null,
                    ],
                ],
            ],
            [
                'email' => 'khachhang@example.vn',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified' => true,
                'email_verified_at' => now(),
                'employee_id' => null,
                'department' => null,
                'position' => null,
                'hire_date' => null,
                'is_active' => true,
                'last_login_at' => now()->subDays(1),
                'profile' => [
                    'profile_type' => 'customer',
                    'name' => 'Nguyễn Văn A',
                    'phone' => '0912345678',
                    'birth_date' => '1995-05-20',
                    'gender' => 'male',
                    'driver_license_number' => 'B123456789',
                    'driver_license_issue_date' => now()->subYears(3)->toDateString(),
                    'driver_license_expiry_date' => now()->addYears(7)->toDateString(),
                    'driver_license_class' => 'B',
                    'driving_experience_years' => 5,
                    'preferred_car_types' => json_encode(['suv','sedan']),
                    'preferred_brands' => json_encode(['Toyota','Hyundai','VinFast']),
                    'preferred_colors' => json_encode(['Trắng','Đen','Xanh']),
                    'budget_min' => 500000000,
                    'budget_max' => 1200000000,
                    'purchase_purpose' => 'Gia đình',
                    'customer_type' => 'new',
                    'employee_salary' => null,
                    'employee_skills' => null,
                    'is_vip' => false,
                ],
                'addresses' => [
                    [
                        'type' => 'home',
                        'contact_name' => 'Nguyễn Văn A',
                        'phone' => '0912345678',
                        'address' => '25 Lý Thường Kiệt, Hoàn Kiếm',
                        'city' => 'Hà Nội',
                        'state' => 'Hoàn Kiếm',
                        'postal_code' => '100000',
                        'country' => 'Vietnam',
                        'is_default' => true,
                        'notes' => 'Giao giờ hành chính',
                    ],
                    [
                        'type' => 'work',
                        'contact_name' => 'Nguyễn Văn A',
                        'phone' => '0912345678',
                        'address' => 'Số 1 Đại Cồ Việt, Hai Bà Trưng',
                        'city' => 'Hà Nội',
                        'state' => 'Hai Bà Trưng',
                        'postal_code' => '100000',
                        'country' => 'Vietnam',
                        'is_default' => false,
                        'notes' => null,
                    ],
                ],
            ],
        ];

        foreach ($users as $u) {
            $profile = $u['profile'];
            $addresses = $u['addresses'];
            unset($u['profile'], $u['addresses']);

            $user = User::updateOrCreate(['email' => $u['email']], $u);

            $profile['user_id'] = $user->id;
            // JSON fields guard if passed as arrays
            foreach (['preferred_car_types','preferred_brands','preferred_colors'] as $jsonField) {
                if (is_array($profile[$jsonField] ?? null)) {
                    $profile[$jsonField] = json_encode($profile[$jsonField]);
                }
            }
            UserProfile::updateOrCreate(['user_id' => $user->id], $profile);

            foreach ($addresses as $addr) {
                $addr['user_id'] = $user->id;
                Address::create($addr);
            }
        }

        // Thêm nhiều user ngẫu nhiên để test tải
        $customerNames = [
            'Nguyễn Văn', 'Trần Thị', 'Lê Minh', 'Phạm Thu', 'Hoàng Đức', 'Vũ Thị', 'Đặng Văn', 'Bùi Minh',
            'Dương Thị', 'Ngô Văn', 'Lý Thị', 'Võ Minh', 'Phan Thu', 'Tạ Văn', 'Lưu Thị', 'Mai Đức'
        ];
        $lastNames = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];
        
        for ($i = 1; $i <= 30; $i++) {
            $email = "user{$i}@example.vn";
            $fullName = $customerNames[array_rand($customerNames)] . ' ' . $lastNames[array_rand($lastNames)];
            $phoneNumber = '09' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            
            $user = User::updateOrCreate(['email' => $email], [
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified' => true,
                'email_verified_at' => now(),
                'employee_id' => null,
                'department' => null,
                'position' => null,
                'hire_date' => null,
                'is_active' => true,
                'last_login_at' => now()->subDays(rand(0,10)),
            ]);

            $profile = [
                'user_id' => $user->id,
                'profile_type' => 'customer',
                'name' => $fullName,
                'phone' => $phoneNumber, // Đảm bảo phone luôn có
                'birth_date' => now()->subYears(rand(20,55))->format('Y-m-d'),
                'gender' => rand(0,1) ? 'male' : 'female',
                'driver_license_number' => rand(0,1) ? 'B' . rand(100000000, 999999999) : null,
                'driver_license_issue_date' => rand(0,1) ? now()->subYears(rand(1,5))->format('Y-m-d') : null,
                'driver_license_expiry_date' => rand(0,1) ? now()->addYears(rand(5,10))->format('Y-m-d') : null,
                'driver_license_class' => rand(0,1) ? 'B' : null,
                'driving_experience_years' => rand(0,15),
                'preferred_car_types' => json_encode(['suv','sedan']),
                'preferred_brands' => json_encode(['Toyota','Hyundai','Kia','VinFast']),
                'preferred_colors' => json_encode(['Trắng','Đen','Đỏ','Xanh']),
                'budget_min' => rand(400,800) * 1000000,
                'budget_max' => rand(900,1800) * 1000000,
                'purchase_purpose' => rand(0,1) ? 'Gia đình' : 'Cá nhân',
                'customer_type' => ['new', 'returning', 'prospect'][rand(0,2)],
                'employee_salary' => null,
                'employee_skills' => null,
                'is_vip' => rand(0,10) === 0, // 10% chance VIP
            ];
            UserProfile::updateOrCreate(['user_id' => $user->id], $profile);

            Address::create([
                'user_id' => $user->id,
                'type' => 'home',
                'contact_name' => $fullName,
                'phone' => $phoneNumber, // Consistent phone
                'address' => rand(1,99) . ' Đường Số ' . rand(1,30) . ', Phường ' . rand(1,20),
                'city' => ['TP. Hồ Chí Minh', 'Hà Nội', 'Đà Nẵng', 'Cần Thơ'][rand(0,3)],
                'state' => 'Quận ' . rand(1,12),
                'postal_code' => '700000',
                'country' => 'Vietnam',
                'is_default' => true,
                'notes' => rand(0,1) ? 'Giao giờ hành chính' : null,
            ]);
        }

        // Thêm người dùng cho các vai trò khác
        $extraRoles = [
            ['email' => 'manager@showroom.vn', 'role' => 'manager', 'name' => 'Quản lý Kinh doanh'],
            ['email' => 'sales1@showroom.vn', 'role' => 'sales_person', 'name' => 'Nhân viên Kinh doanh 1'],
            ['email' => 'sales2@showroom.vn', 'role' => 'sales_person', 'name' => 'Nhân viên Kinh doanh 2'],
            ['email' => 'support@showroom.vn', 'role' => 'technician', 'name' => 'Kỹ thuật Dịch vụ'],
            ['email' => 'editor@showroom.vn', 'role' => 'technician', 'name' => 'Kỹ thuật Nội dung'],
        ];
        foreach ($extraRoles as $er) {
            $user = User::updateOrCreate(['email' => $er['email']], [
                'password' => Hash::make('password'),
                'role' => $er['role'],
                'email_verified' => true,
                'email_verified_at' => now(),
                'employee_id' => strtoupper(substr($er['role'],0,3)) . rand(100,999),
                'department' => in_array($er['role'], ['sales_person','manager']) ? 'Kinh doanh' : (in_array($er['role'], ['technician']) ? 'Kỹ thuật' : 'Khác'),
                'position' => $er['name'],
                'hire_date' => now()->subMonths(rand(1,24))->toDateString(),
                'is_active' => true,
                'last_login_at' => now()->subDays(rand(0,7)),
            ]);

            UserProfile::updateOrCreate(['user_id' => $user->id], [
                'user_id' => $user->id,
                'profile_type' => in_array($er['role'], ['sales_person','manager','technician']) ? 'employee' : 'customer',
                'name' => $er['name'],
                'phone' => '09' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'birth_date' => now()->subYears(rand(25,45))->format('Y-m-d'),
                'gender' => rand(0,1) ? 'male' : 'female',
                'driver_license_number' => null,
                'driver_license_issue_date' => null,
                'driver_license_expiry_date' => null,
                'driver_license_class' => null,
                'driving_experience_years' => rand(0,10),
                'preferred_car_types' => null,
                'preferred_brands' => null,
                'preferred_colors' => null,
                'budget_min' => null,
                'budget_max' => null,
                'purchase_purpose' => null,
                'customer_type' => 'new',
                'employee_salary' => rand(12,30) * 1000000,
                'employee_skills' => $er['role'] === 'sales_person' ? 'Tư vấn, chốt sales' : ($er['role'] === 'technician' ? 'Kỹ thuật, bảo trì' : 'Quản lý, điều phối'),
                'is_vip' => false,
            ]);

            Address::updateOrCreate([
                'user_id' => $user->id,
                'type' => 'work',
                'address' => 'Trụ sở Showroom',
            ], [
                'user_id' => $user->id,
                'type' => 'work',
                'contact_name' => $er['name'],
                'phone' => '09' . str_pad((string) rand(10000000, 99999999), 8, '0', STR_PAD_LEFT),
                'address' => 'Trụ sở Showroom',
                'city' => 'TP. Hồ Chí Minh',
                'state' => 'Quận 1',
                'postal_code' => '700000',
                'country' => 'Vietnam',
                'is_default' => false,
                'notes' => null,
            ]);
        }
    }
}


