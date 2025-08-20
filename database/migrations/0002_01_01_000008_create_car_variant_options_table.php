<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_variant_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');
            
            // Thông tin tùy chọn
            $table->string('option_name'); // Tên tùy chọn
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->string('option_code')->nullable(); // Mã tùy chọn của hãng
            $table->string('option_value')->nullable(); // Giá trị tùy chọn (nếu có)
            
            // Phân loại tùy chọn
            $table->enum('category', ['exterior', 'interior', 'wheels', 'audio', 'navigation', 'safety', 'comfort', 'performance', 'appearance'])->default('exterior');
            $table->enum('availability', ['standard', 'optional', 'package', 'dealer_installed', 'factory_installed'])->default('optional');
            $table->enum('type', ['single', 'multiple_choice', 'package', 'standalone'])->default('standalone');
            
            // Giá cả
            $table->decimal('price', 12, 2)->default(0)->comment('Giá tùy chọn');
            $table->decimal('package_price', 12, 2)->nullable()->comment('Giá gói tùy chọn (nếu thuộc package)');
            $table->boolean('is_included')->default(false)->comment('Tùy chọn có sẵn trong giá xe không');
            
            // Thông tin bổ sung
			$table->string('image_path', 2048)->nullable(); // Ảnh tùy chọn
			$table->string('icon_path', 2048)->nullable(); // Icon tùy chọn
            $table->boolean('is_active')->default(true); // Tùy chọn có hiển thị không
            $table->boolean('is_popular')->default(false); // Tùy chọn phổ biến
            $table->boolean('is_recommended')->default(false); // Tùy chọn được khuyến nghị
			$table->unsignedInteger('sort_order')->default(0); // Thứ tự sắp xếp
            
            // Thông tin kỹ thuật
            $table->text('specifications')->nullable(); // Thông số kỹ thuật
            $table->string('compatibility_notes')->nullable(); // Ghi chú tương thích
            
            $table->timestamps();
            
			// Indexes
            $table->index(['car_variant_id', 'is_active']);
            $table->index(['category', 'availability']);
            $table->index(['type', 'is_active']);
            $table->index(['is_popular', 'is_recommended']);
            $table->index('sort_order');
			$table->index('created_at');
            
            // Constraints
            $table->unique(['car_variant_id', 'option_name'], 'unique_option_per_variant');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_variant_options');
    }
};
