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
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Thông tin cá nhân chuyên biệt
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('nationality')->default('Vietnamese');
            $table->string('id_card_number')->nullable();
            $table->string('passport_number')->nullable();
            
            // Thông tin liên hệ khẩn cấp
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Thông tin nghề nghiệp
            $table->string('occupation')->nullable();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('work_email')->nullable();
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->string('income_source')->nullable();
            
            // Thông tin tài chính
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->integer('credit_score')->nullable();
            $table->boolean('has_existing_loan')->default(false);
            $table->text('existing_loan_details')->nullable();
            
            // Thông tin bằng lái xe
            $table->string('driver_license_number')->nullable();
            $table->date('driver_license_issue_date')->nullable();
            $table->date('driver_license_expiry_date')->nullable();
            $table->string('driver_license_class')->nullable();
            $table->integer('driving_experience_years')->nullable();
            $table->text('driving_history')->nullable();
            
            // Sở thích mua xe
            $table->json('preferred_car_types')->nullable();
            $table->json('preferred_brands')->nullable();
            $table->json('preferred_colors')->nullable();
            $table->decimal('budget_min', 15, 2)->nullable();
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->string('purchase_purpose')->nullable();
            $table->text('special_requirements')->nullable();
            
            // Thông tin gia đình
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            $table->integer('family_size')->nullable();
            $table->boolean('has_children')->default(false);
            $table->integer('children_count')->nullable();
            $table->json('family_vehicles')->nullable();
            
            // Thông tin khách hàng
            $table->enum('customer_type', ['new', 'returning', 'vip', 'prospect'])->default('new');
            $table->string('lead_source')->nullable();
            $table->string('referred_by')->nullable();
            $table->date('first_contact_date')->nullable();
            $table->date('last_contact_date')->nullable();
            $table->integer('total_visits')->nullable()->default(0);
            $table->integer('total_test_drives')->nullable()->default(0);
            
            // Thông tin bán hàng
            $table->foreignId('assigned_sales_person_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('preferred_showroom_id')->nullable()->constrained('showrooms')->onDelete('set null');
            $table->text('sales_notes')->nullable();
            $table->enum('sales_stage', ['prospect', 'qualified', 'proposal', 'negotiation', 'closed'])->nullable();
            
            // Marketing preferences
            $table->boolean('consent_to_marketing')->default(false);
            $table->json('marketing_preferences')->nullable();
            $table->boolean('consent_to_sms')->default(false);
            $table->boolean('consent_to_email')->default(false);
            $table->boolean('consent_to_call')->default(false);
            
            // Trạng thái
            $table->boolean('is_active')->default(true);
            $table->boolean('is_vip')->default(false);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->decimal('satisfaction_score', 2, 1)->nullable();
            $table->text('feedback')->nullable();
            
            $table->softDeletes();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['assigned_sales_person_id', 'is_active']);
            $table->index(['customer_type', 'is_active']);
            $table->index(['is_vip', 'is_active']);
            $table->index('driver_license_number');
            $table->index('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_profiles');
    }
};
