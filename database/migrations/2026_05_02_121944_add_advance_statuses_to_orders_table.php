<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: modify ENUM to add new values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending_advance_payment',
            'advance_paid',
            'order_confirmed',
            'pending',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
            'refunded',
            'exchange',
            'returned'
        ) DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
            'refunded'
        ) DEFAULT 'pending'");
    }
};
