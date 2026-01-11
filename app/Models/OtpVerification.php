<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'otp_code',
        'purpose',
        'status',
        'attempts',
        'expires_at',
        'verified_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isVerified()
    {
        return $this->status === 'verified';
    }

    public function canAttempt()
    {
        return $this->attempts < 3 && !$this->isExpired();
    }

    public function verify($code)
    {
        if ($this->isExpired()) {
            $this->update(['status' => 'expired']);
            return false;
        }

        $this->increment('attempts');

        if ($this->otp_code === $code) {
            $this->update([
                'status' => 'verified',
                'verified_at' => now()
            ]);
            return true;
        }

        if ($this->attempts >= 3) {
            $this->update(['status' => 'failed']);
        }

        return false;
    }

    public static function generateCode($length = 6)
    {
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
