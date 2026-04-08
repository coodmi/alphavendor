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
        Schema::create('advance_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->integer('advance_percentage'); // 25, 50, 75
            $table->decimal('advance_amount', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->string('payment_method'); // bkash, nagad, rocket, bank_transfer
            $table->string('contact_number');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid', 'completed', 'cancelled'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_payments');
    }
};
