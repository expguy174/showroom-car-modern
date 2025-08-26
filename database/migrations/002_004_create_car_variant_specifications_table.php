<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_variant_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');
            
            // Thông số kỹ thuật (tất cả đều dùng spec_name + spec_value)
            $table->string('category')->nullable(); // engine, transmission, dimensions, performance, safety, etc.
            $table->string('spec_name'); // Tên thông số (ví dụ: "Loại nhiên liệu", "Công suất", "Chiều dài")
            $table->string('spec_value')->nullable(); // Giá trị (ví dụ: "Petrol", "150hp", "4500")
            $table->string('unit')->nullable(); // Đơn vị (ví dụ: "hp", "mm", "kg")
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->string('spec_code')->nullable(); // Mã thông số của hãng
            
            // Trạng thái và hiển thị
            $table->boolean('is_important')->default(false); // Thông số quan trọng
            $table->boolean('is_highlighted')->default(false); // Thông số nổi bật
            $table->unsignedInteger('sort_order')->default(0); // Thứ tự sắp xếp
            
            $table->timestamps();

            $table->unique(['car_variant_id', 'spec_name'], 'uniq_variant_spec_per_variant');
            $table->index(['car_variant_id', 'category']);
            $table->index(['is_important', 'is_highlighted']);
            $table->index('sort_order');
            $table->index('spec_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_variant_specifications');
    }
};
