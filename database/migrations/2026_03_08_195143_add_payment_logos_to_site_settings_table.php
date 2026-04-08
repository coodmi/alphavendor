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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('payment_logo_1')->nullable()->after('linkedin_url');
            $table->string('payment_logo_2')->nullable()->after('payment_logo_1');
            $table->string('payment_logo_3')->nullable()->after('payment_logo_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['payment_logo_1', 'payment_logo_2', 'payment_logo_3']);
        });
    }
};
