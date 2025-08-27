<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_core_tables_exist(): void
    {
        $expectedTables = [
            // 001_* core
            'users',
            'sessions',
            'password_reset_tokens',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',

            // 002_* cars
            'car_brands',
            'car_models',
            'car_variants',
            'car_variant_specifications',
            'car_variant_features',
            'car_variant_colors',
            'car_variant_images',
            'car_model_images',

            // 003_* business
            'dealerships',
            'showrooms',
            'accessories',
            'services',
            'finance_options',

            // 004_* user content
            'user_profiles',
            'addresses',
            'reviews',
            'test_drives',
            'contact_messages',

            // 005_* commerce
            'payment_methods',
            'cart_items',
            'wishlist_items',
            'orders',
            'order_items',
            'order_logs',

            // 006_* payments
            'payment_transactions',
            'refunds',
            'installments',

            // 007_* engagement
            'service_appointments',
            'notifications',
            'promotions',
            'blogs',
        ];

        foreach ($expectedTables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Failed asserting that table '{$table}' exists."
            );
        }
    }
}


