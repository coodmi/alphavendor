<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'employee_title')) {
                $table->string('employee_title')->nullable()->after('employee_role_id');
            }
            if (!Schema::hasColumn('users', 'nid_card')) {
                $table->string('nid_card')->nullable()->after('employee_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_title', 'nid_card']);
        });
    }
};
