<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $provider;
    protected $config;

    public function __construct()
    {
        $this->provider = config('sms.default_provider', 'mimsms');
        $this->config = config('sms.providers.' . $this->provider);
    }

    /**
     * Send SMS message
     */
    public function send($phoneNumber, $message, $type = 'general', $userId = null)
    {
        try {
            // Log the SMS attempt
            $smsLog = SmsLog::create([
                'user_id' => $userId,
                'phone_number' => $phoneNumber,
                'message' => $message,
                'type' => $type,
                'status' => 'pending',
                'provider' => $this->provider,
            ]);

            // Send via provider
            $response = $this->sendViaProvider($phoneNumber, $message);

            if ($response['success']) {
                $smsLog->update([
                    'status' => 'sent',
                    'provider_message_id' => $response['message_id'] ?? null,
                    'sent_at' => now(),
                ]);
            } else {
                $smsLog->update([
                    'status' => 'failed',
                    'error_message' => $response['error'] ?? 'Unknown error',
                ]);
            }

            return $response;

        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            
            if (isset($smsLog)) {
                $smsLog->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate and send OTP
     */
    public function sendOtp($phoneNumber, $purpose = 'verification', $userId = null)
    {
        try {
            // Generate OTP code
            $otpCode = $this->generateOtpCode();

            // Create OTP record
            $otp = OtpVerification::create([
                'user_id' => $userId,
                'phone_number' => $phoneNumber,
                'otp_code' => $otpCode,
                'purpose' => $purpose,
                'expires_at' => now()->addMinutes((int) config('sms.otp_expiry_minutes', 10)),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Prepare OTP message
            $message = $this->getOtpMessage($otpCode, $purpose);

            // Send SMS
            $response = $this->send($phoneNumber, $message, 'otp', $userId);

            if ($response['success']) {
                return [
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'expires_in' => (int) config('sms.otp_expiry_minutes', 10) * 60,
                    'otp_id' => $otp->id
                ];
            } else {
                // Mark OTP as failed if SMS sending failed
                $otp->update(['status' => 'failed']);
                return $response;
            }

        } catch (\Exception $e) {
            Log::error('OTP generation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp($phoneNumber, $otpCode, $purpose = 'verification')
    {
        $otp = OtpVerification::where('phone_number', $phoneNumber)
            ->where('purpose', $purpose)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$otp) {
            return [
                'success' => false,
                'message' => 'OTP not found or already used'
            ];
        }

        if (!$otp->canAttempt()) {
            return [
                'success' => false,
                'message' => 'OTP expired or maximum attempts reached'
            ];
        }

        $verified = $otp->verify($otpCode);

        if ($verified) {
            return [
                'success' => true,
                'message' => 'OTP verified successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid OTP code',
            'attempts_remaining' => 3 - $otp->attempts
        ];
    }

    /**
     * Send via specific provider
     */
    protected function sendViaProvider($phoneNumber, $message)
    {
        switch ($this->provider) {
            case 'mock':
                return $this->sendViaMock($phoneNumber, $message);
            case 'mimsms':
                return $this->sendViaMimSms($phoneNumber, $message);
            case 'twilio':
                return $this->sendViaTwilio($phoneNumber, $message);
            case 'nexmo':
                return $this->sendViaNexmo($phoneNumber, $message);
            default:
                throw new \Exception('Unsupported SMS provider: ' . $this->provider);
        }
    }

    /**
     * Send via Mock (for testing)
     */
    protected function sendViaMock($phoneNumber, $message)
    {
        // Log the SMS for testing purposes
        Log::info('Mock SMS sent', [
            'phone' => $phoneNumber,
            'message' => $message
        ]);
        
        return [
            'success' => true,
            'message_id' => 'mock_' . uniqid(),
            'response' => ['status' => 'Mock SMS sent successfully']
        ];
    }

    /**
     * Send via MiMSMS
     */
    protected function sendViaMimSms($phoneNumber, $message)
    {
        // Fix phone number format - remove + and ensure 880 format
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
            'ApiKey' => env('MIMSMS_APIKEY'),
            'MobileNumber' => $formattedPhone,
            'SenderName' => env('MIMSMS_SENDER_NAME', 'iSMS'),
            'CampaignName' => '',
            'UserName' => env('MIMSMS_USERNAME'),
            'TransactionType' => 'T',
            'MessageId' => '',
            'Message' => $message,
            'Telco' => ''
        ]);

        if ($response->successful()) {
            $data = $response->json();
            
            // Check if the response indicates success
            if (isset($data['statusCode']) && $data['statusCode'] == '200') {
                return [
                    'success' => true,
                    'message_id' => $data['trxnId'] ?? null,
                    'response' => $data
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $data['responseResult'] ?? 'Unknown error from MiMSMS',
                    'status_code' => $data['statusCode'] ?? 'unknown'
                ];
            }
        }

        return [
            'success' => false,
            'error' => $response->body(),
            'status_code' => $response->status()
        ];
    }

    /**
     * Format phone number for MiMSMS (880 format, no +)
     */
    private function formatPhoneNumber($phoneNumber)
    {
        // Remove any spaces, dashes, or special characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // If starts with +880, remove + and keep 880
        if (strpos($phoneNumber, '+880') === 0) {
            return substr($phoneNumber, 1); // Remove the +
        }
        
        // If starts with 880, keep as is
        if (strpos($phone, '880') === 0) {
            return $phone;
        }
        
        // If starts with 01 (local format), add 880
        if (strpos($phone, '01') === 0) {
            return '880' . $phone;
        }
        
        // Default: assume it needs 880 prefix
        return '880' . ltrim($phone, '0');
    }

    /**
     * Send via Twilio (placeholder)
     */
    protected function sendViaTwilio($phoneNumber, $message)
    {
        // Implement Twilio integration here
        throw new \Exception('Twilio provider not implemented yet');
    }

    /**
     * Send via Nexmo (placeholder)
     */
    protected function sendViaNexmo($phoneNumber, $message)
    {
        // Implement Nexmo integration here
        throw new \Exception('Nexmo provider not implemented yet');
    }

    /**
     * Generate OTP code
     */
    protected function generateOtpCode()
    {
        $length = (int) config('sms.otp_length', 6);
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Get OTP message template
     */
    protected function getOtpMessage($otpCode, $purpose)
    {
        $templates = [
            'registration' => "Welcome! Your registration OTP is: {$otpCode}. Valid for " . (int) config('sms.otp_expiry_minutes', 10) . " minutes.",
            'login' => "Your login OTP is: {$otpCode}. Valid for " . (int) config('sms.otp_expiry_minutes', 10) . " minutes.",
            'password_reset' => "Your password reset OTP is: {$otpCode}. Valid for " . (int) config('sms.otp_expiry_minutes', 10) . " minutes.",
            'phone_verification' => "Your phone verification OTP is: {$otpCode}. Valid for " . (int) config('sms.otp_expiry_minutes', 10) . " minutes.",
            'transaction' => "Your transaction OTP is: {$otpCode}. Valid for " . (int) config('sms.otp_expiry_minutes', 10) . " minutes.",
            'verification' => "Your verification OTP is: {$otpCode}. Valid for " . (int) config('sms.otp_expiry_minutes', 10) . " minutes.",
        ];

        return $templates[$purpose] ?? $templates['verification'];
    }

    /**
     * Get SMS statistics
     */
    public function getStats()
    {
        return [
            'total_sent' => SmsLog::count(),
            'sent_today' => SmsLog::whereDate('created_at', today())->count(),
            'failed' => SmsLog::where('status', 'failed')->count(),
            'pending' => SmsLog::where('status', 'pending')->count(),
            'total_cost' => SmsLog::sum('cost'),
            'otp_sent' => SmsLog::where('type', 'otp')->count(),
            'otp_verified' => OtpVerification::where('status', 'verified')->count(),
            'otp_failed' => OtpVerification::where('status', 'failed')->count(),
        ];
    }
}