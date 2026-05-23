<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OtpVerification;
use Illuminate\Support\Facades\Http;
use App\Services\OtpMessageBuilder;

class OtpController extends Controller
{
    public function index(Request $request)
    {
        $otps = OtpVerification::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.otp.index', compact('otps'));
    }

    public function resend($id)
    {
        $otp = OtpVerification::findOrFail($id);
        $otp->otp_code = rand(100000, 999999);
        $otp->status = 'pending';
        $otp->expires_at = now()->addMinutes(5);
        $otp->save();
        // Send OTP via MiMSMS
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('MIMSMS_TOKEN'),
            'Content-Type' => 'application/json',
        ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
            'ApiKey' => env('MIMSMS_APIKEY'),
            'MobileNumber' => $otp->phone_number,
            'SenderName' => 'iSMS',
            'UserName' => env('MIMSMS_USERNAME'),
            'TransactionType' => 'T',
            'Message' => OtpMessageBuilder::build((string) $otp->otp_code),
        ]);
        \Log::info('Admin resent OTP', ['otp_id' => $otp->id, 'response' => $response->json()]);
        return back()->with('success', 'OTP resent successfully!');
    }
}
