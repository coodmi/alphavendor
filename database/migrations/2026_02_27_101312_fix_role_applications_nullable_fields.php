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
        Schema::table('role_applications', function (Blueprint $table) {
            // Make fields nullable that might not be filled during registration
            $table->string('business_name')->nullable()->change();
            $table->string('business_phone')->nullable()->change();
            $table->string('business_email')->nullable()->change();
            $table->text('business_address')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('postal_code')->nullable()->change();
            $table->string('country')->nullable()->change();
            $table->integer('years_in_business')->nullable()->change();
            $table->text('business_description')->nullable()->change();
            // business_type is now handled by a separate migration
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_applications', function (Blueprint $table) {
            // Revert back to non-nullable (if needed)
            $table->string('business_name')->nullable(false)->change();
            $table->string('business_phone')->nullable(false)->change();
            $table->string('business_email')->nullable(false)->change();
            $table->text('business_address')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('state')->nullable(false)->change();
            $table->string('postal_code')->nullable(false)->change();
            $table->string('country')->nullable(false)->change();
            $table->integer('years_in_business')->nullable(false)->change();
            $table->text('business_description')->nullable(false)->change();
            // business_type is now handled by a separate migration
        });
    }
};
