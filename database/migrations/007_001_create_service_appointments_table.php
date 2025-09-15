<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('showroom_id')->constrained('showrooms')->onDelete('cascade');
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');

            // Thông tin xe tối thiểu
            $table->string('vehicle_registration', 32)->nullable();
            $table->unsignedInteger('current_mileage')->nullable();

            // Lịch hẹn
            $table->string('appointment_number')->unique();
            $table->date('appointment_date');
            $table->time('appointment_time');

            $table->enum('appointment_type', ['maintenance','repair','inspection','warranty_work','recall_service','emergency','other'])->default('maintenance');
            $table->text('requested_services');
            $table->text('service_description')->nullable();

            $table->enum('status', ['scheduled','confirmed','in_progress','completed','cancelled','no_show','rescheduled'])->default('scheduled');
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');

            // Bảo hành (tối giản cờ)
            $table->boolean('is_warranty_work')->default(false);

            // Chi phí ước tính
            $table->decimal('estimated_cost', 15, 2)->unsigned()->nullable();

            // Đánh giá sau dịch vụ (tùy chọn)
            $table->tinyInteger('satisfaction_rating')->unsigned()->nullable(); // 1-5 sao
            $table->text('feedback')->nullable();

            $table->timestamps();

            // Indexes tối ưu hóa (đơn giản hoá)
            $table->index(['user_id', 'appointment_date']);
            $table->index(['showroom_id', 'appointment_date']);
            $table->index(['status', 'appointment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_appointments');
    }
};


