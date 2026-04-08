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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'verification_status')) {
                $table->enum('verification_status', ['unverified', 'pending', 'verified', 'rejected'])
                    ->default('unverified')
                    ->after('status');
            }
            if (!Schema::hasColumn('users', 'verification_submitted_at')) {
                $table->timestamp('verification_submitted_at')->nullable()->after('verification_status');
            }
            if (!Schema::hasColumn('users', 'verification_reviewed_at')) {
                $table->timestamp('verification_reviewed_at')->nullable()->after('verification_submitted_at');
            }
            if (!Schema::hasColumn('users', 'verification_reviewed_by')) {
                $table->foreignId('verification_reviewed_by')->nullable()->constrained('users')->nullOnDelete()->after('verification_reviewed_at');
            }
            if (!Schema::hasColumn('users', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('verification_reviewed_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['verification_reviewed_by']);
            $table->dropColumn([
                'verification_status',
                'verification_submitted_at',
                'verification_reviewed_at',
                'verification_reviewed_by',
                'rejection_reason'
            ]);
        });
    }
};
