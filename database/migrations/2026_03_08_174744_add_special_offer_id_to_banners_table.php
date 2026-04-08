<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->foreignId('special_offer_id')->nullable()->after('link')->constrained('special_offers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropForeign(['special_offer_id']);
            $table->dropColumn('special_offer_id');
        });
    }
};
