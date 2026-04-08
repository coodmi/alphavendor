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
        Schema::create('verification_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'nid_front',
                'nid_back',
                'trade_license',
                'personal_photo',
                'shop_profile'
            ]);
            $table->string('file_path');
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable(); // in bytes
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            // Index for faster queries
            $table->index(['user_id', 'document_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_documents');
    }
};
