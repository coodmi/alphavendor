<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'support_tickets';

    protected $fillable = [
        'ticket_number',
        'user_id',
        'category_id',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_to',
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
            if (!$ticket->ticket_number) {
                $ticket->ticket_number = 'TKT-' . strtoupper(uniqid());
            }
            $ticket->last_activity_at = now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function replies()
    {
        return $this->messages();
    }

    public function latestReply()
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

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'open' => 'blue',
            'in_progress' => 'yellow',
            'pending_customer' => 'orange',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'low' => 'green',
            'normal' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray'
        };
    }
}
