<?php

namespace App\Helpers;

class FormatHelper
{
    /**
     * Formatea un número como una moneda.
     *
     * @param float $amount
     * @param string $currencySymbol
     * @return string
     */
    public static function formatCurrency($amount, $currencySymbol = '$')
    {
        return $currencySymbol . number_format($amount, 2, '.', ',');
    }
}