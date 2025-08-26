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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('profile_type', ['customer', 'employee'])->default('customer');
            
            // Thông tin cá nhân cơ bản
            $table->string('name');
            $table->string('avatar_path', 2048)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            
            // Thông tin bằng lái xe
            $table->string('driver_license_number')->nullable();
            $table->date('driver_license_issue_date')->nullable();
            $table->date('driver_license_expiry_date')->nullable();
            $table->string('driver_license_class')->nullable();
            $table->integer('driving_experience_years')->nullable();
            
            // Sở thích mua xe
            $table->json('preferred_car_types')->nullable();
            $table->json('preferred_brands')->nullable();
            $table->json('preferred_colors')->nullable();
            $table->decimal('budget_min', 15, 2)->nullable();
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->string('purchase_purpose')->nullable();
            
            // Thông tin khách hàng
            $table->enum('customer_type', ['new', 'returning', 'vip', 'prospect'])->default('new');
            
            // Thông tin employee (chỉ cho employee)
            $table->decimal('employee_salary', 15, 2)->nullable(); // Lương
            $table->text('employee_skills')->nullable(); // Kỹ năng
            
            // Marketing preferences - Có thể lưu ở bảng riêng nếu cần
            
            // Trạng thái
            $table->boolean('is_vip')->default(false);
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'profile_type']);
            $table->index(['profile_type', 'is_vip']);
            $table->index(['customer_type']);
            $table->index(['is_vip']);
            $table->index('driver_license_number');
            $table->index('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
