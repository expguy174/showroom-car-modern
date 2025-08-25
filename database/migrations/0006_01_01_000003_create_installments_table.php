<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('payment_transaction_id')->nullable()->constrained('payment_transactions')->onDelete('set null');
			$table->unsignedSmallInteger('installment_number');
            $table->decimal('amount', 15, 2)->unsigned()->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('bank_name')->nullable();
            $table->decimal('interest_rate', 5, 2); // %/năm
			$table->unsignedSmallInteger('tenure_months');
            $table->decimal('down_payment_amount', 15, 2)->unsigned()->default(0);
            $table->decimal('monthly_payment_amount', 15, 2)->unsigned();
            $table->json('schedule')->nullable(); // lịch thanh toán
            $table->enum('status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'installment_number']);
            $table->index(['order_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
