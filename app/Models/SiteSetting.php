<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_logo',
        'site_description',
        'footer_text',
        'footer_copyright',
        'developer_name',
        'developer_url',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'payment_logo_1',
        'payment_logo_2',
        'payment_logo_3',
        'contact_email',
        'contact_phone',
        'contact_address',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_branch',
        'bank_routing_number',
    ];

    /**
     * Get the site settings (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'site_name' => 'AlphaVendor',
                'footer_copyright' => '© ' . date('Y') . ' AlphaVendor. All rights reserved.',
                'developer_name' => 'Alphainno',
                'developer_url' => 'https://alphainno.com',
            ]);
        }
        
        return $settings;
    }
}
