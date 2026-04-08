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
        Schema::create('home_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, image, number
            $table->timestamps();
        });

        // Insert default content
        DB::table('home_page_contents')->insert([
            ['key' => 'hero_title', 'value' => 'Welcome to Alpha Vendor', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_description', 'value' => 'Your one-stop marketplace for retail, wholesale, import and export solutions', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_image', 'value' => 'https://images.unsplash.com/photo-1556740758-90de374c12ad?w=800&h=600&fit=crop', 'type' => 'image', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_page_contents');
    }
};
