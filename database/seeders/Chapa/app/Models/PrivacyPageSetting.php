<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivacyPageSetting extends Model
{
    protected $fillable = [
        'hero',
        'introduction',
        'section_1',
        'section_2',
        'section_3',
        'section_4',
        'section_5',
        'section_6',
        'contact_section',
    ];

    protected $casts = [
        'hero' => 'array',
        'introduction' => 'array',
        'section_1' => 'array',
        'section_2' => 'array',
        'section_3' => 'array',
        'section_4' => 'array',
        'section_5' => 'array',
        'section_6' => 'array',
        'contact_section' => 'array',
    ];
}
