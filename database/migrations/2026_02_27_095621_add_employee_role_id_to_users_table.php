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
            // Add employee_role_id to link to custom employee roles
            if (!Schema::hasColumn('users', 'employee_role_id')) {
                $table->foreignId('employee_role_id')->nullable()->after('role')->constrained('employee_roles')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_role_id']);
            $table->dropColumn('employee_role_id');
        });
    }
};
