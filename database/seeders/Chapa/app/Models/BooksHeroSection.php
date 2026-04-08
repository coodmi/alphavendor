<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksHeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'rating_score',
        'rating_count',
        'fsc_text',
        'slider_images',
    ];

    protected $casts = [
        'slider_images' => 'array',
        'rating_score' => 'decimal:1',
    ];

    /**
     * Get the rating as stars (for display).
     */
    public function getRatingStarsAttribute()
    {
        $fullStars = (int) floor((float) $this->rating_score);
        $halfStar = ((float) $this->rating_score - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;

        $stars = str_repeat('★', $fullStars);
        if ($halfStar) {
            $stars .= '☆';
        }
        $stars .= str_repeat('☆', $emptyStars);

        return $stars;
    }
}
