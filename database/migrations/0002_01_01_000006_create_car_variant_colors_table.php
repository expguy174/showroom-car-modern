<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_variant_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');
            $table->string('color_name'); // Tên màu
            $table->string('color_code')->nullable(); // Mã màu của hãng
            $table->string('hex_code')->nullable(); // Mã màu hex
            $table->string('rgb_code')->nullable(); // Mã màu RGB
            
            // Thông tin ảnh
			$table->string('image_path', 2048)->nullable(); // Ảnh màu xe
			$table->string('image_url', 2048)->nullable(); // Ảnh màu xe (URL)
			$table->string('swatch_image', 2048)->nullable(); // Ảnh mẫu màu nhỏ
			$table->string('exterior_image', 2048)->nullable(); // Ảnh ngoại thất
			$table->string('interior_image', 2048)->nullable(); // Ảnh nội thất
            
            // Phân loại màu
            $table->enum('color_type', ['solid', 'metallic', 'pearlescent', 'matte', 'special'])->default('solid');
            $table->enum('availability', ['standard', 'optional', 'limited', 'discontinued'])->default('standard');
            
            // Giá cả & tồn kho
            $table->decimal('price_adjustment', 10, 2)->default(0)->comment('Phụ phí màu (có thể âm nếu giảm giá)');
			$table->unsignedInteger('stock_quantity')->default(0);
            $table->boolean('is_free')->default(true)->comment('Màu có miễn phí không');
            
            // Thông tin bổ sung
            $table->text('description')->nullable(); // Mô tả màu
            $table->string('material')->nullable(); // Chất liệu sơn
            $table->boolean('is_popular')->default(false); // Màu phổ biến
            $table->boolean('is_active')->default(true); // Màu có hiển thị không
			$table->unsignedInteger('sort_order')->default(0); // Thứ tự sắp xếp
            
            $table->timestamps();
            
			// Indexes
            $table->index(['car_variant_id', 'is_active']);
            $table->index(['color_type', 'availability']);
            $table->index(['is_popular', 'is_active']);
            $table->index('sort_order');
			$table->index('created_at');
            
            // Constraints
            $table->unique(['car_variant_id', 'color_name'], 'unique_color_per_variant');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_variant_colors');
    }
};