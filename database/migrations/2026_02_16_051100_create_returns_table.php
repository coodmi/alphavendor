<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', ['return', 'refund', 'exchange'])->default('return');
            $table->enum('reason', [
                'defective',
                'wrong_item',
                'not_as_described',
                'damaged',
                'size_issue',
                'quality_issue',
                'changed_mind',
                'other'
            ]);
            $table->text('reason_details')->nullable();
            
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'processing',
                'shipped_back',
                'received',
                'refunded',
                'completed',
                'cancelled'
            ])->default('pending');
            
            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('vendor_notes')->nullable();
            
            // Images for proof
            $table->json('images')->nullable();
            
            // Refund details
            $table->enum('refund_method', ['original_payment', 'wallet', 'bank_transfer'])->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->timestamp('refund_processed_at')->nullable();
            $table->string('refund_transaction_id')->nullable();
            
            // Exchange details
            $table->foreignId('exchange_product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->string('exchange_product_variant')->nullable();
            
            // Tracking
            $table->string('return_tracking_number')->nullable();
            $table->string('return_courier')->nullable();
            
            // Approval/Rejection
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            $table->index(['order_id', 'user_id']);
            $table->index(['vendor_id', 'status']);
            $table->index('return_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
