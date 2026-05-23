<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('weight_kg', 10, 3)->nullable()->after('shipping_charge_outside_dhaka');
            $table->string('import_country', 100)->nullable()->after('weight_kg');
            $table->decimal('bangladesh_import_cost', 10, 2)->nullable()->after('import_country');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['weight_kg', 'import_country', 'bangladesh_import_cost']);
        });
    }
};
