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
            $table->decimal('price', 12, 2); // Giá dịch vụ
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->boolean('is_featured')->default(false); // Dịch vụ nổi bật
            $table->integer('sort_order')->default(0); // Thứ tự sắp xếp
            
            // Tương thích với xe
            $table->json('compatible_car_brands')->nullable(); // Thương hiệu xe tương thích
            $table->json('compatible_car_models')->nullable(); // Dòng xe tương thích
            $table->json('compatible_car_years')->nullable(); // Năm sản xuất tương thích
            
            // Yêu cầu và điều kiện
            $table->text('requirements')->nullable(); // Yêu cầu thực hiện
            $table->integer('warranty_months')->default(0); // Bảo hành (tháng)
            $table->boolean('service_center_required')->default(false); // Yêu cầu trung tâm dịch vụ
            
            // Dịch vụ bao gồm
            $table->boolean('parts_included')->default(false); // Bao gồm phụ tùng
            $table->boolean('labor_included')->default(true); // Bao gồm nhân công
            $table->boolean('oil_change_included')->default(false); // Bao gồm thay dầu
            $table->boolean('filter_change_included')->default(false); // Bao gồm thay lọc
            $table->boolean('inspection_included')->default(false); // Bao gồm kiểm tra
            
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();
            
            // Indexes
            $table->index(['category', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index('sort_order');
            $table->index('code');
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
