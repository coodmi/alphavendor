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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('paperfly_tracking_number')->nullable()->after('status');
            $table->string('paperfly_merchant_order_ref')->nullable()->after('paperfly_tracking_number');
            $table->string('delivery_status')->default('pending')->after('paperfly_merchant_order_ref');
            $table->text('tracking_history')->nullable()->after('delivery_status');
            $table->timestamp('picked_at')->nullable()->after('tracking_history');
            $table->timestamp('in_transit_at')->nullable()->after('picked_at');
            $table->timestamp('delivered_at')->nullable()->after('in_transit_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'paperfly_tracking_number',
                'paperfly_merchant_order_ref',
                'delivery_status',
                'tracking_history',
                'picked_at',
                'in_transit_at',
                'delivered_at'
            ]);
        });
    }
};
