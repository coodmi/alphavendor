<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, image, number
            $table->timestamps();
        });

        // Insert default content
        DB::table('contact_page_contents')->insert([
            ['key' => 'hero_title', 'value' => 'Contact Us', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_description', 'value' => 'Get in touch with our team. We\'re here to help you succeed.', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email', 'value' => 'support@alphavendor.com', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone', 'value' => '+1 (555) 123-4567', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'address', 'value' => '123 Business St, Commerce City, CC 12345', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'business_hours', 'value' => 'Monday - Friday: 9:00 AM - 6:00 PM', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'support_email', 'value' => 'support@alphavendor.com', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'sales_email', 'value' => 'sales@alphavendor.com', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_page_contents');
    }
};
