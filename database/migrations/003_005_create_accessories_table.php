<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->json('compatible_car_brands')->nullable();
            $table->json('compatible_car_models')->nullable();
            $table->json('compatible_car_years')->nullable();
            
            // Pricing
            $table->decimal('base_price', 15, 2)->comment('Giá gốc (MSRP)');
            $table->decimal('current_price', 15, 2)->comment('Giá bán hiện tại');
            $table->boolean('is_on_sale')->default(false)->comment('Đang giảm giá không');
            $table->date('sale_start_date')->nullable();
            $table->date('sale_end_date')->nullable();
            
            // Inventory
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock', 'discontinued'])->default('in_stock');
            
            // Images
            $table->json('gallery')->nullable();
            
            // Specifications
            $table->json('specifications')->nullable();
            $table->json('features')->nullable();
            $table->text('installation_instructions')->nullable();
            $table->text('warranty_info')->nullable();
			$table->unsignedInteger('warranty_months')->nullable();
            
            // SEO and marketing
            $table->string('slug')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Statistics - Có thể tính từ bảng khác nếu cần
            
            // Showroom specific fields
            $table->boolean('installation_service_available')->default(false);
            $table->decimal('installation_fee', 15, 2)->nullable();
            $table->text('installation_requirements')->nullable();
            $table->integer('installation_time_minutes')->nullable();
            
            // Warranty and support
            $table->text('warranty_terms')->nullable();
            $table->string('warranty_contact')->nullable();
            $table->text('return_policy')->nullable();
            $table->text('support_contact')->nullable();
            $table->unsignedSmallInteger('return_policy_days')->nullable();
            
            // Physical attributes
            $table->decimal('weight', 8, 2)->nullable(); // kg
            $table->string('dimensions')->nullable(); // L x W x H
            $table->string('material')->nullable();
            $table->json('color_options')->nullable();
            
            // E-commerce flags
            $table->boolean('is_new_arrival')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes tối ưu hóa - bao gồm tất cả indexes cần thiết
            $table->index(['category', 'is_active']);
            $table->index(['stock_status', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['is_on_sale', 'is_active']);
            $table->index(['base_price', 'is_active']);
            $table->index(['current_price', 'is_active']);
            $table->index('sort_order');
            $table->index(['is_new_arrival', 'is_active']);
            $table->index(['is_bestseller', 'is_active']);
            $table->index(['is_popular', 'is_active']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};