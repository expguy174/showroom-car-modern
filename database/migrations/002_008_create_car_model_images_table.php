<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_model_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_model_id')->constrained('car_models')->onDelete('cascade');
            $table->string('image_url', 2048); // URL ảnh
            $table->string('alt_text')->nullable(); // Alt text cho SEO
            $table->string('title')->nullable(); // Tiêu đề/chú thích ảnh

            // Loại ảnh
            $table->string('image_type')->default('gallery')->comment('gallery, interior, exterior, color_swatch');

            // Sắp xếp và hiển thị
            $table->boolean('is_main')->default(false); // Ảnh chính
            $table->boolean('is_active')->default(true); // Ảnh có hiển thị không
            $table->unsignedInteger('sort_order')->default(0); // Thứ tự sắp xếp

            // Thông tin bổ sung
            $table->text('description')->nullable(); // Mô tả chi tiết ảnh

            $table->timestamps();

            // Indexes
            $table->index(['car_model_id', 'is_active']);
            $table->index(['image_type', 'is_active']);
            $table->index(['is_main', 'is_active']);
            $table->index('sort_order');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_model_images');
    }
};
