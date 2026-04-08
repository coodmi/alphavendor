<?php

namespace App\Http\Controllers;

use App\Models\SmsLog;
use App\Models\OtpVerification;
use App\Models\SmsTemplate;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    /**
     * Get SMS logs with filtering
     */
    public function logs(Request $request)
    {
        $query = SmsLog::with('user');

        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('phone_number', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($logs);
    }

    /**
     * Send SMS
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:otp,notification,marketing,transactional',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $smsLog = SmsLog::create([
            'user_id' => $validated['user_id'] ?? null,
            'phone_number' => $validated['phone_number'],
            'message' => $validated['message'],
            'type' => $validated['type'],
            'status' => 'sent', // In production, integrate with actual SMS provider
            'sent_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'SMS sent successfully',
            'sms' => $smsLog
        ]);
    }

    /**
     * Get OTP verifications
     */
    public function otpLogs(Request $request)
    {
        $query = OtpVerification::with('user');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('purpose') && $request->purpose !== 'all') {
            $query->where('purpose', $request->purpose);
        }

        $otps = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json($otps);
    }

    /**
     * Generate and send OTP
     */
    public function generateOtp(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'purpose' => 'required|in:registration,login,password_reset,phone_verification,transaction',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $smsService = app(SmsService::class);
        $result = $smsService->sendOtp(
            $validated['phone_number'],
            $validated['purpose'],
            $validated['user_id'] ?? auth()->id()
        );

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 500);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'otp_code' => 'required|string',
            'purpose' => 'required|string',
        ]);

        $smsService = app(SmsService::class);
        $result = $smsService->verifyOtp(
            $validated['phone_number'],
            $validated['otp_code'],
            $validated['purpose']
        );

        if ($result['success']) {
            return response()->json($result);
        }

        return response()->json($result, 400);
    }

    /**
     * Get SMS templates
     */
    public function templates()
    {
        $templates = SmsTemplate::all();
        return response()->json($templates);
    }

    /**
     * Create SMS template
     */
    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:sms_templates,slug',
            'type' => 'required|in:otp,notification,marketing,transactional',
            'template' => 'required|string',
            'variables' => 'nullable|array',
        ]);

        $template = SmsTemplate::create($validated);

        return response()->json([
            'success' => true,
            'template' => $template
        ]);
    }

    /**
     * Update SMS template
     */
    public function updateTemplate(Request $request, $id)
    {
        $template = SmsTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:otp,notification,marketing,transactional',
            'template' => 'sometimes|string',
            'variables' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        $template->update($validated);

        return response()->json([
            'success' => true,
            'template' => $template
        ]);
    }

    /**
     * Delete SMS template
     */
    public function deleteTemplate($id)
    {
        $template = SmsTemplate::findOrFail($id);
        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template deleted successfully'
        ]);
    }

    /**
     * Get SMS statistics
     */
    public function stats()
    {
        $stats = [
            'total_sent' => SmsLog::count(),
            'sent_today' => SmsLog::whereDate('created_at', today())->count(),
            'failed' => SmsLog::failed()->count(),
            'pending' => SmsLog::pending()->count(),
            'total_cost' => SmsLog::sum('cost'),
            'otp_sent' => SmsLog::where('type', 'otp')->count(),
            'otp_verified' => OtpVerification::where('status', 'verified')->count(),
            'otp_failed' => OtpVerification::where('status', 'failed')->count(),
        ];

        return response()->json($stats);
    }
}
