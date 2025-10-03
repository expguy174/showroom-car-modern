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
        $this->call([
            DealershipSeeder::class,
            ShowroomSeeder::class,
            CarBrandSeeder::class,
            CarModelSeeder::class,
            CarVariantSeeder::class,
            CarVariantColorSeeder::class,
            UpdateColorInventorySeeder::class,
            CarVariantFeatureSeeder::class,
            CarVariantSpecificationSeeder::class,
            CarModelImageSeeder::class,
            CarVariantImageSeeder::class,
            PaymentMethodSeeder::class,
            ServiceSeeder::class,
            AccessorySeeder::class,
            UserSeeder::class,
            ReviewSeeder::class,
            TestDriveSeeder::class,
            ServiceAppointmentSeeder::class,
            FinanceOptionSeeder::class,
            PromotionSeeder::class,
            OrderSeeder::class,
            PaymentTransactionSeeder::class,
            RefundSeeder::class,
            NotificationSeeder::class,
            BlogSeeder::class,
            ContactMessageSeeder::class,
            OrderLogSeeder::class,
            InstallmentSeeder::class,
        ]);
    }
}


