@extends('layouts.dashboard')

@section('title', 'Alpha AI Chat Management')
@section('page-title', 'Alpha AI Chat')

@section('sidebar-menu')
    @include('dashboards.partials.admin-sidebar')
@endsection

@section('content')
<div style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 5px;">Alpha AI Chat Settings</h2>
            <p style="color: #7f8c8d;">Configure your AI-powered chatbot for customer support</p>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="color: #7f8c8d; font-size: 14px;">Chatbot Status:</span>
            <span style="padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; {{ ($settings['chatbot_enabled'] ?? '0') == '1' ? 'background: #10b981; color: white;' : 'background: #ef4444; color: white;' }}">
                {{ ($settings['chatbot_enabled'] ?? '0') == '1' ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>
</div>

@if(session('success'))
<div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
    <i class="fas fa-check-circle" style="font-size: 20px;"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

<form method="POST" action="{{ route('admin.chatbot.update') }}" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px;">
    @csrf

    <!-- General Settings -->
    <div style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e7eb;">
        <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-cog" style="color: #667eea;"></i> General Settings
        </h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox" name="chatbot_enabled" value="1" {{ ($settings['chatbot_enabled'] ?? '0') == '1' ? 'checked' : '' }}
                    style="width: 18px; height: 18px; margin-right: 8px;">
                <span style="font-size: 16px; font-weight: 500; color: #2c3e50;">Enable Chatbot</span>
            </label>
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px; margin-left: 26px;">Turn on/off the chatbot on your website</p>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Chatbot Name *</label>
            <input type="text" name="chatbot_name" value="{{ $settings['chatbot_name'] ?? 'Alpha AI Assistant' }}" required
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">The name displayed in the chat header</p>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Welcome Message *</label>
            <textarea name="chatbot_welcome_message" rows="3" required
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">{{ $settings['chatbot_welcome_message'] ?? 'Hello! 👋 I\'m Alpha AI Assistant. How can I help you today?' }}</textarea>
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">First message shown when chat opens</p>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Input Placeholder *</label>
            <input type="text" name="chatbot_placeholder" value="{{ $settings['chatbot_placeholder'] ?? 'Type your message...' }}" required
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Chat Button Text</label>
            <input type="text" name="chatbot_button_text" value="{{ $settings['chatbot_button_text'] ?? 'Chat with us' }}"
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">Text shown on the chat button (leave empty for icon only)</p>
        </div>
    </div>

    <!-- Appearance Settings -->
    <div style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e7eb;">
        <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-palette" style="color: #f59e0b;"></i> Appearance
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Position *</label>
                <select name="chatbot_position" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                    <option value="bottom-right" {{ ($settings['chatbot_position'] ?? 'bottom-right') == 'bottom-right' ? 'selected' : '' }}>Bottom Right</option>
                    <option value="bottom-left" {{ ($settings['chatbot_position'] ?? '') == 'bottom-left' ? 'selected' : '' }}>Bottom Left</option>
                    <option value="top-right" {{ ($settings['chatbot_position'] ?? '') == 'top-right' ? 'selected' : '' }}>Top Right</option>
                    <option value="top-left" {{ ($settings['chatbot_position'] ?? '') == 'top-left' ? 'selected' : '' }}>Top Left</option>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Theme Color *</label>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <input type="color" name="chatbot_theme_color" value="{{ $settings['chatbot_theme_color'] ?? '#FFA500' }}" required
                        style="width: 80px; height: 45px; border: 1px solid #ddd; border-radius: 8px; cursor: pointer;">
                    <input type="text" value="{{ $settings['chatbot_theme_color'] ?? '#FFA500' }}" readonly
                        style="flex: 1; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; background: #f9fafb;">
                </div>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Avatar URL</label>
            <input type="text" name="chatbot_avatar" value="{{ $settings['chatbot_avatar'] ?? '' }}"
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="https://example.com/avatar.png">
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">URL to chatbot avatar image (leave empty for default)</p>
        </div>
    </div>

    <!-- Behavior Settings -->
    <div style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e7eb;">
        <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-sliders-h" style="color: #10b981;"></i> Behavior
        </h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Response Delay (ms) *</label>
            <input type="number" name="chatbot_response_delay" value="{{ $settings['chatbot_response_delay'] ?? '1000' }}" required min="0" max="5000"
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">Delay before showing bot response (0-5000ms)</p>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox" name="chatbot_show_typing" value="1" {{ ($settings['chatbot_show_typing'] ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; height: 18px; margin-right: 8px;">
                <span style="font-size: 16px; font-weight: 500; color: #2c3e50;">Show Typing Indicator</span>
            </label>
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px; margin-left: 26px;">Display "typing..." animation before responses</p>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox" name="chatbot_sound_enabled" value="1" {{ ($settings['chatbot_sound_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                    style="width: 18px; height: 18px; margin-right: 8px;">
                <span style="font-size: 16px; font-weight: 500; color: #2c3e50;">Enable Sound Notifications</span>
            </label>
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px; margin-left: 26px;">Play sound when new messages arrive</p>
        </div>
    </div>

    <!-- Quick Replies -->
    <div style="margin-bottom: 40px; padding-bottom: 30px; border-bottom: 2px solid #e5e7eb;">
        <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-bolt" style="color: #8b5cf6;"></i> Quick Replies
        </h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Quick Reply Buttons</label>
            <textarea name="chatbot_quick_replies" rows="4"
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;" placeholder="Track Order&#10;Product Info&#10;Contact Support&#10;Return Policy">{{ $settings['chatbot_quick_replies'] ?? "Track Order\nProduct Info\nContact Support\nReturn Policy" }}</textarea>
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px;">One button per line. These appear as quick action buttons in the chat.</p>
        </div>
    </div>

    <!-- Business Hours -->
    <div style="margin-bottom: 30px;">
        <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-clock" style="color: #ef4444;"></i> Business Hours
        </h3>
        
        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox" name="chatbot_business_hours_enabled" value="1" {{ ($settings['chatbot_business_hours_enabled'] ?? '0') == '1' ? 'checked' : '' }}
                    style="width: 18px; height: 18px; margin-right: 8px;">
                <span style="font-size: 16px; font-weight: 500; color: #2c3e50;">Enable Business Hours Mode</span>
            </label>
            <p style="font-size: 13px; color: #7f8c8d; margin-top: 5px; margin-left: 26px;">Show different messages during/outside business hours</p>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Business Hours Message</label>
            <textarea name="chatbot_business_hours_message" rows="2"
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">{{ $settings['chatbot_business_hours_message'] ?? 'We\'re online! Our team is available to help you.' }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #2c3e50; margin-bottom: 8px;">Offline Message</label>
            <textarea name="chatbot_offline_message" rows="2"
                style="width: 100%; padding: 12px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">{{ $settings['chatbot_offline_message'] ?? 'We\'re currently offline. Leave us a message and we\'ll get back to you soon!' }}</textarea>
        </div>
    </div>

    <!-- Submit Button -->
    <div style="display: flex; justify-content: flex-end; gap: 12px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
        <a href="{{ route('admin.dashboard') }}" style="background: #e5e7eb; color: #6b7280; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500;">
            Cancel
        </a>
        <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 32px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 16px;">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </div>
</form>

<!-- Preview Section -->
<div style="margin-top: 30px; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px;">
    <h3 style="font-size: 20px; font-weight: 600; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-eye" style="color: #667eea;"></i> Preview
    </h3>
    <div style="background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 12px; padding: 40px; text-align: center;">
        <i class="fas fa-comment-dots" style="font-size: 64px; color: {{ $settings['chatbot_theme_color'] ?? '#FFA500' }}; margin-bottom: 16px;"></i>
        <p style="color: #6b7280; font-size: 16px; margin-bottom: 8px;">Chatbot preview will appear here</p>
        <p style="color: #9ca3af; font-size: 14px;">Save your settings to see the chatbot in action on your website</p>
    </div>
</div>

<script>
// Update color preview when color picker changes
document.querySelector('input[name="chatbot_theme_color"]').addEventListener('input', function(e) {
    document.querySelector('input[type="text"][readonly]').value = e.target.value;
});
</script>
@endsection
