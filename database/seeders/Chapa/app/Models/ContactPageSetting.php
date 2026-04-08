<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPageSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'info_title',
        'address',
        'phone',
        'whatsapp',
        'email',
        'hours',
        'map_embed_url',
        'form_title',
        'form_description',
        'form_submit_email',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    public function getFormattedData()
    {
        return [
            'hero' => [
                'title' => $this->hero_title,
                'subtitle' => $this->hero_subtitle,
                'description' => $this->hero_description,
            ],
            'info' => [
                'title' => $this->info_title,
                'address' => $this->address,
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'hours' => $this->hours,
            ],
            'map_embed_url' => $this->map_embed_url,
            'form' => [
                'title' => $this->form_title,
                'description' => $this->form_description,
                'submit_email' => $this->form_submit_email,
            ],
        ];
    }
}
