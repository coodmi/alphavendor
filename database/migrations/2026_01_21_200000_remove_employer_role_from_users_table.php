<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing employer users to user role
        DB::table('users')->where('role', 'employer')->update(['role' => 'user']);
        
        // Then modify the role enum to remove 'employer'
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'employee', 'retailer', 'wholesaler', 'exporter', 'user'])->default('user')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add employer back to the enum
            $table->enum('role', ['admin', 'employee', 'retailer', 'wholesaler', 'exporter', 'employer', 'user'])->default('user')->change();
        });
    }
};