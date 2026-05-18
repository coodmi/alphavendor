<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->string('type', 20)->default('warning'); // info | warning | error | success
            // recipient_type: 'all' | 'role' | 'specific'
            $table->string('recipient_type', 20)->default('specific');
            // recipient_role: retailer | wholesaler | exporter | importer | all_sellers (null if specific)
            $table->string('recipient_role', 30)->nullable();
            $table->timestamps();
        });

        // Pivot: which users received which reminder
        Schema::create('admin_reminder_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reminder_id')->constrained('admin_reminders')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('read_at')->nullable();
            $table->unique(['reminder_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_reminder_recipients');
        Schema::dropIfExists('admin_reminders');
    }
};
