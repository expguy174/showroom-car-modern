<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_variant_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');
            // Optional color-level association when an image is specific to a variant color
            $table->foreignId('car_variant_color_id')->nullable()->constrained('car_variant_colors')->nullOnDelete();
            $table->string('image_url', 2048); // URL ảnh
            $table->string('alt_text')->nullable(); // Alt text cho SEO
            $table->string('title')->nullable(); // Tiêu đề/chú thích ảnh

            // Loại ảnh
            $table->string('image_type')->default('gallery')->comment('gallery, interior, exterior');

            // Sắp xếp và hiển thị
            $table->boolean('is_main')->default(false); // Ảnh chính
            $table->boolean('is_active')->default(true); // Ảnh có hiển thị không
            $table->unsignedInteger('sort_order')->default(0); // Thứ tự sắp xếp

            // Thông tin bổ sung
            $table->string('angle')->nullable(); // Góc chụp (front, side, rear, interior)
            $table->text('description')->nullable(); // Mô tả chi tiết ảnh

            $table->timestamps();

            // Indexes
            $table->index(['car_variant_id', 'is_active']);
            $table->index(['car_variant_id', 'car_variant_color_id']);
            $table->index(['image_type', 'is_active']);
            $table->index(['is_main', 'is_active']);
            $table->index('sort_order');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_variant_images');
    }
};
