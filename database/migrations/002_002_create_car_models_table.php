<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_brand_id')->constrained('car_brands')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            
            // Phân loại xe
            $table->enum('body_type', ['sedan', 'suv', 'hatchback', 'wagon', 'coupe', 'convertible', 'pickup', 'van', 'minivan'])->nullable();
            $table->enum('segment', ['economy', 'compact', 'mid-size', 'full-size', 'luxury', 'premium', 'sports', 'exotic'])->nullable();
            $table->enum('fuel_type', ['gasoline', 'diesel', 'hybrid', 'electric', 'plug-in_hybrid', 'hydrogen'])->nullable();
            
            // Thông tin sản xuất
			$table->unsignedSmallInteger('production_start_year')->nullable();
			$table->unsignedSmallInteger('production_end_year')->nullable();
            $table->string('generation')->nullable()->comment('Thế hệ xe (ví dụ: Gen 1, Gen 2)');
            
            // SEO và Marketing
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('keywords')->nullable();
            
            // Trạng thái và hiển thị
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_discontinued')->default(false);
			$table->unsignedInteger('sort_order')->default(0);
            

            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes tối ưu hóa - chỉ giữ lại những indexes cần thiết
            $table->unique(['car_brand_id', 'name'], 'uniq_brand_model_name');
            $table->index(['car_brand_id', 'is_active', 'sort_order']);
            $table->index(['body_type', 'segment']);
            $table->index(['is_featured', 'is_new', 'is_active']);
            $table->index('sort_order');
            $table->index('production_start_year');
			$table->index('production_end_year');
			$table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_models');
    }
};