<?php

if (!function_exists('currency')) {
    /**
     * Format price with BDT currency symbol
     *
     * @param float $amount
     * @param int $decimals
     * @return string
     */
    function currency($amount, $decimals = 2)
    {
        return '৳' . number_format($amount, $decimals);
    }
}

if (!function_exists('currency_symbol')) {
    /**
     * Get currency symbol
     *
     * @return string
     */
    function currency_symbol()
    {
        return '৳';
    }
}

if (!function_exists('currency_code')) {
    /**
     * Get currency code
     *
     * @return string
     */
    function currency_code()
    {
        return 'BDT';
    }
}
