<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('contact_type', ['user', 'guest'])->default('guest');
            $table->foreignId('showroom_id')->nullable()->constrained('showrooms')->onDelete('set null');
            // Thông tin liên hệ (chỉ cần thiết khi contact_type = 'guest')
            // Khi contact_type = 'user', thông tin lấy từ users table qua user_id
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('topic', ['sales', 'service', 'test_drive', 'warranty', 'finance', 'other'])->nullable();
            $table->enum('status', ['new', 'in_progress', 'resolved', 'closed'])->default('new');
            $table->dateTime('handled_at')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('source')->nullable(); // website, phone, chat, etc.
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['status', 'topic']);
            $table->index(['showroom_id', 'status']);
            $table->index(['user_id']);
            $table->index(['handled_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
