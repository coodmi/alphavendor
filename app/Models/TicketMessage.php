<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachments',
        'is_internal',
        'is_staff',
        'read_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_internal' => 'boolean',
        'is_staff' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($message) {
            $ticket = $message->ticket;
            $ticket->increment('response_count');
            $ticket->update(['last_activity_at' => now()]);
            
            if ($message->is_staff && !$ticket->first_response_at) {
                $ticket->update(['first_response_at' => now()]);
            }
        });
    }

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function markAsRead()
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
    }
}
