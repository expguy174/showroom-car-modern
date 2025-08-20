<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('finance_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('bank_name');
            $table->text('description')->nullable();
            $table->decimal('interest_rate', 5, 2); // Lãi suất %
            $table->decimal('processing_fee', 12, 2)->default(0); // Phí xử lý hồ sơ
			$table->unsignedSmallInteger('min_tenure'); // Thời hạn tối thiểu (tháng)
			$table->unsignedSmallInteger('max_tenure'); // Thời hạn tối đa (tháng)
            $table->decimal('min_down_payment', 5, 2); // Trả trước tối thiểu %
            $table->decimal('min_loan_amount', 15, 2); // Số tiền vay tối thiểu
            $table->decimal('max_loan_amount', 15, 2); // Số tiền vay tối đa
            $table->text('requirements')->nullable(); // Yêu cầu hồ sơ
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index(['is_active']);
            $table->index(['bank_name', 'is_active']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_options');
    }
}; 