<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
			$table->string('password');
            $table->enum('role', ['user', 'admin', 'sales_person', 'technician', 'manager'])->default('user');
            
            // Verification
            $table->boolean('email_verified')->default(false);
            
            // Employee information (chỉ dùng khi role != 'user')
            $table->string('employee_id')->nullable()->unique(); // Mã nhân viên
            $table->string('department')->nullable(); // Phòng ban
            $table->string('position')->nullable(); // Chức vụ
            $table->date('hire_date')->nullable(); // Ngày vào làm
            
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
			$table->timestamps();
			$table->softDeletes();
            
            // Indexes
			            $table->index('email_verified_at');
            $table->index(['role', 'is_active']);
            $table->index(['department', 'is_active']);
            $table->index('created_at');
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::dropIfExists('users');
    }
};
