<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSetting;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Display the chatbot management page
     */
    public function index()
    {
        $settings = ChatbotSetting::getAllSettings();
        return view('admin.chatbot.index', compact('settings'));
    }

    /**
     * Update chatbot settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'chatbot_enabled' => 'nullable|boolean',
            'chatbot_name' => 'required|string|max:100',
            'chatbot_welcome_message' => 'required|string|max:500',
            'chatbot_placeholder' => 'required|string|max:100',
            'chatbot_position' => 'required|in:bottom-right,bottom-left,top-right,top-left',
            'chatbot_theme_color' => 'required|string|max:7',
            'chatbot_button_text' => 'nullable|string|max:50',
            'chatbot_avatar' => 'nullable|string|max:255',
            'chatbot_response_delay' => 'required|integer|min:0|max:5000',
            'chatbot_show_typing' => 'nullable|boolean',
            'chatbot_sound_enabled' => 'nullable|boolean',
            'chatbot_business_hours_enabled' => 'nullable|boolean',
            'chatbot_business_hours_message' => 'nullable|string|max:500',
            'chatbot_offline_message' => 'nullable|string|max:500',
            'chatbot_quick_replies' => 'nullable|string',
        ]);

        // Handle checkbox values
        $validated['chatbot_enabled'] = $request->has('chatbot_enabled') ? '1' : '0';
        $validated['chatbot_show_typing'] = $request->has('chatbot_show_typing') ? '1' : '0';
        $validated['chatbot_sound_enabled'] = $request->has('chatbot_sound_enabled') ? '1' : '0';
        $validated['chatbot_business_hours_enabled'] = $request->has('chatbot_business_hours_enabled') ? '1' : '0';

        foreach ($validated as $key => $value) {
            $type = 'text';
            if (in_array($key, ['chatbot_enabled', 'chatbot_show_typing', 'chatbot_sound_enabled', 'chatbot_business_hours_enabled'])) {
                $type = 'boolean';
            } elseif (in_array($key, ['chatbot_welcome_message', 'chatbot_business_hours_message', 'chatbot_offline_message', 'chatbot_quick_replies'])) {
                $type = 'textarea';
            } elseif ($key === 'chatbot_theme_color') {
                $type = 'color';
            }

            ChatbotSetting::updateSetting($key, $value, $type);
        }

        return redirect()->route('admin.chatbot.index')
            ->with('success', 'Chatbot settings updated successfully!');
    }
}
