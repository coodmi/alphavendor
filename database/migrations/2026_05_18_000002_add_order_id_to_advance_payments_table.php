<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advance_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('advance_payments', 'order_id')) {
                $table->foreignId('order_id')
                      ->nullable()
                      ->after('vendor_id')
                      ->constrained('orders')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('advance_payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
};
