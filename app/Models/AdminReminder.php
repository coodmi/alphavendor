<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminReminder extends Model
{
    protected $fillable = [
        'sender_id',
        'title',
        'message',
        'type',
        'recipient_type',
        'recipient_role',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients()
    {
        return $this->belongsToMany(User::class, 'admin_reminder_recipients', 'reminder_id', 'user_id')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }

    /**
     * Check if a specific user has read this reminder.
     */
    public function isReadBy(int $userId): bool
    {
        return $this->recipients()
                    ->wherePivot('user_id', $userId)
                    ->wherePivotNotNull('read_at')
                    ->exists();
    }

    /**
     * Type badge color (Tailwind).
     */
    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'warning' => 'bg-yellow-100 text-yellow-800',
            'error'   => 'bg-red-100 text-red-800',
            'success' => 'bg-green-100 text-green-800',
            default   => 'bg-blue-100 text-blue-800',
        };
    }

    /**
     * Type icon (FontAwesome).
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'warning' => 'fa-exclamation-triangle',
            'error'   => 'fa-times-circle',
            'success' => 'fa-check-circle',
            default   => 'fa-info-circle',
        };
    }
}
