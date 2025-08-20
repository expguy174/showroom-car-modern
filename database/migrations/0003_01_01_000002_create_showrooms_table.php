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
        Schema::create('showrooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealership_id')->constrained('dealerships')->onDelete('cascade');
            $table->string('name'); // Tên showroom
            $table->string('code')->unique(); // Mã showroom
            $table->text('description')->nullable();
            
            // Thông tin liên hệ
			$table->string('phone', 20);
            $table->string('email')->nullable();
            
            // Địa chỉ
			$table->string('address', 255);
            $table->string('city');
            
            // Trạng thái
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['dealership_id', 'is_active']);
            $table->index(['city', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('showrooms');
    }
};
