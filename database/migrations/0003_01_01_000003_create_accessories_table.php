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
            $table->string('code')->unique();
            $table->string('sku')->unique(); // Stock Keeping Unit
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->json('compatible_car_brands')->nullable();
            $table->json('compatible_car_models')->nullable();
            $table->json('compatible_car_years')->nullable();
            
            // Pricing
            $table->decimal('price', 15, 2);
            $table->decimal('original_price', 15, 2)->nullable();
            $table->decimal('cost_price', 15, 2)->nullable();
            $table->decimal('wholesale_price', 15, 2)->nullable();
            $table->boolean('is_on_sale')->default(false);
            $table->decimal('sale_price', 15, 2)->nullable();
            $table->date('sale_start_date')->nullable();
            $table->date('sale_end_date')->nullable();
            
            // Inventory
			$table->unsignedInteger('stock_quantity')->default(0);
			$table->unsignedInteger('min_stock_level')->default(0);
			$table->unsignedInteger('max_stock_level')->nullable();
            $table->enum('stock_status', ['in_stock', 'low_stock', 'out_of_stock', 'discontinued'])->default('in_stock');
            $table->boolean('track_quantity')->default(true);
            $table->boolean('allow_backorder')->default(false);
			$table->unsignedInteger('backorder_quantity')->default(0);
            
            // Images and media
			$table->string('main_image_path', 2048)->nullable();
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();
            $table->string('manual_pdf_path')->nullable();
            
            // Specifications
            $table->json('specifications')->nullable();
            $table->json('features')->nullable();
            $table->text('installation_instructions')->nullable();
            $table->text('warranty_info')->nullable();
			$table->unsignedInteger('warranty_months')->nullable();
            
            // SEO and marketing
            $table->string('slug')->unique();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_new')->default(false);
			$table->unsignedInteger('sort_order')->default(0);
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->enum('status', ['active', 'inactive', 'draft', 'archived'])->default('active');
            
            // Statistics
			$table->unsignedInteger('view_count')->default(0);
			$table->unsignedInteger('purchase_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
			$table->unsignedInteger('rating_count')->default(0);
            
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
            $table->boolean('is_available')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes tối ưu hóa - bao gồm tất cả indexes cần thiết
            $table->index(['category', 'is_active']);
            $table->index(['brand', 'is_active']);
            $table->index(['stock_status', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['is_on_sale', 'is_active']);
            $table->index('price');
            $table->index('sort_order');
            $table->index(['is_new_arrival', 'is_active']);
            $table->index(['is_bestseller', 'is_active']);
            $table->index(['is_popular', 'is_active']);
            $table->index('average_rating');
            $table->index('view_count');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accessories');
    }
};