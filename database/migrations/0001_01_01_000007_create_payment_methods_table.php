<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NOTE: Ensure this migration timestamp is earlier than orders create migration
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // cash, bank_transfer, vnpay, momo
            $table->string('provider')->nullable(); // VNPay, MoMo, Bank
            $table->enum('type', ['online', 'offline'])->default('offline');
            $table->boolean('is_active')->default(true);
            $table->decimal('fee_flat', 12, 2)->default(0); // phí cố định
            $table->decimal('fee_percent', 5, 2)->default(0); // phí theo %
            $table->json('config')->nullable();
			$table->unsignedInteger('sort_order')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'type']);
			$table->index(['sort_order']);
			$table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
