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
            $table->foreignId('assigned_technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');

            // Thông tin xe - sử dụng foreign keys thay vì lưu trữ trùng lặp
            $table->string('vehicle_vin', 32)->nullable();
            $table->string('vehicle_registration', 32)->nullable();
            $table->unsignedSmallInteger('vehicle_year')->nullable();
            $table->unsignedInteger('current_mileage')->nullable();

            $table->string('appointment_number')->unique();
            $table->date('appointment_date');
            $table->string('appointment_time', 8);
            $table->unsignedSmallInteger('estimated_duration')->nullable();

            $table->enum('appointment_type', ['maintenance','repair','inspection','warranty_work','recall_service','emergency','other'])->default('maintenance');
            $table->text('requested_services');
            $table->text('service_description')->nullable();
            $table->text('customer_complaints')->nullable();
            $table->text('special_instructions')->nullable();

            $table->enum('status', ['scheduled','confirmed','in_progress','completed','cancelled','no_show','rescheduled'])->default('scheduled');
            $table->enum('priority', ['low','medium','high','urgent'])->default('medium');

            // Warranty information
            $table->boolean('is_warranty_work')->default(false);
            $table->string('warranty_number')->nullable();
            $table->date('warranty_expiry_date')->nullable();

            // Cost information
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->decimal('parts_cost', 12, 2)->nullable();
            $table->decimal('labor_cost', 12, 2)->nullable();
            $table->decimal('tax_amount', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->enum('payment_status', ['pending','paid','partial'])->default('pending');
            $table->enum('payment_method', ['cash','card','bank_transfer','installment'])->nullable();
            $table->date('payment_date')->nullable();

            // Service execution
            $table->string('actual_start_time', 8)->nullable();
            $table->string('actual_end_time', 8)->nullable();
            $table->mediumText('work_performed')->nullable();
            $table->mediumText('parts_used')->nullable();
            $table->mediumText('technician_notes')->nullable();
            $table->boolean('quality_check_passed')->default(false);
            $table->foreignId('quality_check_by')->nullable()->constrained('users')->nullOnDelete();
            $table->mediumText('quality_check_notes')->nullable();
            $table->boolean('vehicle_ready')->default(false);
            $table->string('vehicle_ready_time', 8)->nullable();
            $table->boolean('customer_notified')->default(false);
            $table->string('customer_notified_time', 8)->nullable();
            $table->decimal('customer_satisfaction', 3, 2)->nullable();
            $table->boolean('customer_recommend')->default(false);
            $table->mediumText('customer_feedback')->nullable();
            $table->text('notes')->nullable();
            $table->json('documents')->nullable();
            $table->string('tags')->nullable();

            $table->timestamps();

            // Indexes tối ưu hóa
            $table->index(['user_id', 'status']);
            $table->index(['showroom_id', 'status']);
            $table->index(['assigned_technician_id', 'status']);
            $table->index(['appointment_date', 'status']);
            $table->index(['status', 'priority']);
            $table->index('appointment_number');
            $table->index(['is_warranty_work', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_appointments');
    }
};


