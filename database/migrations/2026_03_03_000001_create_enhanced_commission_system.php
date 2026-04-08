<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Violation Rules Table
        Schema::create('violation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_code')->unique();
            $table->string('rule_name_en');
            $table->string('rule_name_bn')->nullable();
            $table->text('description')->nullable();
            $table->enum('penalty_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seller Violations Table
        Schema::create('seller_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('rule_id')->constrained('violation_rules')->onDelete('cascade');
            $table->date('violation_date');
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'reviewed', 'applied', 'waived'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->index(['seller_id', 'status']);
            $table->index('order_id');
        });

        // Violation Notifications Table
        Schema::create('violation_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('violation_id')->constrained('seller_violations')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['recipient_id', 'is_read']);
        });


        // Commission Invoices Table
        Schema::create('commission_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->date('invoice_date');
            $table->decimal('order_amount', 10, 2)->default(0);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('category_commission_rate', 5, 2)->default(0);
            $table->decimal('category_commission_amount', 10, 2)->default(0);
            $table->decimal('cod_commission_rate', 5, 2)->default(0);
            $table->decimal('cod_commission_amount', 10, 2)->default(0);
            $table->decimal('total_commission', 10, 2)->default(0);
            $table->decimal('penalty_amount', 10, 2)->default(0);
            $table->decimal('total_deduction', 10, 2)->default(0);
            $table->decimal('net_vendor_earning', 10, 2)->default(0);
            $table->enum('status', ['draft', 'finalized', 'paid'])->default('finalized');
            $table->string('pdf_path')->nullable();
            $table->timestamps();
            
            $table->index(['seller_id', 'status']);
            $table->index('order_id');
        });

        // Commission Invoice Items Table
        Schema::create('commission_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('commission_invoices')->onDelete('cascade');
            $table->enum('item_type', ['category_commission', 'cod_commission', 'penalty']);
            $table->string('description');
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_invoice_items');
        Schema::dropIfExists('commission_invoices');
        Schema::dropIfExists('violation_notifications');
        Schema::dropIfExists('seller_violations');
        Schema::dropIfExists('violation_rules');
    }
};
