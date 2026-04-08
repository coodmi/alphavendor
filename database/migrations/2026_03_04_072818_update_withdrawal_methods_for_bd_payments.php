<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawal_methods', function (Blueprint $table) {
            // Add new columns for bKash and Nagad
            $table->string('bkash_number', 20)->nullable()->after('swift_code');
            $table->string('nagad_number', 20)->nullable()->after('bkash_number');
            $table->string('branch_name')->nullable()->after('bank_name');
            
            // Drop paypal_email column if it exists
            if (Schema::hasColumn('withdrawal_methods', 'paypal_email')) {
                $table->dropColumn('paypal_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('withdrawal_methods', function (Blueprint $table) {
            $table->dropColumn(['bkash_number', 'nagad_number', 'branch_name']);
            $table->string('paypal_email')->nullable();
        });
    }
};
