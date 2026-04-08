<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advance_payments', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('transaction_id');
            $table->string('bank_account_holder')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_account_holder');
        });
    }

    public function down(): void
    {
        Schema::table('advance_payments', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'bank_account_holder', 'bank_account_number']);
        });
    }
};
