<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_transaction_id')->constrained('payment_transactions')->onDelete('cascade');
            $table->decimal('amount', 15, 2)->unsigned();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'processing', 'refunded', 'failed'])->default('pending');
            $table->dateTime('processed_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

			$table->index(['payment_transaction_id', 'status']);
			$table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
