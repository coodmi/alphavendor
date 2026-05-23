<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Services\OtpMessageBuilder;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = [
            'MIMSMS_APIKEY' => Setting::getValue('MIMSMS_APIKEY'),
            'MIMSMS_TOKEN' => Setting::getValue('MIMSMS_TOKEN'),
            'MIMSMS_USERNAME' => Setting::getValue('MIMSMS_USERNAME'),
            'MIMSMS_SENDER' => Setting::getValue('MIMSMS_SENDER'),
            'OTP_EXPIRY' => OtpMessageBuilder::expiryMinutes(),
            'OTP_TEMPLATE' => OtpMessageBuilder::getTemplate(),
        ];
        return view('admin.settings.otp', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'MIMSMS_APIKEY' => 'required|string',
                'MIMSMS_TOKEN' => 'nullable|string', // Made optional since MiMSMS doesn't need token
                'MIMSMS_USERNAME' => 'required|string',
                'MIMSMS_SENDER' => 'required|string',
                'OTP_EXPIRY' => 'required|integer|min:1',
                'OTP_TEMPLATE' => 'required|string',
            ]);

            // Save settings to database
            foreach ($validated as $key => $value) {
                Setting::setValue($key, $value ?? '');
            }

            OtpMessageBuilder::persistTemplate(
                $validated['OTP_TEMPLATE'],
                (int) $validated['OTP_EXPIRY']
            );

            // Also update .env file for immediate use
            $this->updateEnvFile([
                'MIMSMS_APIKEY' => $validated['MIMSMS_APIKEY'],
                'MIMSMS_USERNAME' => $validated['MIMSMS_USERNAME'],
                'MIMSMS_SENDER_NAME' => $validated['MIMSMS_SENDER'],
                'OTP_EXPIRY_MINUTES' => $validated['OTP_EXPIRY'],
                'OTP_SMS_TEMPLATE' => $validated['OTP_TEMPLATE'],
            ]);

            // Clear config cache
            \Artisan::call('config:clear');

            return back()->with('success', 'OTP/API settings updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('OTP Settings Update Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()]);
        }
    }

    /**
     * Update .env file with new values
     */
    private function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envFile, $envContent);
    }
}
