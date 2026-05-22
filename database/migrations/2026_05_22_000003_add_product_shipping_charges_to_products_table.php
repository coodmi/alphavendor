<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('shipping_charge_inside_dhaka', 10, 2)->nullable()->after('shipping_method_id');
            $table->decimal('shipping_charge_outside_dhaka', 10, 2)->nullable()->after('shipping_charge_inside_dhaka');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['shipping_charge_inside_dhaka', 'shipping_charge_outside_dhaka']);
        });
    }
};
