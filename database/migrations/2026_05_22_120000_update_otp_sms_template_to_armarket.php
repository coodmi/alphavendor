<?php

use App\Services\OtpMessageBuilder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $newTemplate = OtpMessageBuilder::DEFAULT_TEMPLATE;

        DB::table('settings')->where('key', 'OTP_TEMPLATE')->delete();

        DB::table('settings')->updateOrInsert(
            ['key' => 'OTP_TEMPLATE'],
            ['value' => $newTemplate, 'group' => 'otp', 'updated_at' => now(), 'created_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['key' => 'OTP_EXPIRY'],
            ['value' => '15', 'group' => 'otp', 'updated_at' => now(), 'created_at' => now()]
        );
    }

    public function down(): void
    {
        // Keep template on rollback; admins can edit from dashboard.
    }
};
