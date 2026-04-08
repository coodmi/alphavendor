<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationNotification extends Model
{
    protected $fillable = [
        'violation_id',
        'recipient_id',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    /**
     * Get the violation
     */
    public function violation()
    {
        return $this->belongsTo(SellerViolation::class, 'violation_id');
    }

    /**
     * Get the recipient (admin/employee)
     */
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Get unread notifications for a user
     */
    public static function getUnreadForUser($userId)
    {
        return self::where('recipient_id', $userId)
            ->where('is_read', false)
            ->with(['violation.seller', 'violation.rule'])
            ->latest()
            ->get();
    }
}
