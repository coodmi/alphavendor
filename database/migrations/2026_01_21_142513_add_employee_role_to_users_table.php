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
        Schema::table('users', function (Blueprint $table) {
            // Modify the role enum to include 'employee'
            $table->enum('role', ['admin', 'employee', 'retailer', 'wholesaler', 'exporter', 'employer', 'user'])->default('user')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert back to previous enum values
            $table->enum('role', ['admin', 'retailer', 'wholesaler', 'exporter', 'employer', 'user'])->default('user')->change();
        });
    }
};