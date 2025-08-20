<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Polymorphic item reference: item_type + item_id (car_variant, accessory, service)
            $table->string('item_type');
            $table->unsignedBigInteger('item_id');
            $table->index(['item_type', 'item_id'], 'oi_item_type_item_id_idx');

            // Optional color for car_variant items
            $table->foreignId('color_id')->nullable()->constrained('car_variant_colors')->onDelete('set null');

            // Snapshot fields to preserve state at purchase time
            $table->string('item_name');
            $table->string('item_sku')->nullable();
            $table->json('item_metadata')->nullable();

			$table->unsignedInteger('quantity');
            $table->decimal('price', 15, 2); // unit price at time of order
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);

			$table->timestamps();
			
			// Indexes
			$table->index(['order_id']);
			$table->index(['item_type', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};