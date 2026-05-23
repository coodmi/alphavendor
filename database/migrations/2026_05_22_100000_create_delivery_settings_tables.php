<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('extra_per_kg_charge', 10, 2)->default(30);
            $table->timestamps();
        });

        DB::table('delivery_settings')->insert([
            'extra_per_kg_charge' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::create('district_delivery_charges', function (Blueprint $table) {
            $table->id();
            $table->string('district')->unique();
            $table->string('division')->nullable();
            $table->decimal('base_charge', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('district_delivery_charges');
        Schema::dropIfExists('delivery_settings');
    }
};
