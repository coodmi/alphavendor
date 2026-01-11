<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'assigned_to',
        'subject',
        'description',
        'priority',
        'status',
        'first_response_at',
        'resolved_at',
        'closed_at',
        'last_activity_at',
        'response_count',
        'satisfaction_rating',
        'satisfaction_comment',
    ];

    protected $casts = [
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'satisfaction_rating' => 'decimal:1',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            $ticket->ticket_number = 'TKT-' . strtoupper(uniqid());
            $ticket->last_activity_at = now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(TicketMessage::class, 'ticket_id')->latestOfMany();
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    public function close()
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now()
        ]);
    }

    public function resolve()
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now()
        ]);
    }
}
