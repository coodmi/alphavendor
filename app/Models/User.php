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
}
