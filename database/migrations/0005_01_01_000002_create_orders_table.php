<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Thông tin đơn hàng - không trùng lặp với users table
            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount_total', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('shipping_fee', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->text('note')->nullable();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
            $table->string('payment_status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'])->default('pending');
			$table->string('order_number', 64)->unique();
            
            // E-commerce specific fields
            $table->string('tracking_number')->nullable();
            $table->date('estimated_delivery')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->enum('source', ['website', 'mobile_app', 'phone', 'walk_in', 'social_media'])->default('website');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('referrer')->nullable();
            
            // Audit trail
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            
            // Showroom specific fields
            $table->foreignId('sales_person_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('showroom_id')->nullable()->constrained('showrooms')->onDelete('set null');
            $table->date('delivery_date')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('delivery_notes')->nullable();
            
            // Sử dụng foreign keys thay vì lưu trữ dữ liệu trùng lặp
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->onDelete('set null');
            
            // Trade-in information
            $table->boolean('has_trade_in')->default(false);
            $table->string('trade_in_brand')->nullable();
            $table->string('trade_in_model')->nullable();
			$table->unsignedSmallInteger('trade_in_year')->nullable();
            $table->decimal('trade_in_value', 15, 2)->nullable();
            $table->text('trade_in_condition')->nullable();
            
            // Financing information
            $table->foreignId('finance_option_id')->nullable()->constrained('finance_options')->onDelete('set null');
            $table->decimal('down_payment_amount', 15, 2)->nullable();
            $table->decimal('monthly_payment_amount', 15, 2)->nullable();
            $table->integer('loan_term_months')->nullable();
            $table->decimal('interest_rate', 5, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
			// Indexes tối ưu hóa
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'status']);
            $table->index('transaction_id');
			$table->index('order_number');
            $table->index(['source', 'status']);
            $table->index('tracking_number');
            $table->index(['sales_person_id', 'status']);
            $table->index(['showroom_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};