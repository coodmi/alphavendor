<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OptionCategoryAssignment extends Model
{
    protected $fillable = [
        'option_id',
        'category_id',
        'applies_to_all',
    ];

    protected $casts = [
        'applies_to_all' => 'boolean',
    ];

    /**
     * Get the option this assignment belongs to.
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(GlobalServiceOption::class, 'option_id');
    }

    /**
     * Get the category this assignment belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }
}
