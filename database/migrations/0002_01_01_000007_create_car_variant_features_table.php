<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_variant_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');
            
            // Thông tin tính năng
            $table->string('feature_name'); // Tên tính năng
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->string('feature_code')->nullable(); // Mã tính năng của hãng
            
            // Phân loại tính năng
            $table->enum('category', ['safety', 'comfort', 'technology', 'performance', 'exterior', 'interior', 'entertainment', 'convenience'])->default('comfort');
            $table->enum('availability', ['standard', 'optional', 'package', 'dealer_installed'])->default('standard');
            $table->enum('importance', ['essential', 'important', 'nice_to_have', 'luxury'])->default('important');
            
            // Giá cả
            $table->decimal('price', 12, 2)->default(0)->comment('Giá tính năng');
            $table->decimal('package_price', 12, 2)->nullable()->comment('Giá gói tính năng (nếu thuộc package)');
            $table->boolean('is_included')->default(true)->comment('Tính năng có sẵn trong giá xe không');
            
            // Thông tin bổ sung
            $table->string('icon_path')->nullable(); // Icon tính năng
            $table->boolean('is_active')->default(true); // Tính năng có hiển thị không
            $table->boolean('is_featured')->default(false); // Tính năng nổi bật
			$table->unsignedInteger('sort_order')->default(0); // Thứ tự sắp xếp
            
            $table->timestamps();
            
			// Indexes
            $table->index(['car_variant_id', 'is_active']);
            $table->index(['category', 'availability']);
            $table->index(['importance', 'is_active']);
            $table->index('sort_order');
			$table->index('created_at');
            
            // Constraints
            $table->unique(['car_variant_id', 'feature_name'], 'unique_feature_per_variant');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_variant_features');
    }
};
