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


    /**
     * Formatea un número de teléfono a un string numerico útil para comparaciones.
     *
     * @param string $phone
     * @return string
     */
    public static function formatPhoneNumber($phone)
    {
        $phone = str_replace([' ', '+', '-','(', ')' ], '', $phone);
        $phone = preg_replace('/^598/', '', $phone);
        $phone = preg_replace('/^0/', '', $phone);

        return $phone;
    }

    public static function formatDniNumber($dni)
    {
        // Remove any non-numeric characters
        $dni = preg_replace('/\D/', '', $dni);
        return $dni;
    }
}