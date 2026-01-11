<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'profile_image',
        'certifications',
        'exporter_rating',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'certifications' => 'array',
            'exporter_rating' => 'decimal:2',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is retailer
     */
    public function isRetailer(): bool
    {
        return $this->role === 'retailer';
    }

    /**
     * Check if user is wholesaler
     */
    public function isWholesaler(): bool
    {
        return $this->role === 'wholesaler';
    }

    /**
     * Check if user is exporter
     */
    public function isExporter(): bool
    {
        return $this->role === 'exporter';
    }

    /**
     * Check if user is normal user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get role applications for this user
     */
    public function roleApplications()
    {
        return $this->hasMany(\App\Models\RoleApplication::class);
    }

    /**
     * Get pending role application
     */
    public function pendingRoleApplication()
    {
        return $this->hasOne(\App\Models\RoleApplication::class)->where('status', 'pending');
    }

    /**
     * Get products owned by this vendor
     */
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'vendor_id');
    }

    /**
     * Get orders for this vendor's products
     */
    public function orders()
    {
        return $this->hasManyThrough(\App\Models\Order::class, \App\Models\Product::class, 'vendor_id', 'id', 'id', 'id');
    }

    /**
     * Get notifications for this user
     */
    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    /**
     * Get chat conversations for this user
     */
    public function chatConversations()
    {
        return $this->hasMany(\App\Models\ChatConversation::class);
    }

    /**
     * Get chat messages sent by this user
     */
    public function chatMessages()
    {
        return $this->hasMany(\App\Models\ChatMessage::class);
    }
}
