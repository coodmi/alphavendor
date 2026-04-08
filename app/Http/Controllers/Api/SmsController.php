<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Professional\ProfessionalSmsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;

/**
 * SMS API Controller
 * 
 * Professional REST API for SMS operations
 * 
 * @package App\Http\Controllers\Api
 * @author Your Name
 * @version 1.0.0
 */
class SmsController extends Controller
{
    private ProfessionalSmsService $smsService;

    public function __construct(ProfessionalSmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send SMS message
     * 
     * @OA\Post(
     *     path="/api/sms/send",
     *     summary="Send SMS message",
     *     tags={"SMS"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone_number", "message"},
     *             @OA\Property(property="phone_number", type="string", example="8801234567890"),
     *             @OA\Property(property="message", type="string", example="Hello, this is a test message"),
     *             @OA\Property(property="type", type="string", enum={"notification", "marketing", "transactional"}, example="notification")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="SMS sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="SMS sent successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="transaction_id", type="string"),
     *                 @OA\Property(property="sms_log_id", type="integer"),
     *                 @OA\Property(property="cost", type="number", format="float")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Validation error"),
     *     @OA\Response(response=429, description="Rate limit exceeded"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function send(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|string|regex:/^880[0-9]{10}$/',
                'message' => 'required|string|min:1|max:1600',
                'type' => 'sometimes|string|in:notification,marketing,transactional'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Check API rate limit
            $key = 'api_sms_send:' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 100)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rate limit exceeded',
                    'retry_after' => RateLimiter::availableIn($key)
                ], 429);
            }

            RateLimiter::hit($key, 60);

            // Send SMS
            $result = $this->smsService->send(
                $request->phone_number,
                $request->message,
                $request->type ?? 'notification',
                auth()->id()
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => [
                        'transaction_id' => $result['transaction_id'],
                        'sms_log_id' => $result['sms_log_id'],
                        'cost' => $result['cost'],
                        'provider' => $result['provider']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'],
                    'error_code' => $result['error_code'] ?? null
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Send OTP
     * 
     * @OA\Post(
     *     path="/api/sms/send-otp",
     *     summary="Send OTP code",
     *     tags={"SMS"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone_number"},
     *             @OA\Property(property="phone_number", type="string", example="8801234567890"),
     *             @OA\Property(property="purpose", type="string", enum={"registration", "login", "password_reset", "phone_verification", "transaction"}, example="phone_verification")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OTP sent successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="otp_id", type="integer"),
     *                 @OA\Property(property="expires_in", type="integer"),
     *                 @OA\Property(property="expires_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function sendOtp(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|string|regex:/^880[0-9]{10}$/',
                'purpose' => 'sometimes|string|in:registration,login,password_reset,phone_verification,transaction'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Check API rate limit
            $key = 'api_otp_send:' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 10)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP rate limit exceeded',
                    'retry_after' => RateLimiter::availableIn($key)
                ], 429);
            }

            RateLimiter::hit($key, 60);

            // Send OTP
            $result = $this->smsService->sendOtp(
                $request->phone_number,
                $request->purpose ?? 'phone_verification',
                auth()->id()
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => [
                        'otp_id' => $result['otp_id'],
                        'expires_in' => $result['expires_in'],
                        'expires_at' => $result['expires_at'],
                        'transaction_id' => $result['transaction_id'] ?? null
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error'],
                    'error_code' => $result['error_code'] ?? null
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Verify OTP
     * 
     * @OA\Post(
     *     path="/api/sms/verify-otp",
     *     summary="Verify OTP code",
     *     tags={"SMS"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone_number", "otp_code"},
     *             @OA\Property(property="phone_number", type="string", example="8801234567890"),
     *             @OA\Property(property="otp_code", type="string", example="123456"),
     *             @OA\Property(property="purpose", type="string", example="phone_verification")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OTP verified successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="OTP verified successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="otp_id", type="integer"),
     *                 @OA\Property(property="verified_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required|string|regex:/^880[0-9]{10}$/',
                'otp_code' => 'required|string|size:6',
                'purpose' => 'sometimes|string|in:registration,login,password_reset,phone_verification,transaction'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Check API rate limit
            $key = 'api_otp_verify:' . $request->phone_number;
            if (RateLimiter::tooManyAttempts($key, 10)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP verification rate limit exceeded',
                    'retry_after' => RateLimiter::availableIn($key)
                ], 429);
            }

            RateLimiter::hit($key, 60);

            // Verify OTP
            $result = $this->smsService->verifyOtp(
                $request->phone_number,
                $request->otp_code,
                $request->purpose ?? 'phone_verification'
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => [
                        'otp_id' => $result['otp_id'],
                        'verified_at' => $result['verified_at']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'error_code' => $result['error_code'] ?? null,
                    'attempts_remaining' => $result['attempts_remaining'] ?? null
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Get SMS statistics
     * 
     * @OA\Get(
     *     path="/api/sms/statistics",
     *     summary="Get SMS service statistics",
     *     tags={"SMS"},
     *     @OA\Response(
     *         response=200,
     *         description="Statistics retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->smsService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Health check
     * 
     * @OA\Get(
     *     path="/api/sms/health",
     *     summary="Check SMS service health",
     *     tags={"SMS"},
     *     @OA\Response(
     *         response=200,
     *         description="Health check completed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function health(): JsonResponse
    {
        try {
            $health = $this->smsService->healthCheck();

            return response()->json([
                'success' => $health['healthy'],
                'data' => $health
            ], $health['healthy'] ? 200 : 503);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Health check failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
            ], 500);
        }
    }
}