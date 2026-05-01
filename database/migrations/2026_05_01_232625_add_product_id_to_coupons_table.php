<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            // null = applies to all products, set = specific product only
            $table->foreignId('product_id')->nullable()->after('description')
                  ->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->after('product_id')
                  ->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['category_id']);
            $table->dropColumn(['product_id', 'category_id']);
        });
    }
};
