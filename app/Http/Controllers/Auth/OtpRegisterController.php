<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class OtpRegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $accountType = $request->input('account_type');
        $rules = [];
        $sessionData = [];
        if ($accountType === 'user') {
            $rules = [
                'account_type' => 'required|in:user,retailer,wholesaler,importer',
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'mobile_number' => 'required|string|unique:users,mobile_number',
                'password' => 'required|string|min:8|confirmed',
            ]; 
            $sessionData = $request->only(['account_type','name','email','mobile_number','password']);
        } else if (in_array($accountType, ['retailer', 'wholesaler', 'importer'])) {
            $rules = [
                'account_type' => 'required|in:user,retailer,wholesaler,importer',
                'company_name' => 'required|string|max:255',
                'business_type' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|unique:users,mobile_number',
                'address' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]; 
            $sessionData = [
                'account_type' => $accountType,
                'company_name' => $request->input('company_name'),
                'business_type' => $request->input('business_type'),
                'contact_person' => $request->input('contact_person'),
                'email' => $request->input('email'),
                'mobile_number' => $request->input('phone'),
                'address' => $request->input('address'),
                'password' => $request->input('password'),
            ]; 
        } else {
            return redirect()->back()->withErrors(['account_type' => 'Invalid account type'])->withInput();
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Store registration data in session
        Session::put('pending_registration', $sessionData);

        // Send OTP
        $otp = rand(100000, 999999);
        OtpVerification::updateOrCreate(
            ['phone_number' => $sessionData['mobile_number']],
            [
                'otp_code' => $otp,
                'purpose' => 'registration',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(15),
                'attempts' => 0,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        // Send SMS using the correct API format
        try {
            // Check if in test mode
            if (env('SMS_TEST_MODE', false)) {
                \Log::info('🔔 OTP TEST MODE - Registration OTP', [
                    'mobile' => $sessionData['mobile_number'],
                    'otp' => $otp,
                    'message' => 'SMS sending skipped in test mode. Use this OTP to verify.'
                ]);

                // Show OTP in session for easy access in test mode
                Session::flash('test_otp', $otp);
                
                return redirect()->route('register.otp.form')
                    ->with('mobile_number', $sessionData['mobile_number'])
                    ->with('success', "TEST MODE: Your OTP is {$otp}");
            }

            // Get message template and replace {otp} placeholder
            $template = env('OTP_SMS_TEMPLATE', 'Your OTP is: {otp}. Valid for 15 minutes. Do not share this code.');
            $message = str_replace('{otp}', $otp, $template);

            // Format phone number to 880 format
            $mobileNumber = $sessionData['mobile_number'];
            $mobileNumber = preg_replace('/[^0-9]/', '', $mobileNumber);
            if (str_starts_with($mobileNumber, '0')) {
                $mobileNumber = '880' . substr($mobileNumber, 1);
            } elseif (!str_starts_with($mobileNumber, '880')) {
                $mobileNumber = '880' . $mobileNumber;
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
                'ApiKey' => env('MIMSMS_APIKEY'),
                'MobileNumber' => $mobileNumber,
                'SenderName' => env('MIMSMS_SENDER_NAME'),
                'CampaignName' => env('MIMSMS_CAMPAIGN_NAME', ''),
                'UserName' => env('MIMSMS_USERNAME'),
                'TransactionType' => 'T',
                'MessageId' => '',
                'Message' => $message,
                'CampaignId' => 'null',
                'SmsData' => null,
                'Telco' => ''
            ]);

            \Log::info('MiMSMS Registration OTP API response', [
                'mobile' => $sessionData['mobile_number'],
                'otp' => $otp, // Log OTP for debugging (remove in production)
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            return redirect()->route('register.otp.form')->with('mobile_number', $sessionData['mobile_number']);

        } catch (\Exception $e) {
            \Log::error('Registration OTP sending failed', [
                'mobile' => $sessionData['mobile_number'],
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->withErrors(['mobile_number' => 'Failed to send verification code. Please try again.'])->withInput();
        }
    }

    public function showOtpForm()
    {
        $mobile = session('mobile_number') ?? (Session::get('pending_registration')['mobile_number'] ?? null);
        return view('auth.otp_verify', ['mobile_number' => $mobile]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);
        
        $pending = Session::get('pending_registration');
        if (!$pending) {
            return redirect()->route('register')->withErrors(['error' => 'Session expired. Please register again.']);
        }

        // Find the OTP record
        $otpRecord = OtpVerification::where('phone_number', $pending['mobile_number'])
            ->where('purpose', 'registration')
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$otpRecord) {
            return redirect()->back()->withErrors(['otp' => 'No OTP found. Please request a new code.']);
        }

        // Check if OTP is expired
        if ($otpRecord->expires_at->isPast()) {
            $otpRecord->update(['status' => 'expired']);
            return redirect()->back()->withErrors(['otp' => 'OTP has expired. Please request a new code.']);
        }

        // Check attempt limits
        if ($otpRecord->attempts >= 3) {
            $otpRecord->update(['status' => 'failed']);
            return redirect()->back()->withErrors(['otp' => 'Maximum verification attempts exceeded. Please request a new code.']);
        }

        // Increment attempts
        $otpRecord->increment('attempts');

        // Verify OTP code
        if ($otpRecord->otp_code === $request->otp) {
            // Check if user already exists with this email or mobile number
            $existingUser = User::where('email', $pending['email'])
                ->orWhere('mobile_number', $pending['mobile_number'])
                ->first();

            if ($existingUser) {
                // Clean up session and OTP
                Session::forget('pending_registration');
                Session::forget('mobile_number');
                $otpRecord->update(['status' => 'failed']);

                \Log::warning('Registration attempt with existing credentials', [
                    'email' => $pending['email'],
                    'mobile' => $pending['mobile_number'],
                    'existing_user_id' => $existingUser->id
                ]);

                return redirect()->route('login')->withErrors([
                    'email' => 'An account with this email or phone number already exists. Please login instead.'
                ]);
            }

            // Determine account type
            $accountType = $pending['account_type'] ?? 'user';
            $userData = [];
            if ($accountType === 'user') {
                $userData = [
                    'name' => $pending['name'],
                    'email' => $pending['email'],
                    'mobile_number' => $pending['mobile_number'],
                    'password' => Hash::make($pending['password']),
                    'role' => 'user',
                    'verification_status' => 'verified', // Customers are auto-verified
                ];
            } else if (in_array($accountType, ['retailer', 'wholesaler', 'importer'])) {
                $userData = [
                    'name' => $pending['contact_person'],
                    'email' => $pending['email'],
                    'mobile_number' => $pending['mobile_number'],
                    'password' => Hash::make($pending['password']),
                    'role' => $accountType,
                    'status' => 'pending', // Vendors need admin approval
                    'verification_status' => 'unverified', // Vendors need verification
                ];
            }
            
            try {
                $user = User::create($userData);
            } catch (\Illuminate\Database\QueryException $e) {
                // Handle any database errors gracefully
                Session::forget('pending_registration');
                Session::forget('mobile_number');
                $otpRecord->update(['status' => 'failed']);

                \Log::error('User creation failed during OTP verification', [
                    'email' => $pending['email'],
                    'mobile' => $pending['mobile_number'],
                    'error' => $e->getMessage()
                ]);

                return redirect()->route('register')->withErrors([
                    'error' => 'Registration failed. This email or phone number may already be in use. Please try again or contact support.'
                ]);
            }

            // For professional types, create RoleApplication
            if (in_array($accountType, ['retailer', 'wholesaler', 'importer'])) {
                try {
                    \App\Models\RoleApplication::create([
                        'user_id' => $user->id,
                        'requested_role' => $accountType,
                        'business_name' => $pending['company_name'] ?? null,
                        'business_type' => $pending['business_type'] ?? null,
                        'business_phone' => $pending['mobile_number'] ?? null,
                        'business_email' => $pending['email'] ?? null,
                        'business_address' => $pending['address'] ?? null,
                        'contact_person' => $pending['contact_person'] ?? null,
                        'status' => 'pending',
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Role application creation failed', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    // Continue anyway - user is created, they can apply later
                }
            }

            // Update OTP record
            $otpRecord->update([
                'status' => 'verified',
                'verified_at' => now()
            ]);

            // Clean up session
            Session::forget('pending_registration');
            Session::forget('mobile_number');

            // Log in user and regenerate session
            auth()->login($user);
            $request->session()->regenerate();

            \Log::info('User registration completed successfully', [
                'user_id' => $user->id,
                'mobile' => $user->mobile_number,
                'otp_id' => $otpRecord->id
            ]);

            // Redirect based on user role
            if (in_array($accountType, ['retailer', 'wholesaler', 'importer'])) {
                return redirect()->route('dashboard')->with('success', 'Registration completed successfully! Please complete your verification to start selling.');
            }

            return redirect()->route('home')->with('success', 'Registration completed successfully! Welcome to AlphaVendor.');
        } else {
            \Log::warning('Invalid OTP attempt', [
                'mobile' => $pending['mobile_number'],
                'provided_otp' => $request->otp,
                'attempts' => $otpRecord->attempts,
                'otp_id' => $otpRecord->id
            ]);

            $attemptsRemaining = 3 - $otpRecord->attempts;
            $errorMessage = "Invalid OTP code. {$attemptsRemaining} attempts remaining.";
            
            return redirect()->back()->withErrors(['otp' => $errorMessage]);
        }
    }

    public function resendOtp(Request $request)
    {
        $pending = Session::get('pending_registration');
        if (!$pending) {
            return response()->json([
                'success' => false, 
                'message' => 'Session expired. Please register again.'
            ], 400);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        
        // Update or create OTP record
        OtpVerification::updateOrCreate(
            ['phone_number' => $pending['mobile_number']],
            [
                'otp_code' => $otp,
                'purpose' => 'registration',
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(15),
                'attempts' => 0,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]
        );

        // Send SMS using the correct API format
        try {
            // Check if in test mode
            if (env('SMS_TEST_MODE', false)) {
                \Log::info('🔔 OTP TEST MODE - Resend OTP', [
                    'mobile' => $pending['mobile_number'],
                    'otp' => $otp,
                    'message' => 'SMS sending skipped in test mode. Use this OTP to verify.'
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

            $resendMobile = preg_replace('/[^0-9]/', '', $pending['mobile_number']);
            if (str_starts_with($resendMobile, '0')) {
                $resendMobile = '880' . substr($resendMobile, 1);
            } elseif (!str_starts_with($resendMobile, '880')) {
                $resendMobile = '880' . $resendMobile;
            }

            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
                'ApiKey' => env('MIMSMS_APIKEY'),
                'MobileNumber' => $resendMobile,
                'SenderName' => env('MIMSMS_SENDER_NAME'),
                'CampaignName' => env('MIMSMS_CAMPAIGN_NAME', ''),
                'UserName' => env('MIMSMS_USERNAME'),
                'TransactionType' => 'T',
                'MessageId' => '',
                'Message' => $message,
                'CampaignId' => 'null',
                'SmsData' => null,
                'Telco' => ''
            ]);

            \Log::info('MiMSMS Resend OTP API response', [
                'mobile' => $pending['mobile_number'],
                'response' => $response->json(),
                'status' => $response->status(),
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['statusCode']) && $responseData['statusCode'] == '200') {
                    return response()->json([
                        'success' => true, 
                        'message' => 'Verification code sent successfully!'
                    ]);
                } else {
                    \Log::warning('MiMSMS API returned non-200 status', [
                        'mobile' => $pending['mobile_number'],
                        'response' => $responseData
                    ]);
                    
                    return response()->json([
                        'success' => false, 
                        'message' => 'Failed to send verification code. Please try again.'
                    ], 500);
                }
            } else {
                \Log::error('MiMSMS API HTTP error', [
                    'mobile' => $pending['mobile_number'],
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return response()->json([
                    'success' => false, 
                    'message' => 'Failed to send verification code. Please try again.'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('Resend OTP exception', [
                'mobile' => $pending['mobile_number'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false, 
                'message' => 'Failed to send verification code. Please try again.'
            ], 500);
        }
    }
}
