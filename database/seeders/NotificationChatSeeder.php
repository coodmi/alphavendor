<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Notification;
use App\Models\ChatConversation;
use App\Models\ChatMessage;

class NotificationChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first user (should be admin from previous seeder)
        $admin = User::where('role', 'admin')->first();
        $user = User::where('role', 'user')->first();
        
        if (!$user) {
            // Create a test user if none exists
            $user = User::create([
                'name' => 'Test User',
                'email' => 'testuser@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'status' => 'active',
            ]);
        }

        // Create sample notifications
        $notifications = [
            [
                'user_id' => $user->id,
                'type' => 'success',
                'title' => 'Welcome to AlphaVendor!',
                'message' => 'Your account has been successfully created. Start exploring our features.',
                'data' => json_encode(['action' => 'welcome']),
                'created_at' => now()->subHours(2),
            ],
            [
                'user_id' => $user->id,
                'type' => 'info',
                'title' => 'New Feature Available',
                'message' => 'Check out our new chat support feature. Get instant help from our team.',
                'data' => json_encode(['action' => 'feature_announcement']),
                'created_at' => now()->subHour(),
            ],
            [
                'user_id' => $user->id,
                'type' => 'warning',
                'title' => 'Profile Incomplete',
                'message' => 'Please complete your profile to access all features.',
                'data' => json_encode(['action' => 'profile_warning']),
                'created_at' => now()->subMinutes(30),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }

        // Create sample chat conversation
        $conversation = ChatConversation::create([
            'user_id' => $user->id,
            'admin_id' => $admin ? $admin->id : null,
            'subject' => 'How to become a vendor?',
            'status' => 'open',
            'priority' => 'normal',
            'last_message_at' => now()->subMinutes(15),
        ]);

        // Create sample messages
        ChatMessage::create([
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'message' => 'Hi! I would like to know how I can become a vendor on your platform.',
            'is_admin' => false,
            'created_at' => now()->subMinutes(20),
        ]);

        if ($admin) {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'user_id' => $admin->id,
                'message' => 'Hello! Thank you for your interest. To become a vendor, you need to apply for a role upgrade. Would you like to be a retailer, wholesaler, or exporter?',
                'is_admin' => true,
                'read_at' => now()->subMinutes(18),
                'created_at' => now()->subMinutes(18),
            ]);

            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'message' => 'I am interested in becoming a retailer. What documents do I need?',
                'is_admin' => false,
                'created_at' => now()->subMinutes(15),
            ]);
        }

        // Create another conversation for testing
        $conversation2 = ChatConversation::create([
            'user_id' => $user->id,
            'subject' => 'Payment inquiry',
            'status' => 'resolved',
            'priority' => 'high',
            'last_message_at' => now()->subDays(1),
        ]);

        ChatMessage::create([
            'conversation_id' => $conversation2->id,
            'user_id' => $user->id,
            'message' => 'I have a question about payment methods.',
            'is_admin' => false,
            'read_at' => now()->subDays(1),
            'created_at' => now()->subDays(1),
        ]);

        $this->command->info('Sample notifications and chat conversations created successfully!');
    }
}
