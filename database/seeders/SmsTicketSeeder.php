<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SmsLog;
use App\Models\OtpVerification;
use App\Models\SmsTemplate;
use App\Models\TicketCategory;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Models\User;
use Carbon\Carbon;

class SmsTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $admin = User::where('role', 'admin')->first();
        $users = User::where('role', '!=', 'admin')->take(5)->get();

        // Create SMS Templates
        $templates = [
            [
                'name' => 'OTP Verification',
                'slug' => 'otp-verification',
                'type' => 'otp',
                'template' => 'Your OTP code is: {{otp_code}}. Valid for {{expiry_minutes}} minutes. Do not share this code with anyone.',
                'variables' => ['otp_code', 'expiry_minutes'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Confirmation',
                'slug' => 'order-confirmation',
                'type' => 'transactional',
                'template' => 'Order {{order_number}} confirmed! Total: ${{total}}. Expected delivery: {{delivery_date}}. Track your order at {{tracking_url}}',
                'variables' => ['order_number', 'total', 'delivery_date', 'tracking_url'],
                'is_active' => true,
            ],
            [
                'name' => 'Welcome Message',
                'slug' => 'welcome-message',
                'type' => 'notification',
                'template' => 'Welcome to Alpha Vendor, {{name}}! Your account has been created successfully. Start exploring our products today!',
                'variables' => ['name'],
                'is_active' => true,
            ],
            [
                'name' => 'Password Reset',
                'slug' => 'password-reset',
                'type' => 'otp',
                'template' => 'Reset your Alpha Vendor password using code: {{reset_code}}. If you didn\'t request this, please ignore.',
                'variables' => ['reset_code'],
                'is_active' => true,
            ],
            [
                'name' => 'Promotion',
                'slug' => 'promotion-discount',
                'type' => 'marketing',
                'template' => '🎉 Special Offer! Get {{discount}}% OFF on {{category}}. Use code: {{promo_code}}. Valid until {{expiry_date}}. Shop now!',
                'variables' => ['discount', 'category', 'promo_code', 'expiry_date'],
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            SmsTemplate::create($template);
        }

        // Create SMS Logs
        foreach ($users as $user) {
            // OTP SMS
            SmsLog::create([
                'user_id' => $user->id,
                'phone_number' => '+88017' . rand(10000000, 99999999),
                'message' => 'Your OTP code is: ' . rand(100000, 999999) . '. Valid for 10 minutes.',
                'type' => 'otp',
                'status' => 'sent',
                'provider' => 'twilio',
                'cost' => 0.05,
                'sent_at' => Carbon::now()->subHours(rand(1, 48)),
                'delivered_at' => Carbon::now()->subHours(rand(1, 48)),
            ]);

            // Notification SMS
            SmsLog::create([
                'user_id' => $user->id,
                'phone_number' => '+88017' . rand(10000000, 99999999),
                'message' => 'Your order #ORD-' . rand(1000, 9999) . ' has been confirmed and is being processed.',
                'type' => 'transactional',
                'status' => 'sent',
                'provider' => 'twilio',
                'cost' => 0.05,
                'sent_at' => Carbon::now()->subHours(rand(1, 72)),
                'delivered_at' => Carbon::now()->subHours(rand(1, 72)),
            ]);
        }

        // Failed SMS
        SmsLog::create([
            'user_id' => $users->first()->id,
            'phone_number' => '+88017' . rand(10000000, 99999999),
            'message' => 'Test message',
            'type' => 'notification',
            'status' => 'failed',
            'provider' => 'twilio',
            'error_message' => 'Invalid phone number format',
            'sent_at' => Carbon::now()->subHours(2),
        ]);

        // Create OTP Verifications
        foreach ($users->take(3) as $user) {
            // Verified OTP
            OtpVerification::create([
                'user_id' => $user->id,
                'phone_number' => '+88017' . rand(10000000, 99999999),
                'otp_code' => (string) rand(100000, 999999),
                'purpose' => 'phone_verification',
                'status' => 'verified',
                'attempts' => 1,
                'expires_at' => Carbon::now()->addMinutes(10),
                'verified_at' => Carbon::now()->subMinutes(5),
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ]);

            // Pending OTP
            OtpVerification::create([
                'user_id' => $user->id,
                'phone_number' => '+88017' . rand(10000000, 99999999),
                'otp_code' => (string) rand(100000, 999999),
                'purpose' => 'login',
                'status' => 'pending',
                'attempts' => 0,
                'expires_at' => Carbon::now()->addMinutes(10),
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X)',
            ]);
        }

        // Failed OTP
        OtpVerification::create([
            'user_id' => $users->last()->id,
            'phone_number' => '+88017' . rand(10000000, 99999999),
            'otp_code' => (string) rand(100000, 999999),
            'purpose' => 'password_reset',
            'status' => 'failed',
            'attempts' => 3,
            'expires_at' => Carbon::now()->subMinutes(5),
            'ip_address' => '192.168.1.' . rand(1, 255),
            'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
        ]);

        // Create Ticket Categories
        $categories = [
            ['name' => 'Technical Support', 'slug' => 'technical', 'color' => '#667eea', 'order' => 1],
            ['name' => 'Billing & Payments', 'slug' => 'billing', 'color' => '#f59e0b', 'order' => 2],
            ['name' => 'Order Issues', 'slug' => 'orders', 'color' => '#10b981', 'order' => 3],
            ['name' => 'Account Management', 'slug' => 'account', 'color' => '#3b82f6', 'order' => 4],
            ['name' => 'Product Inquiry', 'slug' => 'product', 'color' => '#8b5cf6', 'order' => 5],
            ['name' => 'General Support', 'slug' => 'general', 'color' => '#6b7280', 'order' => 6],
        ];

        foreach ($categories as $category) {
            TicketCategory::create($category);
        }

        $categoryModels = TicketCategory::all();

        // Create Support Tickets with Messages
        $subjects = [
            'Unable to login to my account',
            'Payment not reflecting in wallet',
            'Order delivery delayed',
            'How to upgrade to wholesaler account?',
            'Product pricing discrepancy',
            'Need invoice for recent order',
            'Website loading very slow',
            'Unable to upload product images',
        ];

        $statuses = ['open', 'in_progress', 'pending_customer', 'resolved', 'closed'];
        $priorities = ['low', 'normal', 'high', 'urgent'];

        foreach ($users as $index => $user) {
            $categoryId = $categoryModels->random()->id;
            $priority = $priorities[array_rand($priorities)];
            $status = $statuses[array_rand($statuses)];
            
            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'category_id' => $categoryId,
                'assigned_to' => $status !== 'open' ? $admin->id : null,
                'subject' => $subjects[$index % count($subjects)],
                'description' => $this->generateDescription($subjects[$index % count($subjects)]),
                'priority' => $priority,
                'status' => $status,
                'first_response_at' => $status !== 'open' ? Carbon::now()->subHours(rand(1, 12)) : null,
                'resolved_at' => in_array($status, ['resolved', 'closed']) ? Carbon::now()->subHours(rand(1, 6)) : null,
                'closed_at' => $status === 'closed' ? Carbon::now()->subHours(rand(1, 3)) : null,
                'satisfaction_rating' => $status === 'closed' ? rand(3, 5) : null,
                'satisfaction_comment' => $status === 'closed' && rand(0, 1) ? 'Great support, issue resolved quickly!' : null,
                'created_at' => Carbon::now()->subDays(rand(1, 7)),
            ]);

            // Initial message
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'message' => $ticket->description,
                'is_staff' => false,
                'created_at' => $ticket->created_at,
            ]);

            // Add admin response if not open
            if ($status !== 'open') {
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $admin->id,
                    'message' => $this->generateAdminResponse(),
                    'is_staff' => true,
                    'read_at' => Carbon::now()->subHours(rand(1, 6)),
                    'created_at' => $ticket->created_at->addHours(rand(1, 4)),
                ]);

                // Add customer reply
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'message' => 'Thank you for looking into this. ' . $this->generateFollowUp(),
                    'is_staff' => false,
                    'read_at' => Carbon::now()->subHours(rand(1, 3)),
                    'created_at' => $ticket->created_at->addHours(rand(5, 8)),
                ]);
            }

            // Add internal note for some tickets
            if ($admin && rand(0, 1)) {
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $admin->id,
                    'message' => 'Internal note: ' . $this->generateInternalNote(),
                    'is_staff' => true,
                    'is_internal' => true,
                    'created_at' => $ticket->created_at->addHours(rand(2, 6)),
                ]);
            }
        }

        $this->command->info('SMS and Ticket sample data seeded successfully!');
        $this->command->info('- ' . SmsTemplate::count() . ' SMS templates created');
        $this->command->info('- ' . SmsLog::count() . ' SMS logs created');
        $this->command->info('- ' . OtpVerification::count() . ' OTP verifications created');
        $this->command->info('- ' . TicketCategory::count() . ' ticket categories created');
        $this->command->info('- ' . SupportTicket::count() . ' support tickets created');
        $this->command->info('- ' . TicketMessage::count() . ' ticket messages created');
    }

    private function generateDescription($subject): string
    {
        $descriptions = [
            'I have been experiencing this issue since yesterday and need urgent help. Please assist me at the earliest.',
            'Could you please help me resolve this? I have tried several times but the issue persists.',
            'This is affecting my business operations. I would appreciate a quick response on this matter.',
            'I noticed this problem while using the platform today. Can you please look into it?',
            'Need clarification on this matter. Please provide detailed information.',
        ];
        
        return $subject . '. ' . $descriptions[array_rand($descriptions)];
    }

    private function generateAdminResponse(): string
    {
        $responses = [
            'Thank you for contacting us. I have reviewed your issue and will assist you right away.',
            'I understand your concern. Let me investigate this and get back to you shortly.',
            'We apologize for the inconvenience. Our team is working on resolving this issue.',
            'I have checked your account and identified the problem. Here is what we can do...',
            'Thank you for bringing this to our attention. I will escalate this to the appropriate team.',
        ];
        
        return $responses[array_rand($responses)];
    }

    private function generateFollowUp(): string
    {
        $followUps = [
            'When can I expect this to be resolved?',
            'Is there anything else I need to provide?',
            'I have checked and the issue is still there.',
            'That helps, but I still have some questions.',
            'Perfect! That solved the issue.',
        ];
        
        return $followUps[array_rand($followUps)];
    }

    private function generateInternalNote(): string
    {
        $notes = [
            'Checked database - user record is intact. Possible cache issue.',
            'Escalated to payment team for verification.',
            'This is a known issue. Fix scheduled for next deployment.',
            'User needs additional guidance. Prepare detailed documentation.',
            'Priority ticket - assign to senior support.',
        ];
        
        return $notes[array_rand($notes)];
    }
}
