<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old table and recreate with new structure
        Schema::dropIfExists('about_page_contents');
        
        Schema::create('about_page_contents', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('hero_title')->default('About AlphaVendor');
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_cta_primary_text')->nullable();
            $table->string('hero_cta_primary_link')->nullable();
            $table->string('hero_cta_secondary_text')->nullable();
            $table->string('hero_cta_secondary_link')->nullable();
            
            // Story Section
            $table->string('story_title')->default('Our Story');
            $table->text('story_paragraph_1')->nullable();
            $table->text('story_paragraph_2')->nullable();
            $table->text('story_paragraph_3')->nullable();
            
            // Mission & Vision
            $table->string('mission_title')->default('Our Mission');
            $table->text('mission_content')->nullable();
            $table->string('vision_title')->default('Our Vision');
            $table->text('vision_content')->nullable();
            
            // Stats Section
            $table->string('stats_vendors')->default('1000+');
            $table->string('stats_products')->default('50000+');
            $table->string('stats_customers')->default('100000+');
            $table->string('stats_countries')->default('50+');
            
            // CTA Section
            $table->string('cta_title')->default('Ready to Get Started?');
            $table->text('cta_subtitle')->nullable();
            $table->string('cta_primary_text')->nullable();
            $table->string('cta_primary_link')->nullable();
            $table->string('cta_secondary_text')->nullable();
            $table->string('cta_secondary_link')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_page_contents');
        
        // Recreate old structure
        Schema::create('about_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });
    }
};
