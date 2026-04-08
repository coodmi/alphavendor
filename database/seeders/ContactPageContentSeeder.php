<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactPageContent;

class ContactPageContentSeeder extends Seeder
{
    public function run(): void
    {
        ContactPageContent::create([
            // Hero Section
            'hero_title' => 'Contact Us',
            'hero_subtitle' => 'We\'d love to hear from you. Get in touch with our team',
            
            // Address Section
            'address_title' => 'Visit Us',
            'address_line1' => '123 Business Street',
            'address_line2' => 'Dhaka 1000',
            'address_line3' => 'Bangladesh',
            
            // Phone Section
            'phone_title' => 'Call Us',
            'phone_primary' => '+880 1700-000000',
            'phone_secondary' => '+880 1800-000000',
            'phone_hours' => 'Mon-Fri 9AM-6PM',
            
            // Email Section
            'email_title' => 'Email Us',
            'email_info' => 'info@alphavendor.com',
            'email_support' => 'support@alphavendor.com',
            'email_sales' => 'sales@alphavendor.com',
            
            // Working Hours Section
            'hours_title' => 'Working Hours',
            'hours_weekdays' => 'Monday - Friday',
            'hours_weekdays_time' => '9:00 AM - 6:00 PM',
            'hours_weekend' => 'Saturday: 10:00 AM - 4:00 PM',
            
            // Contact Form Section
            'form_title' => 'Send Us a Message',
            
            // Map Section
            'map_title' => 'Our Location',
            'map_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.9085788647937!2d90.39225931498193!3d23.750891084588654!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8b087026b81%3A0x8fa563bbdd5904c2!2sDhaka%2C%20Bangladesh!5e0!3m2!1sen!2s!4v1234567890123!5m2!1sen!2s',
            
            // Social Media Section
            'social_title' => 'Follow Us',
            'social_description' => 'Stay connected with us on social media for updates, news, and special offers.',
            'social_facebook' => 'https://facebook.com/alphavendor',
            'social_twitter' => 'https://twitter.com/alphavendor',
            'social_instagram' => 'https://instagram.com/alphavendor',
            'social_linkedin' => 'https://linkedin.com/company/alphavendor',
            'social_youtube' => 'https://youtube.com/@alphavendor',
            
            // FAQ Section
            'faq_title' => 'Frequently Asked Questions',
            'faq_subtitle' => 'Find quick answers to common questions',
            
            // CTA Section
            'cta_title' => 'Still Have Questions?',
            'cta_subtitle' => 'Our support team is here to help you. Reach out anytime!',
            'cta_email_text' => 'Email Us',
            'cta_email_link' => 'support@alphavendor.com',
            'cta_phone_text' => 'Call Us Now',
            'cta_phone_link' => '+8801700000000',
        ]);
    }
}
