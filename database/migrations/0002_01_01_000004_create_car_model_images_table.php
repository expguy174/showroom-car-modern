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
			$table->string('image_path', 2048)->nullable(); // Đường dẫn ảnh (nullable để hỗ trợ ảnh online)
			$table->string('image_url', 2048)->nullable(); // URL ảnh (nếu cần)
            $table->string('alt_text')->nullable(); // Alt text cho SEO
            $table->string('caption')->nullable(); // Chú thích ảnh
            $table->string('title')->nullable(); // Tiêu đề ảnh
            
            // Metadata ảnh
            $table->string('image_type')->default('gallery')->comment('gallery, thumbnail, hero, interior, exterior, detail');
			$table->unsignedInteger('width')->nullable(); // Chiều rộng ảnh
			$table->unsignedInteger('height')->nullable(); // Chiều cao ảnh
            $table->string('file_size')->nullable(); // Kích thước file
            $table->string('file_format')->nullable(); // Định dạng file (jpg, png, webp)
            
            // Sắp xếp và hiển thị
            $table->boolean('is_main')->default(false); // Ảnh chính
            $table->boolean('is_active')->default(true); // Ảnh có hiển thị không
			$table->unsignedInteger('sort_order')->default(0); // Thứ tự sắp xếp
            $table->boolean('is_featured')->default(false); // Ảnh nổi bật
            
            // Thông tin bổ sung
            $table->string('color_variant')->nullable(); // Màu sắc của xe trong ảnh
            $table->text('description')->nullable(); // Mô tả chi tiết ảnh
            
            $table->timestamps();
            
			// Indexes
            $table->index(['car_model_id', 'is_active']);
            $table->index(['image_type', 'is_active']);
            $table->index(['is_main', 'is_active']);
            $table->index('sort_order');
			$table->index('created_at');
            
            // Constraints
            // Không sử dụng unique constraint - sẽ kiểm soát logic trong code
            // Mỗi car model có thể có nhiều ảnh, nhưng chỉ một ảnh chính
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_model_images');
    }
};