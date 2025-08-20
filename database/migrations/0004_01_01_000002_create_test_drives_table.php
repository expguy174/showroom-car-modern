<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('test_drives', function (Blueprint $table) {
            $table->id();
            $table->string('test_drive_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');
            $table->foreignId('showroom_id')->nullable()->constrained('showrooms')->nullOnDelete();
            
            // Thông tin lịch hẹn - không trùng lặp với users table
            $table->date('preferred_date');
            $table->time('preferred_time');
            $table->integer('duration_minutes')->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->text('special_requirements')->nullable();
            $table->boolean('has_experience')->default(false);
            $table->string('experience_level')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('test_drive_type', ['individual', 'group', 'virtual'])->default('individual');
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('feedback')->nullable();
            $table->decimal('satisfaction_rating', 3, 2)->nullable();
            
            $table->timestamps();
            
            // Indexes tối ưu hóa
            $table->index(['user_id', 'status']);
            $table->index(['showroom_id', 'status']);
            $table->index(['car_variant_id', 'status']);
            $table->index(['status', 'preferred_date']);
            $table->index(['test_drive_type', 'status']);
            $table->index('test_drive_number');
            $table->index('preferred_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_drives');
    }
}; 