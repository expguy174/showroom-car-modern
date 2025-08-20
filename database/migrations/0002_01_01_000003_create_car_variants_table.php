<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_model_id')->constrained('car_models')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->string('sku')->nullable()->unique();
            // Uniqueness trong 1 model để tránh trùng tên biến thể
            $table->unique(['car_model_id', 'name']);

            // Mô tả và thông tin
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('fuel_type')->nullable(); // petrol, diesel, electric, hybrid
            $table->string('transmission')->nullable(); // manual, automatic, cvt
            $table->string('engine_size')->nullable(); // 1.5L, 2.0L
            $table->string('power')->nullable(); // 150hp, 200kW
            $table->string('torque')->nullable(); // 250Nm
            $table->string('fuel_consumption')->nullable(); // 6.5L/100km
            $table->unsignedSmallInteger('warranty_years')->nullable();

            // Giá cả và khuyến mãi
            $table->decimal('price', 15, 2)->default(0);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->decimal('original_price', 15, 2)->nullable();
            $table->boolean('has_discount')->default(false);
            $table->decimal('discount_percentage', 5, 2)->default(0);

            // Trạng thái và đánh giá
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedInteger('view_count')->default(0);


            // SEO và Marketing
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes tối ưu
            $table->index(['car_model_id', 'is_active']);
            $table->index(['car_model_id', 'is_active', 'is_available']);
            $table->index(['price', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['is_new_arrival', 'is_active']);
            $table->index(['is_bestseller', 'is_active']);
            $table->index('average_rating');
            $table->index('view_count');

            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_variants');
    }
};
