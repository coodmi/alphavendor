<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawal_methods', function (Blueprint $table) {
            $table->string('rocket_number')->nullable()->after('nagad_number');
            // personal = manual transfer, merchant = auto-pay (future)
            $table->enum('account_type', ['personal', 'merchant'])->default('personal')->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('withdrawal_methods', function (Blueprint $table) {
            $table->dropColumn(['rocket_number', 'account_type']);
        });
    }
};
