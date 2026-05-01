<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change enum to include 'draft'
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active','inactive','out_of_stock','draft') DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE products MODIFY COLUMN status ENUM('active','inactive','out_of_stock') DEFAULT 'active'");
    }
};
