<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Core data - phải chạy trước
        $this->call([
            PaymentMethodSeeder::class,
            FinanceOptionSeeder::class,
        ]);

        // 2. Business entities
        $this->call([
            DealershipSeeder::class,
            ShowroomSeeder::class,
        ]);

        // 3. Car data
        $this->call([
            CarBrandSeeder::class,
            CarModelSeeder::class,
            CarVariantSeeder::class,
            CarSpecificationSeeder::class,
            CarVariantColorSeeder::class,
            CarVariantFeatureSeeder::class,
            CarVariantOptionSeeder::class,
            CarModelImageSeeder::class,
            CarVariantImageSeeder::class,
        ]);

        // 4. Accessories
        $this->call([
            AccessorySeeder::class,
        ]);

        // 5. Users and profiles
        $this->call([
            UserSeeder::class,
            AddressSeeder::class,
            CustomerProfileSeeder::class,
        ]);

        // 6. Business transactions
        $this->call([
            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderLogSeeder::class,
            PaymentTransactionSeeder::class,
            InstallmentSeeder::class,
            RefundSeeder::class,
        ]);

        // 7. Services
        $this->call([
            ServiceSeeder::class,
            TestDriveSeeder::class,
            ServiceAppointmentSeeder::class,
        ]);

        // 8. Content and communication
        $this->call([
            BlogSeeder::class,
            PromotionSeeder::class,
            ReviewSeeder::class,
            ContactMessageSeeder::class,
            NotificationSeeder::class,
        ]);

        // 9. All seeders now populate nullable columns with defaults; no fill-null step needed
    }
}
