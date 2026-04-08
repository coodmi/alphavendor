<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old table and recreate with new structure
        Schema::dropIfExists('contact_page_contents');
        
        Schema::create('contact_page_contents', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('hero_title')->default('Contact Us');
            $table->text('hero_subtitle')->nullable();
            
            // Address Section
            $table->string('address_title')->default('Visit Us');
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
            
            // Phone Section
            $table->string('phone_title')->default('Call Us');
            $table->string('phone_primary')->nullable();
            $table->string('phone_secondary')->nullable();
            $table->string('phone_hours')->nullable();
            
            // Email Section
            $table->string('email_title')->default('Email Us');
            $table->string('email_info')->nullable();
            $table->string('email_support')->nullable();
            $table->string('email_sales')->nullable();
            
            // Working Hours Section
            $table->string('hours_title')->default('Working Hours');
            $table->string('hours_weekdays')->nullable();
            $table->string('hours_weekdays_time')->nullable();
            $table->string('hours_weekend')->nullable();
            
            // Contact Form Section
            $table->string('form_title')->default('Send Us a Message');
            
            // Map Section
            $table->string('map_title')->default('Our Location');
            $table->text('map_embed_url')->nullable();
            
            // Social Media Section
            $table->string('social_title')->default('Follow Us');
            $table->text('social_description')->nullable();
            $table->string('social_facebook')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_youtube')->nullable();
            
            // FAQ Section
            $table->string('faq_title')->default('Frequently Asked Questions');
            $table->text('faq_subtitle')->nullable();
            
            // CTA Section
            $table->string('cta_title')->default('Still Have Questions?');
            $table->text('cta_subtitle')->nullable();
            $table->string('cta_email_text')->nullable();
            $table->string('cta_email_link')->nullable();
            $table->string('cta_phone_text')->nullable();
            $table->string('cta_phone_link')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_page_contents');
        
        // Recreate old structure
        Schema::create('contact_page_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key', 191)->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });
    }
};
