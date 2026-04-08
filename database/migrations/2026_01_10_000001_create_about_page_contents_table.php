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
        Schema::create('about_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, image, number
            $table->timestamps();
        });

        // Insert default content
        DB::table('about_page_contents')->insert([
            ['key' => 'hero_title', 'value' => 'About Alpha Vendor', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_description', 'value' => 'Your trusted partner in connecting vendors and buyers worldwide', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_image', 'value' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop', 'type' => 'image', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mission_title', 'value' => 'Our Mission', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mission_description', 'value' => 'To empower businesses by providing a seamless platform for trade and commerce', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'vision_title', 'value' => 'Our Vision', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'vision_description', 'value' => 'To become the leading global marketplace connecting suppliers and buyers', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'values_title', 'value' => 'Our Values', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'values_description', 'value' => 'Trust, Quality, Innovation, and Customer Success', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_page_contents');
    }
};
