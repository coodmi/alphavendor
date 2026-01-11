<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'message',
        'attachment_path',
        'is_admin',
        'read_at',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the conversation this message belongs to.
     */
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    /**
     * Get the user who sent the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if message is read.
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Mark message as read.
     */
    public function markAsRead()
    {
        if (!$this->isRead()) {
            $this->read_at = now();
            $this->save();
        }
    }
}
