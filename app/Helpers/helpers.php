<?php

if (!function_exists('format_currency')) {
    /**
     * Format currency to VNĐ with thousands separator
     * 
     * @param float|int $amount
     * @return string
     */
    function format_currency($amount)
    {
        return number_format($amount, 0, ',', '.') . ' VNĐ';
    }
}

if (!function_exists('format_currency_short')) {
    /**
     * Format currency to short format (tỷ, triệu, nghìn)
     * Smart decimal display: hide if 0, show up to 2 decimals if needed
     * 
     * @param float|int $amount
     * @param int $maxDecimals Maximum decimals to show (default 2)
     * @return string
     */
    function format_currency_short($amount, $maxDecimals = 2)
    {
        if ($amount >= 1000000000) {
            $value = $amount / 1000000000;
            $formatted = rtrim(rtrim(number_format($value, $maxDecimals, ',', '.'), '0'), ',');
            return $formatted . ' tỷ';
        } elseif ($amount >= 1000000) {
            $value = $amount / 1000000;
            $formatted = rtrim(rtrim(number_format($value, $maxDecimals, ',', '.'), '0'), ',');
            return $formatted . ' triệu';
        } elseif ($amount >= 1000) {
            $value = $amount / 1000;
            $formatted = rtrim(rtrim(number_format($value, $maxDecimals, ',', '.'), '0'), ',');
            return $formatted . ' nghìn';
        }
        return number_format($amount, 0, ',', '.') . ' VNĐ';
    }
}

if (!function_exists('format_number')) {
    /**
     * Format number with thousands separator
     * 
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    function format_number($number, $decimals = 0)
    {
        return number_format($number, $decimals, ',', '.');
    }
}

if (!function_exists('format_percentage')) {
    /**
     * Format percentage
     * 
     * @param float $number
     * @param int $decimals
     * @return string
     */
    function format_percentage($number, $decimals = 1)
    {
        return number_format($number, $decimals, ',', '.') . '%';
    }
}
