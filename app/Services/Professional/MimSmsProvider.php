<?php

namespace App\Services\Professional;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\SmsProviderException;

/**
 * MiM SMS Provider
 * 
 * Professional implementation of MiM SMS API integration
 * 
 * @package App\Services\Professional
 * @author Your Name
 * @version 1.0.0
 */
class MimSmsProvider
{
    private const API_ENDPOINT = 'https://api.mimsms.com/api/SmsSending/SMS';
    private const TIMEOUT = 30;
    private const MAX_RETRIES = 3;
    private const CACHE_TTL = 300; // 5 minutes

    private string $apiKey;
    private string $username;
    private string $senderName;
    private bool $isTestMode;

    /**
     * Constructor
     *
     * @param array $config Provider configuration
     */
    public function __construct(array $config)
    {
        $this->apiKey = $config['api_key'] ?? 'dummy_key';
        $this->username = $config['username'] ?? 'dummy_user';
        $this->senderName = $config['sender_name'] ?? 'AlphaVendor';
        $this->isTestMode = $config['test_mode'] ?? true;
        
        // Log warning if using dummy credentials
        if ($this->apiKey === 'dummy_key' || $this->apiKey === 'dummy_api_key' || $this->apiKey === 'dummy_api_key_12345') {
            \Log::warning('MimSMS: Using dummy API credentials. SMS will not be sent. Configure real credentials in .env file.');
        }
    }

    /**
     * Send SMS via MiM SMS API
     *
     * @param string $phoneNumber Recipient phone number
     * @param string $message SMS message content
     * @param array $options Additional options
     * @return array API response
     * @throws SmsProviderException
     */
    public function sendSms(string $phoneNumber, string $message, array $options = []): array
    {
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);
        $this->validatePhoneNumber($formattedPhone);
        $this->validateMessage($message);

        // If using dummy credentials, return mock response
        if ($this->apiKey === 'dummy_key' || $this->apiKey === 'dummy_api_key' || $this->apiKey === 'dummy_api_key_12345' || $this->isTestMode) {
            \Log::info('MimSMS: Test mode - SMS not actually sent', [
                'phone' => $formattedPhone,
                'message' => substr($message, 0, 50)
            ]);
            return $this->mockResponse($formattedPhone, $message);
        }

        $payload = $this->buildPayload($formattedPhone, $message, $options);
        
        return $this->executeRequest($payload);
    }

    /**
     * Build API request payload
     *
     * @param string $phoneNumber Formatted phone number
     * @param string $message SMS message
     * @param array $options Additional options
     * @return array Request payload
     */
    private function buildPayload(string $phoneNumber, string $message, array $options = []): array
    {
        return [
            'ApiKey' => $this->apiKey,
            'MobileNumber' => $phoneNumber,
            'SenderName' => $this->senderName,
            'CampaignName' => $options['campaign_name'] ?? '',
            'UserName' => $this->username,
            'TransactionType' => $options['transaction_type'] ?? 'T',
            'MessageId' => $options['message_id'] ?? '',
            'Message' => $message,
            'Telco' => $options['telco'] ?? ''
        ];
    }

    /**
     * Execute API request with retry logic
     *
     * @param array $payload Request payload
     * @return array API response
     * @throws SmsProviderException
     */
    private function executeRequest(array $payload): array
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < self::MAX_RETRIES) {
            try {
                $response = Http::timeout(self::TIMEOUT)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'User-Agent' => 'Laravel-SMS-Service/1.0'
                    ])
                    ->post(self::API_ENDPOINT, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    return $this->processResponse($data);
                }

                throw new SmsProviderException(
                    "HTTP Error: {$response->status()} - {$response->body()}",
                    $response->status()
                );

            } catch (\Exception $e) {
                $lastException = $e;
                $attempt++;
                
                if ($attempt < self::MAX_RETRIES) {
                    $delay = pow(2, $attempt); // Exponential backoff
                    Log::warning("SMS API attempt {$attempt} failed, retrying in {$delay}s", [
                        'error' => $e->getMessage(),
                        'payload' => $this->sanitizePayload($payload)
                    ]);
                    sleep($delay);
                }
            }
        }

        throw new SmsProviderException(
            "Failed to send SMS after {$attempt} attempts: " . $lastException->getMessage(),
            0,
            $lastException
        );
    }

    /**
     * Process API response
     *
     * @param array $data Raw API response
     * @return array Processed response
     * @throws SmsProviderException
     */
    private function processResponse(array $data): array
    {
        if (!isset($data['statusCode'])) {
            throw new SmsProviderException('Invalid API response format');
        }

        $statusCode = $data['statusCode'];
        $status = $data['status'] ?? 'Unknown';
        $message = $data['responseResult'] ?? 'No message';
        $transactionId = $data['trxnId'] ?? null;

        switch ($statusCode) {
            case '200':
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'transaction_id' => $transactionId,
                    'provider_response' => $data,
                    'status_code' => $statusCode
                ];

            case '401':
                throw new SmsProviderException("Authentication failed: {$message}", 401);

            case '402':
                throw new SmsProviderException("Insufficient balance: {$message}", 402);

            case '400':
                throw new SmsProviderException("Bad request: {$message}", 400);

            default:
                throw new SmsProviderException("API error ({$statusCode}): {$message}", (int)$statusCode);
        }
    }

    /**
     * Format phone number for MiM SMS API
     *
     * @param string $phoneNumber Raw phone number
     * @return string Formatted phone number
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Handle different formats
        if (str_starts_with($phoneNumber, '+880')) {
            return substr($phoneNumber, 1); // Remove +
        }
        
        if (str_starts_with($phone, '880')) {
            return $phone;
        }
        
        if (str_starts_with($phone, '01')) {
            return '880' . $phone;
        }
        
        // Default: assume it needs 880 prefix
        return '880' . ltrim($phone, '0');
    }

    /**
     * Validate phone number
     *
     * @param string $phoneNumber Phone number to validate
     * @throws SmsProviderException
     */
    private function validatePhoneNumber(string $phoneNumber): void
    {
        if (!preg_match('/^880[0-9]{10}$/', $phoneNumber)) {
            throw new SmsProviderException("Invalid phone number format: {$phoneNumber}");
        }
    }

    /**
     * Validate SMS message
     *
     * @param string $message Message to validate
     * @throws SmsProviderException
     */
    private function validateMessage(string $message): void
    {
        if (empty(trim($message))) {
            throw new SmsProviderException('SMS message cannot be empty');
        }

        if (strlen($message) > 1600) {
            throw new SmsProviderException('SMS message too long (max 1600 characters)');
        }
    }

    /**
     * Generate mock response for testing
     *
     * @param string $phoneNumber Phone number
     * @param string $message Message
     * @return array Mock response
     */
    private function mockResponse(string $phoneNumber, string $message): array
    {
        Log::info('Mock SMS sent', [
            'phone' => $phoneNumber,
            'message' => substr($message, 0, 50) . '...',
            'provider' => 'mimsms_mock'
        ]);

        return [
            'success' => true,
            'message' => 'Mock SMS sent successfully',
            'transaction_id' => 'MOCK_' . strtoupper(uniqid()),
            'provider_response' => [
                'statusCode' => '200',
                'status' => 'Success',
                'responseResult' => 'Mock SMS sent successfully',
                'trxnId' => 'MOCK_' . strtoupper(uniqid())
            ],
            'status_code' => '200'
        ];
    }

    /**
     * Sanitize payload for logging (remove sensitive data)
     *
     * @param array $payload Original payload
     * @return array Sanitized payload
     */
    private function sanitizePayload(array $payload): array
    {
        $sanitized = $payload;
        $sanitized['ApiKey'] = '***REDACTED***';
        $sanitized['UserName'] = '***REDACTED***';
        return $sanitized;
    }

    /**
     * Check provider health
     *
     * @return array Health status
     */
    public function healthCheck(): array
    {
        $cacheKey = 'mimsms_health_check';
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            try {
                // Send a minimal test request to check connectivity
                $response = Http::timeout(10)->get('https://api.mimsms.com');
                
                return [
                    'healthy' => $response->successful(),
                    'response_time' => $response->transferStats?->getTransferTime() ?? 0,
                    'status_code' => $response->status(),
                    'checked_at' => now()->toISOString()
                ];
            } catch (\Exception $e) {
                return [
                    'healthy' => false,
                    'error' => $e->getMessage(),
                    'checked_at' => now()->toISOString()
                ];
            }
        });
    }
}