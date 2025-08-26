<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('car_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique()->nullable();
			$table->string('logo_path', 2048)->nullable();
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            
            // SEO và Marketing
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            
            // Thông tin bổ sung
			$table->unsignedSmallInteger('founded_year')->nullable()->comment('Năm thành lập');
			$table->string('website', 2048)->nullable();
			$table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            
            // Trạng thái và hiển thị
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
			$table->unsignedInteger('sort_order')->default(0);
            

            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes tối ưu hóa - chỉ giữ lại những indexes cần thiết
            $table->index(['is_active', 'is_featured', 'sort_order']);
            $table->index('sort_order');
            $table->index('country');
			$table->index('founded_year');
			$table->index('created_at');
        });
    }

    public function down(): void {
        Schema::dropIfExists('car_brands');
    }
};