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
            
            $table->decimal('total_price', 15, 2)->unsigned()->default(0);
            $table->decimal('subtotal', 15, 2)->unsigned()->default(0);
            $table->decimal('discount_total', 15, 2)->unsigned()->default(0);
            $table->decimal('tax_total', 15, 2)->unsigned()->default(0);
            $table->decimal('shipping_fee', 15, 2)->unsigned()->default(0);
            $table->decimal('payment_fee', 15, 2)->unsigned()->default(0);
            $table->decimal('grand_total', 15, 2)->unsigned()->default(0);
            $table->text('note')->nullable();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
            $table->foreignId('finance_option_id')->nullable()->constrained('finance_options')->onDelete('set null');
            $table->decimal('down_payment_amount', 15, 2)->unsigned()->nullable();
            $table->unsignedSmallInteger('tenure_months')->nullable();
            $table->decimal('monthly_payment_amount', 15, 2)->unsigned()->nullable();
            $table->string('payment_status')->default('pending');
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'delivered', 'cancelled'])->default('pending');
			$table->string('order_number', 64)->unique();

            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->onDelete('set null');
            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->onDelete('set null');

            $table->string('transaction_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->string('tracking_number')->nullable();
            $table->date('estimated_delivery')->nullable();
            
            // Additional order details
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->onDelete('set null')->comment('Khuyến mãi đã áp dụng');
            $table->string('shipping_method', 50)->default('standard')->comment('Phương thức vận chuyển: standard, express');
            $table->decimal('tax_rate', 5, 4)->default(0.1000)->comment('Thuế suất áp dụng (VD: 0.1000 = 10%)');

            $table->timestamps();
            $table->softDeletes();
            
			// Indexes chính
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'status']);
            $table->index('transaction_id');
            $table->index('tracking_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};