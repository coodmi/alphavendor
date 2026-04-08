<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopHeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'subtitle',
        'title',
        'description',
        'badges',
        'cover_image',
        'stat1_label', 'stat1_value', 'stat1_sublabel',
        'stat2_label', 'stat2_value', 'stat2_sublabel',
        'stat3_label', 'stat3_value', 'stat3_sublabel',
        'stat4_label', 'stat4_value', 'stat4_sublabel',
    ];

    protected $casts = [
        'badges' => 'array',
    ];
}
