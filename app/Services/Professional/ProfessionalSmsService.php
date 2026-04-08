<?php

namespace App\Services\Professional;

use App\Models\SmsLog;
use App\Models\OtpVerification;
use App\Services\Professional\MimSmsProvider;
use App\Services\Professional\SmsServiceInterface;
use App\Exceptions\SmsProviderException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Professional SMS Service
 * 
 * Enterprise-grade SMS service with comprehensive features:
 * - Multiple provider support
 * - Rate limiting
 * - Comprehensive logging
 * - OTP management
 * - Health monitoring
 * - Statistics and analytics
 * 
 * @package App\Services\Professional
 * @author Your Name
 * @version 1.0.0
 */
class ProfessionalSmsService implements SmsServiceInterface
{
    private MimSmsProvider $provider;
    private array $config;
    private string $defaultProvider;

    // Rate limiting constants
    private const RATE_LIMIT_KEY = 'sms_rate_limit';
    private const DEFAULT_RATE_LIMIT = 60; // per minute
    private const OTP_RATE_LIMIT = 5; // per minute per phone

    // OTP constants
    private const DEFAULT_OTP_LENGTH = 6;
    private const DEFAULT_OTP_EXPIRY = 15; // minutes
    private const MAX_OTP_ATTEMPTS = 3;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config = config('sms_professional', []);
        $this->defaultProvider = $this->config['default_provider'] ?? 'mimsms';
        $this->initializeProvider();
    }

    /**
     * Initialize SMS provider
     */
    private function initializeProvider(): void
    {
        $providerConfig = $this->config['providers'][$this->defaultProvider] ?? [];
        
        switch ($this->defaultProvider) {
            case 'mimsms':
                $this->provider = new MimSmsProvider($providerConfig);
                break;
            
            default:
                throw new \InvalidArgumentException("Unsupported SMS provider: {$this->defaultProvider}");
        }
    }

    /**
     * Send SMS message
     *
     * @param string $phoneNumber Recipient phone number
     * @param string $message SMS message content
     * @param string $type Message type
     * @param int|null $userId User ID for logging
     * @return array Response array
     */
    public function send(string $phoneNumber, string $message, string $type = 'notification', ?int $userId = null): array
    {
        $startTime = microtime(true);
        $smsLog = null;

        try {
            // Validate inputs
            $this->validateSmsInputs($phoneNumber, $message, $type);

            // Check rate limits
            $this->checkRateLimit($phoneNumber);

            // Create SMS log entry
            $smsLog = $this->createSmsLog($phoneNumber, $message, $type, $userId);

            // Send SMS via provider
            $response = $this->provider->sendSms($phoneNumber, $message);

            // Update log with success
            $this->updateSmsLogSuccess($smsLog, $response, microtime(true) - $startTime);

            // Log success
            Log::info('SMS sent successfully', [
                'phone' => $this->maskPhoneNumber($phoneNumber),
                'type' => $type,
                'transaction_id' => $response['transaction_id'] ?? null,
                'duration' => round((microtime(true) - $startTime) * 1000, 2) . 'ms'
            ]);

            return [
                'success' => true,
                'message' => 'SMS sent successfully',
                'transaction_id' => $response['transaction_id'] ?? null,
                'sms_log_id' => $smsLog->id,
                'cost' => $this->calculateSmsCost($message),
                'provider' => $this->defaultProvider
            ];

        } catch (SmsProviderException $e) {
            return $this->handleSmsError($e, $smsLog, $phoneNumber, $type, microtime(true) - $startTime);
        } catch (\Exception $e) {
            return $this->handleSmsError(
                new SmsProviderException('Unexpected error: ' . $e->getMessage(), 0, $e),
                $smsLog,
                $phoneNumber,
                $type,
                microtime(true) - $startTime
            );
        }
    }

    /**
     * Send OTP message
     *
     * @param string $phoneNumber Recipient phone number
     * @param string $purpose OTP purpose
     * @param int|null $userId User ID for logging
     * @return array Response array
     */
    public function sendOtp(string $phoneNumber, string $purpose = 'phone_verification', ?int $userId = null): array
    {
        try {
            // Validate inputs
            $this->validateOtpInputs($phoneNumber, $purpose);

            // Check OTP rate limits
            $this->checkOtpRateLimit($phoneNumber);

            // Generate OTP
            $otpCode = $this->generateSecureOtp();
            $expiryMinutes = $this->config['otp_expiry_minutes'] ?? self::DEFAULT_OTP_EXPIRY;

            // Create OTP record
            $otpRecord = $this->createOtpRecord($phoneNumber, $otpCode, $purpose, $expiryMinutes, $userId);

            // Prepare OTP message
            $message = $this->buildOtpMessage($otpCode, $purpose, $expiryMinutes);

            // Send SMS
            $smsResponse = $this->send($phoneNumber, $message, 'otp', $userId);

            if ($smsResponse['success']) {
                // Update OTP record with SMS details
                $otpRecord->update([
                    'sms_log_id' => $smsResponse['sms_log_id'],
                    'sent_at' => now()
                ]);

                Log::info('OTP sent successfully', [
                    'phone' => $this->maskPhoneNumber($phoneNumber),
                    'purpose' => $purpose,
                    'otp_id' => $otpRecord->id,
                    'expires_at' => $otpRecord->expires_at
                ]);

                return [
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'otp_id' => $otpRecord->id,
                    'expires_in' => $expiryMinutes * 60,
                    'expires_at' => $otpRecord->expires_at->toISOString(),
                    'transaction_id' => $smsResponse['transaction_id']
                ];
            } else {
                // Mark OTP as failed
                $otpRecord->update(['status' => 'failed']);
                return $smsResponse;
            }

        } catch (\Exception $e) {
            Log::error('OTP sending failed', [
                'phone' => $this->maskPhoneNumber($phoneNumber),
                'purpose' => $purpose,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to send OTP: ' . $e->getMessage(),
                'error_code' => 'OTP_SEND_FAILED'
            ];
        }
    }

    /**
     * Verify OTP code
     *
     * @param string $phoneNumber Phone number to verify
     * @param string $otpCode OTP code to verify
     * @param string $purpose OTP purpose
     * @return array Verification result
     */
    public function verifyOtp(string $phoneNumber, string $otpCode, string $purpose = 'phone_verification'): array
    {
        try {
            // Find the latest pending OTP
            $otpRecord = OtpVerification::where('phone_number', $phoneNumber)
                ->where('purpose', $purpose)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if (!$otpRecord) {
                return [
                    'success' => false,
                    'message' => 'OTP not found or already used',
                    'error_code' => 'OTP_NOT_FOUND'
                ];
            }

            // Check if OTP is expired
            if ($otpRecord->expires_at->isPast()) {
                $otpRecord->update(['status' => 'expired']);
                
                return [
                    'success' => false,
                    'message' => 'OTP has expired',
                    'error_code' => 'OTP_EXPIRED'
                ];
            }

            // Check attempt limits
            if ($otpRecord->attempts >= self::MAX_OTP_ATTEMPTS) {
                $otpRecord->update(['status' => 'failed']);
                
                return [
                    'success' => false,
                    'message' => 'Maximum verification attempts exceeded',
                    'error_code' => 'MAX_ATTEMPTS_EXCEEDED'
                ];
            }

            // Increment attempts
            $otpRecord->increment('attempts');

            // Verify OTP code
            if ($otpRecord->otp_code === $otpCode) {
                $otpRecord->update([
                    'status' => 'verified',
                    'verified_at' => now()
                ]);

                Log::info('OTP verified successfully', [
                    'phone' => $this->maskPhoneNumber($phoneNumber),
                    'purpose' => $purpose,
                    'otp_id' => $otpRecord->id
                ]);

                return [
                    'success' => true,
                    'message' => 'OTP verified successfully',
                    'otp_id' => $otpRecord->id,
                    'verified_at' => $otpRecord->verified_at->toISOString()
                ];
            } else {
                Log::warning('Invalid OTP attempt', [
                    'phone' => $this->maskPhoneNumber($phoneNumber),
                    'purpose' => $purpose,
                    'attempts' => $otpRecord->attempts,
                    'otp_id' => $otpRecord->id
                ]);

                return [
                    'success' => false,
                    'message' => 'Invalid OTP code',
                    'error_code' => 'INVALID_OTP',
                    'attempts_remaining' => self::MAX_OTP_ATTEMPTS - $otpRecord->attempts
                ];
            }

        } catch (\Exception $e) {
            Log::error('OTP verification error', [
                'phone' => $this->maskPhoneNumber($phoneNumber),
                'purpose' => $purpose,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'OTP verification failed: ' . $e->getMessage(),
                'error_code' => 'VERIFICATION_ERROR'
            ];
        }
    }

    /**
     * Get SMS service statistics
     *
     * @return array Statistics
     */
    public function getStatistics(): array
    {
        $cacheKey = 'sms_statistics';
        
        return Cache::remember($cacheKey, 300, function () {
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();

            return [
                'total_sent' => SmsLog::count(),
                'sent_today' => SmsLog::whereDate('created_at', $today)->count(),
                'sent_this_month' => SmsLog::where('created_at', '>=', $thisMonth)->count(),
                'success_rate' => $this->calculateSuccessRate(),
                'failed_today' => SmsLog::whereDate('created_at', $today)->where('status', 'failed')->count(),
                'total_cost' => SmsLog::sum('cost'),
                'cost_today' => SmsLog::whereDate('created_at', $today)->sum('cost'),
                'otp_stats' => [
                    'total_sent' => SmsLog::where('type', 'otp')->count(),
                    'total_verified' => OtpVerification::where('status', 'verified')->count(),
                    'verification_rate' => $this->calculateOtpVerificationRate()
                ],
                'provider_stats' => $this->getProviderStatistics(),
                'last_updated' => now()->toISOString()
            ];
        });
    }

    /**
     * Check service health
     *
     * @return array Health status
     */
    public function healthCheck(): array
    {
        try {
            $providerHealth = $this->provider->healthCheck();
            $dbHealth = $this->checkDatabaseHealth();
            
            $isHealthy = $providerHealth['healthy'] && $dbHealth['healthy'];

            return [
                'healthy' => $isHealthy,
                'status' => $isHealthy ? 'operational' : 'degraded',
                'provider' => $providerHealth,
                'database' => $dbHealth,
                'checked_at' => now()->toISOString()
            ];

        } catch (\Exception $e) {
            return [
                'healthy' => false,
                'status' => 'error',
                'error' => $e->getMessage(),
                'checked_at' => now()->toISOString()
            ];
        }
    }

    // Private helper methods...

    private function validateSmsInputs(string $phoneNumber, string $message, string $type): void
    {
        if (empty($phoneNumber)) {
            throw new SmsProviderException('Phone number is required');
        }

        if (empty(trim($message))) {
            throw new SmsProviderException('Message cannot be empty');
        }

        $validTypes = ['otp', 'notification', 'marketing', 'transactional'];
        if (!in_array($type, $validTypes)) {
            throw new SmsProviderException("Invalid message type. Must be one of: " . implode(', ', $validTypes));
        }
    }

    private function validateOtpInputs(string $phoneNumber, string $purpose): void
    {
        if (empty($phoneNumber)) {
            throw new SmsProviderException('Phone number is required');
        }

        $validPurposes = ['registration', 'login', 'password_reset', 'phone_verification', 'transaction'];
        if (!in_array($purpose, $validPurposes)) {
            throw new SmsProviderException("Invalid OTP purpose. Must be one of: " . implode(', ', $validPurposes));
        }
    }

    private function checkRateLimit(string $phoneNumber): void
    {
        $key = self::RATE_LIMIT_KEY . ':' . $phoneNumber;
        
        if (RateLimiter::tooManyAttempts($key, self::DEFAULT_RATE_LIMIT)) {
            $seconds = RateLimiter::availableIn($key);
            throw new SmsProviderException("Rate limit exceeded. Try again in {$seconds} seconds.", 429);
        }

        RateLimiter::hit($key, 60);
    }

    private function checkOtpRateLimit(string $phoneNumber): void
    {
        $key = 'otp_rate_limit:' . $phoneNumber;
        
        if (RateLimiter::tooManyAttempts($key, self::OTP_RATE_LIMIT)) {
            $seconds = RateLimiter::availableIn($key);
            throw new SmsProviderException("OTP rate limit exceeded. Try again in {$seconds} seconds.", 429);
        }

        RateLimiter::hit($key, 60);
    }

    private function generateSecureOtp(): string
    {
        $length = $this->config['otp_length'] ?? self::DEFAULT_OTP_LENGTH;
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    private function createSmsLog(string $phoneNumber, string $message, string $type, ?int $userId): SmsLog
    {
        return SmsLog::create([
            'user_id' => $userId,
            'phone_number' => $phoneNumber,
            'message' => $message,
            'type' => $type,
            'status' => 'pending',
            'provider' => $this->defaultProvider,
            'cost' => $this->calculateSmsCost($message)
        ]);
    }

    private function createOtpRecord(string $phoneNumber, string $otpCode, string $purpose, int $expiryMinutes, ?int $userId): OtpVerification
    {
        return OtpVerification::create([
            'user_id' => $userId,
            'phone_number' => $phoneNumber,
            'otp_code' => $otpCode,
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes($expiryMinutes),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    private function updateSmsLogSuccess(SmsLog $smsLog, array $response, float $duration): void
    {
        $smsLog->update([
            'status' => 'sent',
            'provider_message_id' => $response['transaction_id'],
            'sent_at' => now(),
            'response_time' => round($duration * 1000, 2) // milliseconds
        ]);
    }

    private function handleSmsError(\Exception $e, ?SmsLog $smsLog, string $phoneNumber, string $type, float $duration): array
    {
        if ($smsLog) {
            $smsLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'response_time' => round($duration * 1000, 2)
            ]);
        }

        Log::error('SMS sending failed', [
            'phone' => $this->maskPhoneNumber($phoneNumber),
            'type' => $type,
            'error' => $e->getMessage(),
            'duration' => round($duration * 1000, 2) . 'ms'
        ]);

        return [
            'success' => false,
            'error' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'provider' => $this->defaultProvider
        ];
    }

    private function buildOtpMessage(string $otpCode, string $purpose, int $expiryMinutes): string
    {
        $templates = $this->config['templates']['otp'] ?? [];
        
        $template = $templates[$purpose] ?? $templates['phone_verification'] ?? 
                   "Your verification code is: {{otp}}. Valid for {{expiry}} minutes.";

        return str_replace(
            ['{{otp}}', '{{expiry}}'],
            [$otpCode, $expiryMinutes],
            $template
        );
    }

    private function calculateSmsCost(string $message): float
    {
        // Simple cost calculation - can be made more sophisticated
        $segments = ceil(strlen($message) / 160);
        $costPerSegment = 0.05; // $0.05 per segment
        return $segments * $costPerSegment;
    }

    private function calculateSuccessRate(): float
    {
        $total = SmsLog::count();
        if ($total === 0) return 100.0;
        
        $successful = SmsLog::where('status', 'sent')->count();
        return round(($successful / $total) * 100, 2);
    }

    private function calculateOtpVerificationRate(): float
    {
        $total = OtpVerification::count();
        if ($total === 0) return 100.0;
        
        $verified = OtpVerification::where('status', 'verified')->count();
        return round(($verified / $total) * 100, 2);
    }

    private function getProviderStatistics(): array
    {
        return [
            'current_provider' => $this->defaultProvider,
            'health_status' => $this->provider->healthCheck()
        ];
    }

    private function checkDatabaseHealth(): array
    {
        try {
            SmsLog::count();
            return ['healthy' => true, 'message' => 'Database connection OK'];
        } catch (\Exception $e) {
            return ['healthy' => false, 'error' => $e->getMessage()];
        }
    }

    private function maskPhoneNumber(string $phoneNumber): string
    {
        if (strlen($phoneNumber) < 4) return '***';
        return substr($phoneNumber, 0, 3) . str_repeat('*', strlen($phoneNumber) - 6) . substr($phoneNumber, -3);
    }
}