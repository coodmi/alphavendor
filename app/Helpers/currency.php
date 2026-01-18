<?php

if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        // You can later make this dynamic from DB or settings
        // For now, use BDT if set in .env or fallback to $
        $symbol = config('app.currency_symbol', '$');
        return $symbol;
    }
}
