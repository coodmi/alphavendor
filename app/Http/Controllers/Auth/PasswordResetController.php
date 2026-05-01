<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
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
        // Normalize mobile number before validation
        if ($request->has('mobile_number')) {
            $normalized = $this->normalizeMobile($request->mobile_number);
            $request->merge(['mobile_number' => $normalized]);
        }

        $request->validate([
            'mobile_number' => 'required|string|exists:users,mobile_number',
        ]);

        // Get user
        $user = User::where('mobile_number', $request->mobile_number)->first();
        
        if (!$user) {
            return back()->withErrors(['mobile_number' => 'No account found with this mobile number.']);
        }

        // Format phone number - ensure it has country code
        $mobileNumber = $request->mobile_number;
        if (substr($mobileNumber, 0, 2) === '01') {
            $mobileNumber = '88' . $mobileNumber; // Add Bangladesh country code
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Store OTP
        OtpVerification::updateOrCreate(
            ['phone_number' => $request->mobile_number],
            [
                'otp_code' => $otp,
                'purpose' => 'password_reset',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(15),
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
            $template = env('OTP_SMS_TEMPLATE', 'Your OTP is: {otp}. Valid for 15 minutes. Do not share this code.');
            $message = str_replace('{otp}', $otp, $template);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
                'ApiKey' => config('services.mimsms.apikey'),
                'MobileNumber' => $mobileNumber,
                'SenderName' => config('services.mimsms.sender_name'),
                'CampaignName' => config('services.mimsms.campaign_name', ''),
                'UserName' => config('services.mimsms.username'),
                'TransactionType' => 'T',
                'MessageId' => '',
                'Message' => $message,
                'CampaignId' => 'null',
                'SmsData' => null,
                'Telco' => ''
            ]);

            \Log::info('MiMSMS Password Reset OTP API response', [
                'mobile' => $mobileNumber,
                'otp' => $otp,
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

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
        
        // Format phone number - ensure it has country code
        $formattedMobile = $mobile;
        if (substr($mobile, 0, 2) === '01') {
            $formattedMobile = '88' . $mobile; // Add Bangladesh country code
        }
        
        // Update OTP record
        OtpVerification::updateOrCreate(
            ['phone_number' => $mobile],
            [
                'otp_code' => $otp,
                'purpose' => 'password_reset',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(15),
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
            $template = env('OTP_SMS_TEMPLATE', 'Your OTP is: {otp}. Valid for 15 minutes. Do not share this code.');
            $message = str_replace('{otp}', $otp, $template);

            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
                'ApiKey' => config('services.mimsms.apikey'),
                'MobileNumber' => $formattedMobile,
                'SenderName' => config('services.mimsms.sender_name'),
                'CampaignName' => config('services.mimsms.campaign_name', ''),
                'UserName' => config('services.mimsms.username'),
                'TransactionType' => 'T',
                'MessageId' => '',
                'Message' => $message,
                'CampaignId' => 'null',
                'SmsData' => null,
                'Telco' => ''
            ]);

            \Log::info('MiMSMS Resend Password Reset OTP', [
                'mobile' => $formattedMobile,
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['statusCode']) && $responseData['statusCode'] == '200') {
                    return response()->json([
                        'success' => true, 
                        'message' => 'OTP sent successfully!'
                    ]);
                }
            }

            return response()->json([
                'success' => false, 
                'message' => 'Failed to send OTP. Please try again.'
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

    private function normalizeMobile(string $number): string
    {
        $digits = preg_replace('/[^0-9]/', '', $number);
        if (strlen($digits) === 13 && str_starts_with($digits, '880')) return '+' . $digits;
        if (strlen($digits) === 11 && str_starts_with($digits, '0'))   return '+880' . substr($digits, 1);
        if (strlen($digits) === 10 && str_starts_with($digits, '1'))   return '+880' . $digits;
        if (str_starts_with($number, '+880')) return $number;
        return '+880' . $digits;
    }
}
