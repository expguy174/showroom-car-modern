<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // e.g. status_changed, note_added, payment_updated
            $table->json('details')->nullable(); // {from_status:..., to_status:...}
            $table->text('message')->nullable();
			$table->string('ip_address', 45)->nullable();
			$table->string('user_agent', 512)->nullable();
            $table->timestamps();

            $table->index(['order_id', 'created_at']);
            $table->index(['action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_logs');
    }
};


