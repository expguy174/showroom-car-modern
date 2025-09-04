<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
			$table->string('session_id', 100)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');

            // Polymorphic item reference
            $table->morphs('item');

            // Optional color for car_variant items
            $table->foreignId('color_id')->nullable()->constrained('car_variant_colors')->onDelete('set null');

            // Options signature to distinguish lines with different selected features/options
            $table->string('options_signature', 100)->nullable();

            // Uniques cho user và guest tách biệt (bao gồm options_signature)
            $table->unique(['user_id', 'item_type', 'item_id', 'color_id', 'options_signature'], 'uniq_user_cart_line');
            $table->unique(['session_id', 'item_type', 'item_id', 'color_id', 'options_signature'], 'uniq_session_cart_line');

			$table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};