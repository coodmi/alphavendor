<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChatbotSetting;

class ChatbotSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'chatbot_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'chatbot_name', 'value' => 'Alpha AI Assistant', 'type' => 'text'],
            ['key' => 'chatbot_welcome_message', 'value' => 'Hello! 👋 I\'m Alpha AI Assistant. How can I help you today?', 'type' => 'textarea'],
            ['key' => 'chatbot_placeholder', 'value' => 'Type your message...', 'type' => 'text'],
            ['key' => 'chatbot_position', 'value' => 'bottom-right', 'type' => 'text'],
            ['key' => 'chatbot_theme_color', 'value' => '#FFA500', 'type' => 'color'],
            ['key' => 'chatbot_button_text', 'value' => 'Chat with us', 'type' => 'text'],
            ['key' => 'chatbot_avatar', 'value' => '', 'type' => 'text'],
            ['key' => 'chatbot_response_delay', 'value' => '1000', 'type' => 'text'],
            ['key' => 'chatbot_show_typing', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'chatbot_sound_enabled', 'value' => '1', 'type' => 'boolean'],
            ['key' => 'chatbot_business_hours_enabled', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'chatbot_business_hours_message', 'value' => 'We\'re online! Our team is available to help you.', 'type' => 'textarea'],
            ['key' => 'chatbot_offline_message', 'value' => 'We\'re currently offline. Leave us a message and we\'ll get back to you soon!', 'type' => 'textarea'],
            ['key' => 'chatbot_quick_replies', 'value' => "Track Order\nProduct Info\nContact Support\nReturn Policy", 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            ChatbotSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type']
                ]
            );
        }
    }
}
