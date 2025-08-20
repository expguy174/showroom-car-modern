<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_variant_id')->constrained('car_variants')->onDelete('cascade');
            $table->string('category')->nullable(); // engine, transmission, dimensions, performance, safety, etc.
            $table->string('spec_name');
            $table->string('spec_value')->nullable();
            $table->string('unit')->nullable(); // km/h, mm, kg, etc.
            $table->text('description')->nullable();
            $table->string('spec_code')->nullable();
            $table->boolean('is_important')->default(false);
            $table->boolean('is_highlighted')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['car_variant_id', 'spec_name'], 'uniq_spec_per_variant');
            $table->index(['car_variant_id', 'category']);
            $table->index(['is_important', 'is_highlighted']);
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_specifications');
    }
};
