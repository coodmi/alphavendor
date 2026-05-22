<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix status enum — add 'approved' and 'rejected'
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending','approved','completed','cancelled','rejected') DEFAULT 'pending'");

        // 2. Add withdrawal_id column for reliable linking
        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'withdrawal_id')) {
                $table->unsignedBigInteger('withdrawal_id')->nullable()->after('order_id');
                $table->foreign('withdrawal_id')->references('id')->on('withdrawals')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['withdrawal_id']);
            $table->dropColumn('withdrawal_id');
        });

        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending','completed','cancelled') DEFAULT 'pending'");
    }
};
