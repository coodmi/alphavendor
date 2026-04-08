<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('unpaid', 'paid', 'pending_verification', 'refunded') DEFAULT 'unpaid'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid'");
    }
};
