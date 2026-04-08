<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class OtpSettingsController extends Controller
{
    /**
     * Display OTP & API configuration page
     */
    public function index()
    {
        $settings = [
            'mimsms_apikey' => env('MIMSMS_APIKEY', ''),
            'mimsms_username' => env('MIMSMS_USERNAME', ''),
            'mimsms_sender_name' => env('MIMSMS_SENDER_NAME', ''),
            'mimsms_campaign_name' => env('MIMSMS_CAMPAIGN_NAME', ''),
            'otp_sms_template' => env('OTP_SMS_TEMPLATE', 'Your OTP is: {otp}. Valid for 15 minutes. Do not share this code.'),
            'otp_expiry_minutes' => env('OTP_EXPIRY_MINUTES', 5),
            'otp_max_attempts' => env('OTP_MAX_ATTEMPTS', 3),
            'sms_test_mode' => env('SMS_TEST_MODE', false),
        ];
        
        return view('admin.otp-settings.index', compact('settings'));
    }

    /**
     * Update OTP & API configuration
     */
    public function update(Request $request)
    {
        $request->validate([
            'mimsms_apikey' => 'required|string',
            'mimsms_username' => 'required|email',
            'mimsms_sender_name' => 'required|string',
            'mimsms_campaign_name' => 'nullable|string',
            'otp_sms_template' => 'required|string',
            'otp_expiry_minutes' => 'required|integer|min:1|max:60',
            'otp_max_attempts' => 'required|integer|min:1|max:10',
            'sms_test_mode' => 'boolean',
        ]);

        // Read .env file
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        // Update values
        $updates = [
            'MIMSMS_APIKEY' => $request->mimsms_apikey,
            'MIMSMS_USERNAME' => $request->mimsms_username,
            'MIMSMS_SENDER_NAME' => $request->mimsms_sender_name,
            'MIMSMS_CAMPAIGN_NAME' => $request->mimsms_campaign_name ?? '',
            'OTP_SMS_TEMPLATE' => $request->otp_sms_template,
            'OTP_EXPIRY_MINUTES' => $request->otp_expiry_minutes,
            'OTP_MAX_ATTEMPTS' => $request->otp_max_attempts,
            'SMS_TEST_MODE' => $request->has('sms_test_mode') ? 'true' : 'false',
        ];

        foreach ($updates as $key => $value) {
            // Escape special characters in value
            $escapedValue = addslashes($value);
            
            // Check if key exists
            if (preg_match("/^{$key}=/m", $envContent)) {
                // Update existing key
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$escapedValue}",
                    $envContent
                );
            } else {
                // Add new key
                $envContent .= "\n{$key}={$escapedValue}";
            }
        }

        // Write back to .env
        file_put_contents($envPath, $envContent);

        // Clear config cache
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return redirect()->back()->with('success', 'OTP & API settings updated successfully! Cache cleared.');
    }

    /**
     * Test API connection
     */
    public function testApi(Request $request)
    {
        $request->validate([
            'test_mobile' => 'required|string',
        ]);

        try {
            $mobile = $request->test_mobile;
            
            // Format phone number
            if (substr($mobile, 0, 2) === '01') {
                $mobile = '88' . $mobile;
            }

            $otp = rand(100000, 999999);
            
            // Get message template and replace {otp} placeholder
            $template = env('OTP_SMS_TEMPLATE', 'Your OTP is: {otp}. Valid for 15 minutes. Do not share this code.');
            $message = str_replace('{otp}', $otp, $template);
            
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
                'ApiKey' => env('MIMSMS_APIKEY'),
                'MobileNumber' => $mobile,
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

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['statusCode']) && $responseData['statusCode'] == '200') {
                return response()->json([
                    'success' => true,
                    'message' => 'Test SMS sent successfully!',
                    'otp' => $otp,
                    'sms_message' => $message,
                    'transaction_id' => $responseData['trxnId'] ?? 'N/A',
                    'response' => $responseData
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'API Error: ' . ($responseData['responseResult'] ?? 'Unknown error'),
                    'response' => $responseData
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
