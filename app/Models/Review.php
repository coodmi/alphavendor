<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'status',
        'is_verified_purchase',
        'helpful_votes',
        'reported_count',
        'admin_response',
        'admin_responded_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'helpful_votes' => 'integer',
        'reported_count' => 'integer',
        'admin_responded_at' => 'datetime',
    ];

    /**
     * Get the user that wrote the review
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product being reviewed
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order associated with this review
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for reported reviews
     */
    public function scopeReported($query)
    {
        return $query->where('status', 'reported');
    }

    /**
     * Check if review is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if review is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get star rating display
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}
