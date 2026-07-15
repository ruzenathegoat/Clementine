<?php

namespace App\Services;

class CurrencyService
{
    /**
     * Format a given amount based on the admin's currency preference in the session.
     * Default is USD. If IDR, multiplies by a fixed rate of 16000.
     *
     * @param float|int $amount
     * @param bool $raw Return raw calculated value instead of formatted string
     * @return string|float|int
     */
    public static function format($amount, $raw = false)
    {
        $currency = session('admin_currency', 'USD');
        
        if ($currency === 'IDR') {
            $value = $amount * 16000;
            if ($raw) return $value;
            return 'Rp ' . number_format($value, 0, ',', '.');
        }

        if ($raw) return $amount;
        return '$' . number_format($amount, 2, '.', ',');
    }

    /**
     * Get current currency code
     */
    public static function getCode()
    {
        return session('admin_currency', 'USD');
    }
}
