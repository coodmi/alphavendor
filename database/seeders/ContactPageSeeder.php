<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactPageContent;

class ContactPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content = [
            // Hero Section
            ['key' => 'hero_title', 'value' => 'Contact Us'],
            ['key' => 'hero_subtitle', 'value' => 'We\'d love to hear from you. Get in touch with our team'],

            // Address Card
            ['key' => 'address_title', 'value' => 'Visit Us'],
            ['key' => 'address_line1', 'value' => '123 Business Street'],
            ['key' => 'address_line2', 'value' => 'New York, NY 10001'],
            ['key' => 'address_line3', 'value' => 'United States'],

            // Phone Card
            ['key' => 'phone_title', 'value' => 'Call Us'],
            ['key' => 'phone_primary', 'value' => '+1 (555) 123-4567'],
            ['key' => 'phone_secondary', 'value' => '+1 (555) 987-6543'],
            ['key' => 'phone_hours', 'value' => 'Mon-Fri 9am-6pm EST'],

            // Email Card
            ['key' => 'email_title', 'value' => 'Email Us'],
            ['key' => 'email_info', 'value' => 'info@alphavendor.com'],
            ['key' => 'email_support', 'value' => 'support@alphavendor.com'],
            ['key' => 'email_sales', 'value' => 'sales@alphavendor.com'],

            // Working Hours Card
            ['key' => 'hours_title', 'value' => 'Working Hours'],
            ['key' => 'hours_weekdays', 'value' => 'Monday - Friday'],
            ['key' => 'hours_weekdays_time', 'value' => '9:00 AM - 6:00 PM'],
            ['key' => 'hours_weekend', 'value' => 'Saturday: 10 AM - 4 PM'],

            // Contact Form
            ['key' => 'form_title', 'value' => 'Send Us a Message'],

            // Map Section
            ['key' => 'map_title', 'value' => 'Our Location'],
            ['key' => 'map_embed_url', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d193595.15830869428!2d-74.11976373946234!3d40.69766374865766!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2s!4v1234567890123!5m2!1sen!2s'],

            // Social Media
            ['key' => 'social_title', 'value' => 'Follow Us'],
            ['key' => 'social_description', 'value' => 'Stay connected with us on social media for updates, news, and special offers.'],
            ['key' => 'social_facebook', 'value' => '#'],
            ['key' => 'social_twitter', 'value' => '#'],
            ['key' => 'social_instagram', 'value' => '#'],
            ['key' => 'social_linkedin', 'value' => '#'],
            ['key' => 'social_youtube', 'value' => '#'],

            // FAQ Section
            ['key' => 'faq_title', 'value' => 'Frequently Asked Questions'],
            ['key' => 'faq_subtitle', 'value' => 'Find quick answers to common questions'],

            // Call to Action
            ['key' => 'cta_title', 'value' => 'Still Have Questions?'],
            ['key' => 'cta_subtitle', 'value' => 'Our support team is here to help you. Reach out anytime!'],
            ['key' => 'cta_email_text', 'value' => 'Email Support'],
            ['key' => 'cta_email_link', 'value' => 'support@alphavendor.com'],
            ['key' => 'cta_phone_text', 'value' => 'Call Us Now'],
            ['key' => 'cta_phone_link', 'value' => '+15551234567'],
        ];

        foreach ($content as $item) {
            ContactPageContent::updateOrCreate(
                ['key' => $item['key']],
                ['value' => $item['value']]
            );
        }
    }
}
