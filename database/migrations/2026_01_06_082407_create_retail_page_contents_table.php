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
        Schema::create('retail_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, image, number
            $table->timestamps();
        });

        // Insert default content
        DB::table('retail_page_contents')->insert([
            ['key' => 'hero_title', 'value' => 'Retail Marketplace', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_description', 'value' => 'Discover thousands of products from trusted retail vendors', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_image', 'value' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&h=500&fit=crop', 'type' => 'image', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'stat1_number', 'value' => '500+', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'stat1_label', 'value' => 'Retail Stores', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'stat2_number', 'value' => '10K+', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'stat2_label', 'value' => 'Products', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'stat3_number', 'value' => '50K+', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'stat3_label', 'value' => 'Happy Customers', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retail_page_contents');
    }
};
