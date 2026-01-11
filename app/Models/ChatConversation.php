<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'subject',
        'status',
        'priority',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the user who initiated the conversation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin handling the conversation.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get all messages for this conversation.
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'conversation_id');
    }

    /**
     * Get the latest message.
     */
    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'conversation_id')->latestOfMany();
    }

    /**
     * Get unread messages count for a specific user.
     */
    public function unreadMessagesCount($userId)
    {
        return $this->messages()
            ->where('user_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
