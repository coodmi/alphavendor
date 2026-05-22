<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL requires ALTER COLUMN to change enum values
        DB::statement("ALTER TABLE withdrawal_methods MODIFY COLUMN type ENUM('bank', 'bkash', 'nagad', 'rocket', 'paypal', 'stripe', 'other') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE withdrawal_methods MODIFY COLUMN type ENUM('bank', 'paypal', 'stripe', 'other') NOT NULL");
    }
};
