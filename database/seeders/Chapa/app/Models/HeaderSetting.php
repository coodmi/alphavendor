<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'site_name',
        'phone',
        'whatsapp_number',
        'help_url',
        'search_placeholder',
        'navigation_links',
        'login_url',
        'register_url',
        'cart_url',
        'show_search',
        'show_phone',
        'show_help',
        'is_active'
    ];

    protected $casts = [
        'navigation_links' => 'array',
        'show_search' => 'boolean',
        'show_phone' => 'boolean',
        'show_help' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Get the active header settings
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get formatted header data for frontend
     */
    public function getFormattedData()
    {
        return [
            'logo' => $this->logo,
            'site_name' => $this->site_name,
            'phone' => $this->phone,
            'whatsapp_number' => $this->whatsapp_number,
            'help_url' => $this->help_url,
            'search_placeholder' => $this->search_placeholder,
            'navigation' => $this->navigation_links ?? [],
            'login_url' => $this->login_url,
            'register_url' => $this->register_url,
            'cart_url' => $this->cart_url,
            'show_search' => $this->show_search,
            'show_phone' => $this->show_phone,
            'show_help' => $this->show_help,
        ];
    }
}
