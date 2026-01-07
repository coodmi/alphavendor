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
        Schema::table('products', function (Blueprint $table) {
            // Add supplier_location_id column
            $table->unsignedBigInteger('supplier_location_id')->nullable()->after('supplier_location');
            $table->foreign('supplier_location_id')->references('id')->on('supplier_locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_location_id']);
            $table->dropColumn('supplier_location_id');
        });
    }
};
