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

            // Mô tả
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();

            // Giá cả và khuyến mãi
            $table->decimal('base_price', 15, 2)->default(0)->comment('Giá gốc (MSRP)');
            $table->decimal('current_price', 15, 2)->default(0)->comment('Giá bán hiện tại');
            $table->boolean('is_on_sale')->default(false)->comment('Đang giảm giá không');
            
            // Inventory theo màu (JSON)
            $table->json('color_inventory')->nullable()->comment('{"color_id": {"quantity": 3, "reserved": 1, "available": 2}}');

            // Trạng thái và đánh giá
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_bestseller')->default(false);

            // SEO và Marketing
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes tối ưu
            $table->index(['car_model_id', 'is_active']);
            $table->index(['car_model_id', 'is_active', 'is_available']);
            $table->index(['base_price', 'is_active']);
            $table->index(['current_price', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['is_new_arrival', 'is_active']);
            $table->index(['is_bestseller', 'is_active']);
            $table->index('color_inventory'); // Index cho JSON field

            $table->index('created_at');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_variants');
    }
};
