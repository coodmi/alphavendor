<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtpVerification;
use App\Services\SmsService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

class OtpController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send OTP to phone number
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|regex:/^[0-9+\-\s]+$/',
            'purpose' => 'sometimes|in:registration,login,password_reset,phone_verification,transaction',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $phoneNumber = $request->phone_number;
        $purpose = $request->purpose ?? 'verification';

        // Rate limiting
        $key = 'otp-send:' . $phoneNumber;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many OTP requests. Try again in {$seconds} seconds."
            ], 429);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        // Check for recent pending OTP
        $recentOtp = OtpVerification::where('phone_number', $phoneNumber)
            ->where('purpose', $purpose)
            ->where('status', 'pending')
            ->where('created_at', '>', now()->subMinutes(2))
            ->first();

        if ($recentOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP already sent recently. Please wait before requesting again.'
            ], 429);
        }

        // Send OTP
        $result = $this->smsService->sendOtp(
            $phoneNumber, 
            $purpose, 
            auth()->id()
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'expires_in' => $result['expires_in']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'] ?? 'Failed to send OTP'
        ], 500);
    }

    /**
     * Verify OTP
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'otp_code' => 'required|string|size:6',
            'purpose' => 'sometimes|in:registration,login,password_reset,phone_verification,transaction',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $phoneNumber = $request->phone_number;
        $otpCode = $request->otp_code;
        $purpose = $request->purpose ?? 'verification';

        // Rate limiting for verification attempts
        $key = 'otp-verify:' . $phoneNumber;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many verification attempts. Try again in {$seconds} seconds."
            ], 429);
        }

        RateLimiter::hit($key, 300); // 5 minutes

        // Verify OTP
        $result = $this->smsService->verifyOtp($phoneNumber, $otpCode, $purpose);

        if ($result['success']) {
            RateLimiter::clear($key); // Clear rate limit on successful verification
            return response()->json($result);
        }

        return response()->json($result, 400);
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'purpose' => 'sometimes|in:registration,login,password_reset,phone_verification,transaction',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mark previous OTP as expired
        OtpVerification::where('phone_number', $request->phone_number)
            ->where('purpose', $request->purpose ?? 'verification')
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        // Send new OTP
        return $this->send($request);
    }

    /**
     * Get OTP status
     */
    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'purpose' => 'sometimes|in:registration,login,password_reset,phone_verification,transaction',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $otp = OtpVerification::where('phone_number', $request->phone_number)
            ->where('purpose', $request->purpose ?? 'verification')
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'No pending OTP found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'expires_at' => $otp->expires_at,
                'attempts' => $otp->attempts,
                'max_attempts' => 3,
                'is_expired' => $otp->isExpired(),
                'can_attempt' => $otp->canAttempt(),
            ]
        ]);
    }
}
