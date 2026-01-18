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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->tinyInteger('rating')->unsigned(); // 1-5 stars
            $table->string('title')->nullable();
            $table->text('comment');
            $table->enum('status', ['pending', 'approved', 'rejected', 'reported'])->default('pending');
            $table->boolean('is_verified_purchase')->default(false);
            $table->integer('helpful_votes')->default(0);
            $table->integer('reported_count')->default(0);
            $table->text('admin_response')->nullable();
            $table->timestamp('admin_responded_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['product_id', 'status']);
            $table->index(['user_id', 'product_id']); // Prevent duplicate reviews
            $table->index('rating');
            $table->index('status');
            $table->index('created_at');

            // Unique constraint to prevent multiple reviews per user per product
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
