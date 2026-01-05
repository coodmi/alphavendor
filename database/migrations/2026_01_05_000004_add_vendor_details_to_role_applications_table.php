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
        Schema::table('role_applications', function (Blueprint $table) {
            // Business Information
            $table->string('business_name')->after('reason');
            $table->string('business_registration_number')->nullable()->after('business_name');
            $table->string('tax_id')->nullable()->after('business_registration_number');
            $table->enum('business_type', ['sole_proprietorship', 'partnership', 'llc', 'corporation', 'other'])->after('tax_id');
            
            // Contact Information
            $table->string('business_phone')->after('business_type');
            $table->string('business_email')->after('business_phone');
            $table->string('website')->nullable()->after('business_email');
            
            // Address Information
            $table->text('business_address')->after('website');
            $table->string('city')->after('business_address');
            $table->string('state')->after('city');
            $table->string('postal_code')->after('state');
            $table->string('country')->after('postal_code');
            
            // Business Details
            $table->integer('years_in_business')->after('country');
            $table->text('business_description')->after('years_in_business');
            $table->text('product_categories')->nullable()->after('business_description');
            $table->decimal('estimated_monthly_sales', 12, 2)->nullable()->after('product_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_applications', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'business_registration_number',
                'tax_id',
                'business_type',
                'business_phone',
                'business_email',
                'website',
                'business_address',
                'city',
                'state',
                'postal_code',
                'country',
                'years_in_business',
                'business_description',
                'product_categories',
                'estimated_monthly_sales',
            ]);
        });
    }
};
