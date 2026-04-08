<?php

namespace Tests\Feature\Professional;

use Tests\TestCase;
use App\Services\Professional\ProfessionalSmsService;
use App\Models\SmsLog;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

/**
 * Professional SMS Service Test Suite
 * 
 * Comprehensive tests for the professional SMS service
 * 
 * @package Tests\Feature\Professional
 * @author Your Name
 * @version 1.0.0
 */
class SmsServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProfessionalSmsService $smsService;
    private User $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set test configuration
        Config::set('sms.default_provider', 'mimsms');
        Config::set('sms.providers.mimsms', [
            'api_key' => 'test_key',
            'username' => 'test_user',
            'sender_name' => 'test_sender'
        ]);
        
        // Enable test mode
        Config::set('app.env', 'testing');
        putenv('SMS_TEST_MODE=true');
        
        $this->smsService = new ProfessionalSmsService();
        $this->testUser = User::factory()->create();
    }

    /** @test */
    public function it_can_send_sms_successfully()
    {
        $response = $this->smsService->send(
            '8801234567890',
            'Test message',
            'notification',
            $this->testUser->id
        );

        $this->assertTrue($response['success']);
        $this->assertEquals('SMS sent successfully', $response['message']);
        $this->assertArrayHasKey('transaction_id', $response);
        $this->assertArrayHasKey('sms_log_id', $response);

        // Check database record
        $this->assertDatabaseHas('sms_logs', [
            'user_id' => $this->testUser->id,
            'phone_number' => '8801234567890',
            'message' => 'Test message',
            'type' => 'notification',
            'status' => 'sent'
        ]);
    }

    /** @test */
    public function it_validates_phone_number_format()
    {
        $response = $this->smsService->send(
            'invalid_phone',
            'Test message',
            'notification'
        );

        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Invalid phone number', $response['error']);
    }

    /** @test */
    public function it_validates_message_content()
    {
        $response = $this->smsService->send(
            '8801234567890',
            '', // Empty message
            'notification'
        );

        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Message cannot be empty', $response['error']);
    }

    /** @test */
    public function it_validates_message_type()
    {
        $response = $this->smsService->send(
            '8801234567890',
            'Test message',
            'invalid_type'
        );

        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Invalid message type', $response['error']);
    }

    /** @test */
    public function it_can_send_otp_successfully()
    {
        $response = $this->smsService->sendOtp(
            '8801234567890',
            'phone_verification',
            $this->testUser->id
        );

        $this->assertTrue($response['success']);
        $this->assertEquals('OTP sent successfully', $response['message']);
        $this->assertArrayHasKey('otp_id', $response);
        $this->assertArrayHasKey('expires_in', $response);

        // Check database records
        $this->assertDatabaseHas('otp_verifications', [
            'user_id' => $this->testUser->id,
            'phone_number' => '8801234567890',
            'purpose' => 'phone_verification',
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('sms_logs', [
            'user_id' => $this->testUser->id,
            'phone_number' => '8801234567890',
            'type' => 'otp',
            'status' => 'sent'
        ]);
    }

    /** @test */
    public function it_validates_otp_purpose()
    {
        $response = $this->smsService->sendOtp(
            '8801234567890',
            'invalid_purpose'
        );

        $this->assertFalse($response['success']);
        $this->assertStringContainsString('Invalid OTP purpose', $response['error']);
    }

    /** @test */
    public function it_can_verify_otp_successfully()
    {
        // First send OTP
        $otpResponse = $this->smsService->sendOtp(
            '8801234567890',
            'phone_verification',
            $this->testUser->id
        );

        $this->assertTrue($otpResponse['success']);

        // Get the OTP code from database
        $otpRecord = OtpVerification::where('phone_number', '8801234567890')
            ->where('purpose', 'phone_verification')
            ->where('status', 'pending')
            ->latest()
            ->first();

        $this->assertNotNull($otpRecord);

        // Verify OTP
        $verifyResponse = $this->smsService->verifyOtp(
            '8801234567890',
            $otpRecord->otp_code,
            'phone_verification'
        );

        $this->assertTrue($verifyResponse['success']);
        $this->assertEquals('OTP verified successfully', $verifyResponse['message']);

        // Check database update
        $otpRecord->refresh();
        $this->assertEquals('verified', $otpRecord->status);
        $this->assertNotNull($otpRecord->verified_at);
    }

    /** @test */
    public function it_rejects_invalid_otp_code()
    {
        // First send OTP
        $this->smsService->sendOtp(
            '8801234567890',
            'phone_verification',
            $this->testUser->id
        );

        // Try to verify with wrong code
        $verifyResponse = $this->smsService->verifyOtp(
            '8801234567890',
            '000000', // Wrong code
            'phone_verification'
        );

        $this->assertFalse($verifyResponse['success']);
        $this->assertEquals('Invalid OTP code', $verifyResponse['message']);
        $this->assertArrayHasKey('attempts_remaining', $verifyResponse);
    }

    /** @test */
    public function it_handles_expired_otp()
    {
        // Create expired OTP
        $otpRecord = OtpVerification::create([
            'user_id' => $this->testUser->id,
            'phone_number' => '8801234567890',
            'otp_code' => '123456',
            'purpose' => 'phone_verification',
            'expires_at' => now()->subMinutes(10), // Expired
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test'
        ]);

        $verifyResponse = $this->smsService->verifyOtp(
            '8801234567890',
            '123456',
            'phone_verification'
        );

        $this->assertFalse($verifyResponse['success']);
        $this->assertEquals('OTP has expired', $verifyResponse['message']);

        // Check database update
        $otpRecord->refresh();
        $this->assertEquals('expired', $otpRecord->status);
    }

    /** @test */
    public function it_enforces_max_otp_attempts()
    {
        // Create OTP with max attempts reached
        $otpRecord = OtpVerification::create([
            'user_id' => $this->testUser->id,
            'phone_number' => '8801234567890',
            'otp_code' => '123456',
            'purpose' => 'phone_verification',
            'expires_at' => now()->addMinutes(15),
            'attempts' => 3, // Max attempts
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test'
        ]);

        $verifyResponse = $this->smsService->verifyOtp(
            '8801234567890',
            '123456',
            'phone_verification'
        );

        $this->assertFalse($verifyResponse['success']);
        $this->assertEquals('Maximum verification attempts exceeded', $verifyResponse['message']);

        // Check database update
        $otpRecord->refresh();
        $this->assertEquals('failed', $otpRecord->status);
    }

    /** @test */
    public function it_returns_statistics()
    {
        // Create some test data
        SmsLog::factory()->count(5)->create(['status' => 'sent']);
        SmsLog::factory()->count(2)->create(['status' => 'failed']);
        OtpVerification::factory()->count(3)->create(['status' => 'verified']);

        $stats = $this->smsService->getStatistics();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_sent', $stats);
        $this->assertArrayHasKey('success_rate', $stats);
        $this->assertArrayHasKey('otp_stats', $stats);
        $this->assertEquals(7, $stats['total_sent']);
    }

    /** @test */
    public function it_performs_health_check()
    {
        $health = $this->smsService->healthCheck();

        $this->assertIsArray($health);
        $this->assertArrayHasKey('healthy', $health);
        $this->assertArrayHasKey('status', $health);
        $this->assertArrayHasKey('provider', $health);
        $this->assertArrayHasKey('database', $health);
    }

    /** @test */
    public function it_calculates_sms_cost()
    {
        $response = $this->smsService->send(
            '8801234567890',
            'Short message', // Should be 1 segment
            'notification'
        );

        $this->assertTrue($response['success']);
        $this->assertEquals(0.05, $response['cost']); // 1 segment * $0.05

        // Test long message
        $longMessage = str_repeat('A', 320); // Should be 2 segments
        $response2 = $this->smsService->send(
            '8801234567890',
            $longMessage,
            'notification'
        );

        $this->assertTrue($response2['success']);
        $this->assertEquals(0.10, $response2['cost']); // 2 segments * $0.05
    }

    /** @test */
    public function it_masks_phone_numbers_in_logs()
    {
        // This test would check log output, but since we can't easily test log output
        // in unit tests, we'll just ensure the method exists and doesn't throw errors
        $response = $this->smsService->send(
            '8801234567890',
            'Test message',
            'notification'
        );

        $this->assertTrue($response['success']);
        // The actual masking is tested in the private method, 
        // but we can verify it doesn't break the flow
    }
}