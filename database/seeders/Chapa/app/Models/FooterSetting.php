<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'company_name',
        'company_description',
        'phone',
        'email',
        'address',
        'service_links',
        'quick_links',
        'social_links',
        'newsletter_title',
        'newsletter_description',
        'copyright_text',
        'developer_name',
        'developer_url',
        'is_active'
    ];

    protected $casts = [
        'service_links' => 'array',
        'quick_links' => 'array',
        'social_links' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active footer settings
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get formatted footer data for frontend
     */
    public function getFormattedData()
    {
        return [
            'logo' => $this->logo,
            'company_info' => [
                'name' => $this->company_name,
                'description' => $this->company_description,
            ],
            'contact' => [
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
            ],
            'service_links' => $this->service_links ?? [],
            'quick_links' => $this->quick_links ?? [],
            'social_links' => $this->social_links ?? [],
            'newsletter' => [
                'title' => $this->newsletter_title,
                'description' => $this->newsletter_description,
            ],
            'copyright' => $this->copyright_text ?? '© ' . date('Y') . ' ' . $this->company_name . '. All rights reserved.',
            'developer' => [
                'name' => $this->developer_name,
                'url' => $this->developer_url,
            ]
        ];
    }
}
