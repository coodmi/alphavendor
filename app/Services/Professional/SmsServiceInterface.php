<?php

namespace App\Services\Professional;

/**
 * SMS Service Interface
 * 
 * Defines the contract for SMS service implementations
 * 
 * @package App\Services\Professional
 * @author Your Name
 * @version 1.0.0
 */
interface SmsServiceInterface
{
    /**
     * Send SMS message
     *
     * @param string $phoneNumber Recipient phone number
     * @param string $message SMS message content
     * @param string $type Message type (otp, notification, marketing, transactional)
     * @param int|null $userId User ID for logging
     * @return array Response array with success status and details
     */
    public function send(string $phoneNumber, string $message, string $type = 'notification', ?int $userId = null): array;

    /**
     * Send OTP message
     *
     * @param string $phoneNumber Recipient phone number
     * @param string $purpose OTP purpose (registration, login, password_reset, phone_verification, transaction)
     * @param int|null $userId User ID for logging
     * @return array Response array with success status and OTP details
     */
    public function sendOtp(string $phoneNumber, string $purpose = 'phone_verification', ?int $userId = null): array;

    /**
     * Verify OTP code
     *
     * @param string $phoneNumber Phone number to verify
     * @param string $otpCode OTP code to verify
     * @param string $purpose OTP purpose
     * @return array Response array with verification result
     */
    public function verifyOtp(string $phoneNumber, string $otpCode, string $purpose = 'phone_verification'): array;

    /**
     * Get SMS service statistics
     *
     * @return array Statistics array
     */
    public function getStatistics(): array;

    /**
     * Check service health
     *
     * @return array Health check result
     */
    public function healthCheck(): array;
}