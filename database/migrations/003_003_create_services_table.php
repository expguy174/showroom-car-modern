<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên dịch vụ
            $table->string('code')->unique(); // Mã dịch vụ
            $table->text('description')->nullable(); // Mô tả dịch vụ
            $table->enum('category', ['maintenance', 'repair', 'diagnostic', 'cosmetic', 'emergency'])->default('maintenance'); // Loại dịch vụ
            $table->integer('duration_minutes')->nullable(); // Thời gian thực hiện (phút)
            $table->decimal('price', 15, 2)->unsigned(); // Giá dịch vụ
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->boolean('is_featured')->default(false); // Dịch vụ nổi bật
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            
            // Yêu cầu và điều kiện
            $table->text('requirements')->nullable(); // Yêu cầu thực hiện
            
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
            
            // Indexes
            $table->index(['category', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
