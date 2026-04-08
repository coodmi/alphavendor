<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemOption extends Model
{
    protected $fillable = [
        'order_item_id',
        'option_name',
        'option_type',
        'selected_value_label',
        'price_modifier',
        'custom_input',
    ];

    protected $casts = [
        'price_modifier' => 'decimal:2',
    ];

    /**
     * Get the order item this option belongs to.
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    /**
     * Get formatted price with Bangla currency symbol.
     */
    public function getFormattedPriceAttribute(): string
    {
        $modifier = $this->price_modifier;
        
        if ($modifier == 0) {
            return 'Free';
        }
        
        if ($modifier > 0) {
            return '+৳' . number_format($modifier, 2);
        }
        
        return '-৳' . number_format(abs($modifier), 2);
    }
}
