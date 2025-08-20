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
        Schema::create('dealerships', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên đại lý
            $table->string('code')->unique(); // Mã đại lý
            $table->text('description')->nullable();
            
            // Thông tin liên hệ
			$table->string('phone', 20);
            $table->string('email')->nullable();
            
            // Địa chỉ
            $table->string('address');
            $table->string('city');
            $table->string('country')->default('Vietnam');
            
            // Trạng thái
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['city', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealerships');
    }
};
