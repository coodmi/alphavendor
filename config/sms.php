<?php

use App\Services\OtpMessageBuilder;

return [
    'default_provider' => env('SMS_DEFAULT_PROVIDER', 'mimsms'),
    'otp_length' => (int) env('OTP_LENGTH', 6),
    'otp_expiry_minutes' => (int) env('OTP_EXPIRY_MINUTES', 15),
    'otp_template' => env('OTP_SMS_TEMPLATE', OtpMessageBuilder::DEFAULT_TEMPLATE),
];
