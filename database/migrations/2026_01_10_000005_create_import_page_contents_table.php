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
        Schema::create('import_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, image, number
            $table->timestamps();
        });

        // Insert default content
        DB::table('import_page_contents')->insert([
            ['key' => 'hero_title', 'value' => 'Import Services', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_description', 'value' => 'Connect with international suppliers and import quality products', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'hero_image', 'value' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?w=800&h=600&fit=crop', 'type' => 'image', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_page_contents');
    }
};
