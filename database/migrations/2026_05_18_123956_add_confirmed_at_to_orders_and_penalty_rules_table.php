<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add confirmed_at to orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('orders', 'penalty_applied')) {
                $table->boolean('penalty_applied')->default(false)->after('confirmed_at');
            }
        });

        // 2. Create delivery_penalty_rules table
        Schema::create('delivery_penalty_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('days_threshold');   // e.g. 3 = after 3 days
            $table->decimal('penalty_amount', 10, 2);    // e.g. 50.00 taka
            $table->string('description')->nullable();   // e.g. "3 দিনের বেশি দেরি"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Add 'penalty' to transactions type ENUM
        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('sale','withdrawal','refund','commission','penalty') NOT NULL");

        // 4. Seed a default rule
        DB::table('delivery_penalty_rules')->insert([
            'days_threshold' => 3,
            'penalty_amount' => 50.00,
            'description'    => '৩ দিনের বেশি দেরিতে ডেলিভারি',
            'is_active'      => true,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['confirmed_at', 'penalty_applied']);
        });

        Schema::dropIfExists('delivery_penalty_rules');

        DB::statement("ALTER TABLE transactions MODIFY COLUMN type ENUM('sale','withdrawal','refund','commission') NOT NULL");
    }
};
