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
        // First, we need to drop the enum column and recreate it as string
        // This is necessary because MySQL doesn't allow direct conversion from ENUM to VARCHAR
        
        Schema::table('role_applications', function (Blueprint $table) {
            $table->dropColumn('business_type');
        });
        
        Schema::table('role_applications', function (Blueprint $table) {
            $table->string('business_type')->nullable()->after('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_applications', function (Blueprint $table) {
            $table->dropColumn('business_type');
        });
        
        Schema::table('role_applications', function (Blueprint $table) {
            $table->enum('business_type', ['sole_proprietorship', 'partnership', 'llc', 'corporation', 'other'])->nullable()->after('tax_id');
        });
    }
};
