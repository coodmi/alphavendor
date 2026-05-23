<?php

namespace App\Services;

use App\Support\BangladeshPhone;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MimSmsClient
{
    public function send(string $phoneNumber, string $message): array
    {
        $mobile = BangladeshPhone::forMimSms($phoneNumber);

        try {
            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post('https://api.mimsms.com/api/SmsSending/SMS', [
                    'ApiKey' => config('services.mimsms.apikey') ?: env('MIMSMS_APIKEY'),
                    'MobileNumber' => $mobile,
                    'SenderName' => config('services.mimsms.sender_name') ?: env('MIMSMS_SENDER_NAME', 'iSMS'),
                    'CampaignName' => config('services.mimsms.campaign_name') ?: env('MIMSMS_CAMPAIGN_NAME', ''),
                    'UserName' => config('services.mimsms.username') ?: env('MIMSMS_USERNAME'),
                    'TransactionType' => 'T',
                    'MessageId' => '',
                    'Message' => $message,
                    'CampaignId' => 'null',
                    'SmsData' => null,
                    'Telco' => '',
                ]);

            $data = $response->json() ?? [];

            Log::info('MiMSMS send', [
                'mobile' => $mobile,
                'http_status' => $response->status(),
                'status_code' => $data['statusCode'] ?? null,
                'response' => $data,
            ]);

            if ($response->successful() && ($data['statusCode'] ?? null) == '200') {
                return [
                    'success' => true,
                    'message_id' => $data['trxnId'] ?? null,
                    'response' => $data,
                ];
            }

            return [
                'success' => false,
                'error' => $data['responseResult'] ?? $response->body() ?? 'SMS gateway error',
                'response' => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('MiMSMS exception', ['mobile' => $mobile, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
