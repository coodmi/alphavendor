<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('zone'); // Dhaka City, Dhaka Suburbs, Major Cities, Other Areas
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2);
            $table->integer('estimated_days_min')->default(1);
            $table->integer('estimated_days_max')->default(7);
            $table->decimal('free_shipping_threshold', 10, 2)->nullable(); // Free shipping if order exceeds this amount
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
