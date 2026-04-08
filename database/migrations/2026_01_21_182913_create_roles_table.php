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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique(); // e.g., 'retailer', 'wholesaler'
            $table->string('name'); // e.g., 'Retailer', 'Wholesaler'
            $table->text('description');
            $table->json('permissions'); // Array of permissions
            $table->boolean('is_system_role')->default(false); // Cannot be deleted
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};