<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Support\Carbon;
use App\Services\MimSmsClient;
use App\Services\OtpMessageBuilder;
use App\Support\BangladeshPhone;
use Illuminate\Support\Facades\Session;

class PasswordResetController extends Controller
{
    /**
     * Show the form to request a password reset OTP
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.mobile');
    }

    /**
     * Send OTP to mobile number for password reset
     */
    public function sendResetOtp(Request $request)
    {
        if ($request->has('mobile_number')) {
            $request->merge(['mobile_number' => BangladeshPhone::normalize($request->mobile_number)]);
        }

        $normalized = $request->mobile_number;

        $user = User::query()
            ->whereIn('mobile_number', [
                $normalized,
                ltrim($normalized, '+'),
                BangladeshPhone::forMimSms($normalized),
            ])
            ->first();

        if (! $user) {
            return back()->withErrors(['mobile_number' => 'No account found with this mobile number.'])->withInput();
        }

        $request->merge(['mobile_number' => BangladeshPhone::normalize($user->mobile_number)]);

        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Store OTP
        OtpVerification::updateOrCreate(
            [
                'phone_number' => $request->mobile_number,
                'purpose' => 'password_reset',
            ],
            [
                'otp_code' => $otp,
                'purpose' => 'password_reset',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(OtpMessageBuilder::expiryMinutes()),
                'attempts' => 0,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        // Store mobile in session
        Session::put('reset_mobile_number', $request->mobile_number);

        // Send SMS
        try {
            // Check if in test mode
            if (env('SMS_TEST_MODE', false)) {
                \Log::info('🔔 OTP TEST MODE - Password Reset OTP', [
                    'mobile' => $request->mobile_number,
                    'otp' => $otp,
                    'message' => 'SMS sending skipped in test mode. Use this OTP to reset password.'
                ]);

                return redirect()->route('password.otp.form')
                    ->with('mobile_number', $request->mobile_number)
                    ->with('success', "TEST MODE: Your OTP is {$otp}");
            }

            // Get message template and replace {otp} placeholder
            $message = OtpMessageBuilder::build((string) $otp);
            $smsResult = app(MimSmsClient::class)->send($request->mobile_number, $message);

            if (! ($smsResult['success'] ?? false)) {
                \Log::error('Password reset OTP SMS failed', [
                    'mobile' => $request->mobile_number,
                    'error' => $smsResult['error'] ?? 'unknown',
                ]);

                return back()->withErrors([
                    'mobile_number' => 'OTP SMS পাঠানো যায়নি। নম্বর চেক করে আবার চেষ্টা করুন।',
                ])->withInput();
            }

            return redirect()->route('password.otp.form')
                ->with('mobile_number', $request->mobile_number)
                ->with('success', 'OTP sent to your mobile number.');

        } catch (\Exception $e) {
            \Log::error('Password reset OTP sending failed', [
                'mobile' => $request->mobile_number,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['mobile_number' => 'Failed to send OTP. Please try again.']);
        }
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        $mobile = session('reset_mobile_number');
        if (!$mobile) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.verify-otp', ['mobile_number' => $mobile]);
    }

    /**
     * Verify OTP and show password reset form
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $mobile = Session::get('reset_mobile_number');
        if (!$mobile) {
            return redirect()->route('password.request')->withErrors(['error' => 'Session expired. Please try again.']);
        }

        // Find the OTP record
        $otpRecord = OtpVerification::where('phone_number', $mobile)
            ->where('purpose', 'password_reset')
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'No OTP found. Please request a new code.']);
        }

        // Check if OTP is expired
        if ($otpRecord->expires_at->isPast()) {
            $otpRecord->update(['status' => 'expired']);
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new code.']);
        }

        // Check attempt limits
        if ($otpRecord->attempts >= 3) {
            $otpRecord->update(['status' => 'failed']);
            return back()->withErrors(['otp' => 'Maximum verification attempts exceeded. Please request a new code.']);
        }

        // Increment attempts
        $otpRecord->increment('attempts');

        // Verify OTP code
        if ($otpRecord->otp_code === $request->otp) {
            // Mark OTP as verified
            $otpRecord->update([
                'status' => 'verified',
                'verified_at' => now()
            ]);

            // Store verified status in session
            Session::put('otp_verified', true);

            return redirect()->route('password.reset.form');
        } else {
            $attemptsRemaining = 3 - $otpRecord->attempts;
            return back()->withErrors(['otp' => "Invalid OTP code. {$attemptsRemaining} attempts remaining."]);
        }
    }

    /**
     * Show the password reset form
     */
    public function showResetForm()
    {
        $mobile = Session::get('reset_mobile_number');
        $otpVerified = Session::get('otp_verified');

        if (!$mobile || !$otpVerified) {
            return redirect()->route('password.request')->withErrors(['error' => 'Please verify OTP first.']);
        }

        return view('auth.passwords.reset', ['mobile_number' => $mobile]);
    }

    /**
     * Reset the user's password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $mobile = Session::get('reset_mobile_number');
        $otpVerified = Session::get('otp_verified');

        if (!$mobile || !$otpVerified) {
            return redirect()->route('password.request')->withErrors(['error' => 'Session expired. Please try again.']);
        }

        // Update user password
        $user = User::where('mobile_number', $mobile)->first();
        
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['error' => 'User not found.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clean up session
        Session::forget('reset_mobile_number');
        Session::forget('otp_verified');

        \Log::info('Password reset successful', [
            'user_id' => $user->id,
            'mobile' => $mobile
        ]);

        return redirect()->route('login')->with('success', 'Your password has been reset successfully!');
    }

    /**
     * Resend OTP for password reset
     */
    public function resendOtp(Request $request)
    {
        $mobile = Session::get('reset_mobile_number');
        
        if (!$mobile) {
            return response()->json([
                'success' => false, 
                'message' => 'Session expired. Please try again.'
            ], 400);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        
        $mobile = BangladeshPhone::normalize($mobile);

        // Update OTP record
        OtpVerification::updateOrCreate(
            [
                'phone_number' => $mobile,
                'purpose' => 'password_reset',
            ],
            [
                'otp_code' => $otp,
                'purpose' => 'password_reset',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(OtpMessageBuilder::expiryMinutes()),
                'attempts' => 0,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        // Send SMS
        try {
            // Check if in test mode
            if (env('SMS_TEST_MODE', false)) {
                \Log::info('🔔 OTP TEST MODE - Resend Password Reset OTP', [
                    'mobile' => $formattedMobile,
                    'otp' => $otp,
                    'message' => 'SMS sending skipped in test mode.'
                ]);

                return response()->json([
                    'success' => true, 
                    'message' => "TEST MODE: Your OTP is {$otp}",
                    'test_otp' => $otp
                ]);
            }

            // Get message template and replace {otp} placeholder
            $message = OtpMessageBuilder::build((string) $otp);
            $smsResult = app(MimSmsClient::class)->send($mobile, $message);

            if ($smsResult['success'] ?? false) {
                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent successfully!',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $smsResult['error'] ?? 'Failed to send OTP. Please try again.',
            ], 500);

        } catch (\Exception $e) {
            \Log::error('Resend password reset OTP failed', [
                'mobile' => $mobile,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false, 
                'message' => 'Failed to send OTP. Please try again.'
            ], 500);
        }
    }

}
