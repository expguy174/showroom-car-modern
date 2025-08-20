<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin users
            [
                'name' => 'Admin System',
                'email' => 'admin@showroom.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234567',
                'role' => 'admin',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
                'employee_id' => 'ADM001',
                'department' => 'IT',
                'position' => 'System Administrator',
                'hire_date' => '2020-01-01',
            ],
            [
                'name' => 'Manager Sales',
                'email' => 'manager@showroom.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234568',
                'role' => 'manager',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
                'employee_id' => 'MGR001',
                'department' => 'Sales',
                'position' => 'Sales Manager',
                'hire_date' => '2021-03-15',
            ],
            
            // Sales staff
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'sales1@showroom.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234569',
                'role' => 'sales_person',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
                'employee_id' => 'SAL001',
                'department' => 'Sales',
                'position' => 'Sales Representative',
                'hire_date' => '2022-01-10',
            ],
            [
                'name' => 'Trần Thị B',
                'email' => 'sales2@showroom.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234570',
                'role' => 'sales_person',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
                'employee_id' => 'SAL002',
                'department' => 'Sales',
                'position' => 'Sales Representative',
                'hire_date' => '2022-02-20',
            ],
            
            // Service staff
            [
                'name' => 'Lê Văn C',
                'email' => 'service1@showroom.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234571',
                'role' => 'technician',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
                'employee_id' => 'SVC001',
                'department' => 'Service',
                'position' => 'Service Technician',
                'hire_date' => '2021-06-15',
            ],
            [
                'name' => 'Phạm Thị D',
                'email' => 'service2@showroom.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234572',
                'role' => 'technician',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
                'employee_id' => 'SVC002',
                'department' => 'Service',
                'position' => 'Service Advisor',
                'hire_date' => '2021-08-10',
            ],
            
            // Customer users
            [
                'name' => 'Khách hàng VIP',
                'email' => 'vip@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234573',
                'role' => 'user',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
            ],
            [
                'name' => 'Nguyễn Thị E',
                'email' => 'customer1@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234574',
                'role' => 'user',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
            ],
            [
                'name' => 'Trần Văn F',
                'email' => 'customer2@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234575',
                'role' => 'user',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
            ],
            [
                'name' => 'Lê Thị G',
                'email' => 'customer3@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234576',
                'role' => 'user',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
            ],
            [
                'name' => 'Phạm Văn H',
                'email' => 'customer4@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234577',
                'role' => 'user',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
            ],
            [
                'name' => 'Hoàng Thị I',
                'email' => 'customer5@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234578',
                'role' => 'user',
                'is_verified' => true,
                'is_active' => true,
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'last_login_at' => now(),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
