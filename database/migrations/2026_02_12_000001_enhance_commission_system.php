<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old commission_settings table
        Schema::dropIfExists('commission_settings');
        
        // Create new commission_settings table with seller type support
        Schema::create('commission_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->enum('seller_type', ['retailer', 'wholesaler', 'importer']);
            $table->decimal('commission_rate', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Unique constraint: one rate per category-seller_type combination
            $table->unique(['category_id', 'seller_type']);
        });
        
        // Create COD commission settings table
        Schema::create('cod_commission_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('commission_rate', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Add COD commission fields to orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('cod_commission_amount', 10, 2)->default(0)->after('commission_amount');
            $table->decimal('cod_commission_rate', 5, 2)->default(0)->after('commission_rate');
            $table->decimal('delivery_charge', 10, 2)->default(0)->after('total');
        });
        
        // Add COD commission fields to order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('cod_commission_amount', 10, 2)->default(0)->after('commission_amount');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('cod_commission_amount');
        });
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cod_commission_amount', 'cod_commission_rate', 'delivery_charge']);
        });
        
        Schema::dropIfExists('cod_commission_settings');
        Schema::dropIfExists('commission_settings');
    }
};
