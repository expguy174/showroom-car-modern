<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed_amount', 'free_shipping'])->default('percentage');
            $table->decimal('discount_value', 10, 2); // Giá trị giảm giá
            $table->decimal('min_order_amount', 15, 2)->nullable(); // Giá trị đơn hàng tối thiểu
            $table->unsignedInteger('usage_limit')->nullable(); // Giới hạn sử dụng
            $table->unsignedInteger('usage_count')->default(0); // Số lần đã sử dụng
            
            // Thời gian
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active', 'start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
}; 