<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advance_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('advance_percentage', 5, 2)->default(20.00); // e.g. 20%
            $table->boolean('is_mandatory')->default(true);  // mandatory for wholesale/import
            $table->text('description')->nullable();         // shown to customer
            $table->timestamps();
        });

        // Insert default row
        DB::table('advance_payment_settings')->insert([
            'advance_percentage' => 20.00,
            'is_mandatory'       => true,
            'description'        => 'An advance payment is required to confirm your wholesale/import order.',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('advance_payment_settings');
    }
};
