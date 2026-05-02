<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancePaymentSetting extends Model
{
    protected $fillable = ['advance_percentage', 'is_mandatory', 'description'];

    protected $casts = ['is_mandatory' => 'boolean'];

    /** Singleton getter */
    public static function getSettings(): self
    {
        $s = self::first();
        if (!$s) {
            $s = self::create([
                'advance_percentage' => 20.00,
                'is_mandatory'       => true,
                'description'        => 'An advance payment is required to confirm your wholesale/import order.',
            ]);
        }
        return $s;
    }
}
