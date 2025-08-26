<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100)->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->morphs('item'); // This creates item_type and item_id for polymorphic relationships
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Add unique constraints for both user and session contexts
            $table->unique(['user_id', 'item_type', 'item_id'], 'wishlist_unique_user_item');
            $table->unique(['session_id', 'item_type', 'item_id'], 'wishlist_unique_session_item');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};