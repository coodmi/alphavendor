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
        'mobile_number', // Ensure this is fillable
        'phone',
        'password',
        'role',
        'vendor_badge_id',
        'employee_role_id',
        'status',
        'notes',
        'profile_image',
        'certifications',
        'exporter_rating',
        'dashboard_modules',
        'permissions',
        'verification_status',
        'verification_submitted_at',
        'verification_reviewed_at',
        'verification_reviewed_by',
        'rejection_reason',
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
            'dashboard_modules' => 'array',
            'permissions' => 'array',
            'verification_submitted_at' => 'datetime',
            'verification_reviewed_at' => 'datetime',
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
     * Check if user is employee (moderator)
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /**
     * Check if user is manager
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Check if user is supervisor
     */
    public function isSupervisor(): bool
    {
        return $this->role === 'supervisor';
    }

    /**
     * Check if user is staff (employee, manager, or supervisor)
     */
    public function isStaff(): bool
    {
        return in_array($this->role, ['employee', 'manager', 'supervisor']);
    }


    /**
     * Check if user is normal user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get vendor badge
     */
    public function vendorBadge()
    {
        return $this->belongsTo(VendorBadge::class);
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
     * Get app notifications for this user (custom notifications, not Laravel's built-in)
     */
    public function appNotifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    /**
     * Get wishlist items for this user
     */
    public function wishlists()
    {
        return $this->hasMany(\App\Models\Wishlist::class);
    }

    /**
     * Get chat conversations for this user
     */
    public function chatConversations()
    {
        return $this->hasMany(\App\Models\ChatConversation::class);
    }

    /**
     * Get addresses for this user
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    /**
     * Get default address for this user
     */
    public function defaultAddress()
    {
        return $this->hasOne(UserAddress::class)->where('is_default', true);
    }

    /**
     * Get chat messages sent by this user
     */
    public function chatMessages()
    {
        return $this->hasMany(\App\Models\ChatMessage::class);
    }

    /**
     * Get verification documents for this user
     */
    public function verificationDocuments()
    {
        return $this->hasMany(\App\Models\VerificationDocument::class);
    }

    /**
     * Get the admin who reviewed verification
     */
    public function verificationReviewer()
    {
        return $this->belongsTo(User::class, 'verification_reviewed_by');
    }

    /**
     * Get the employee role for this user
     */
    public function employeeRole()
    {
        return $this->belongsTo(EmployeeRole::class, 'employee_role_id');
    }


    /**
     * Check if user needs verification
     */
    public function needsVerification(): bool
    {
        return in_array($this->role, ['retailer', 'wholesaler', 'exporter', 'importer']);
    }

    /**
     * Check if user is verified
     */
    public function isVerified(): bool
    {
        // Customers are auto-verified
        if ($this->role === 'user') {
            return true;
        }

        return $this->verification_status === 'verified';
    }

    /**
     * Check if verification is pending
     */
    public function isVerificationPending(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Check if verification was rejected
     */
    public function isVerificationRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }

    /**
     * Check if user has submitted verification documents
     */
    public function hasSubmittedVerification(): bool
    {
        return $this->verification_submitted_at !== null;
    }

    /**
     * Get required document types based on role
     */
    public function getRequiredDocumentTypes(): array
    {
        if ($this->role === 'retailer') {
            return ['nid_front', 'nid_back', 'personal_photo', 'shop_profile'];
        }

        if (in_array($this->role, ['wholesaler', 'exporter', 'importer'])) {
            return ['nid_front', 'nid_back', 'trade_license', 'personal_photo', 'shop_profile'];
        }

        return [];
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments(): bool
    {
        $required = $this->getRequiredDocumentTypes();
        $uploaded = $this->verificationDocuments()->pluck('document_type')->toArray();

        foreach ($required as $type) {
            if (!in_array($type, $uploaded)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get verification status badge color
     */
    public function getVerificationBadgeColor(): string
    {
        return match($this->verification_status) {
            'verified' => '#10b981',
            'pending' => '#f59e0b',
            'rejected' => '#ef4444',
            'unverified' => '#6b7280',
            default => '#6b7280',
        };
    }

    /**
     * Get verification status label
     */
    public function getVerificationStatusLabel(): string
    {
        return match($this->verification_status) {
            'verified' => 'Verified',
            'pending' => 'Pending Review',
            'rejected' => 'Rejected',
            'unverified' => 'Unverified',
            default => 'Unknown',
        };
    }

    /**
     * Get vendor's average rating based on product reviews
     */
    public function getVendorRating()
    {
        return \App\Models\Review::whereHas('product', function($q) {
            $q->where('vendor_id', $this->id);
        })->where('status', 'approved')->avg('rating') ?? 0;
    }

    /**
     * Get vendor's total review count
     */
    public function getVendorReviewCount()
    {
        return \App\Models\Review::whereHas('product', function($q) {
            $q->where('vendor_id', $this->id);
        })->where('status', 'approved')->count();
    }

    /**
     * Get vendor's product count
     */
    public function getVendorProductCount()
    {
        return $this->products()->count();
    }

    /**
     * Get vendor's total sales count
     */
    public function getVendorSalesCount()
    {
        return \App\Models\Order::where('vendor_id', $this->id)
            ->whereIn('status', ['delivered', 'completed'])
            ->count();
    }
}
