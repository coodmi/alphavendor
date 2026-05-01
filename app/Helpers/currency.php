<?php

if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        return config('app.currency_symbol', '৳');
    }
}

if (!function_exists('currency')) {
    function currency($amount, $decimals = 2)
    {
        $symbol = currency_symbol();
        return $symbol . number_format((float) $amount, $decimals);
    }
}
