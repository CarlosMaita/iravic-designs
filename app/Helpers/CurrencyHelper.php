<?php

namespace App\Helpers;

use App\Services\ExchangeRateService;

class CurrencyHelper
{
    /**
     * Convert price from USD to VES using current exchange rate
     *
     * @param float $usdPrice
     * @return float
     */
    public static function convertToVES(float $usdPrice): float
    {
        $exchangeRateService = app(ExchangeRateService::class);
        return $exchangeRateService->convertToVES($usdPrice);
    }

    /**
     * Convert price from VES to USD using current exchange rate
     *
     * @param float $vesPrice
     * @return float
     */
    public static function convertToUSD(float $vesPrice): float
    {
        $exchangeRateService = app(ExchangeRateService::class);
        return $exchangeRateService->convertToUSD($vesPrice);
    }

    /**
     * Format price for display based on currency
     *
     * @param float $price
     * @param string $currency ('USD' or 'VES')
     * @param bool $includeSymbol
     * @return string
     */
    public static function formatPrice(float $price, string $currency = 'USD', bool $includeSymbol = true): string
    {
        if ($currency === 'VES') {
            $formatted = number_format($price, 2, ',', '.');
            return $includeSymbol ? 'Bs. ' . $formatted : $formatted;
        } else {
            $formatted = number_format($price, 2, '.', ',');
            return $includeSymbol ? '$' . $formatted : $formatted;
        }
    }

    /**
     * Get currency symbol
     *
     * @param string $currency
     * @return string
     */
    public static function getCurrencySymbol(string $currency): string
    {
        return $currency === 'VES' ? 'Bs.' : '$';
    }

    /**
     * Get current exchange rate
     *
     * @return float
     */
    public static function getCurrentExchangeRate(): float
    {
        $exchangeRateService = app(ExchangeRateService::class);
        return $exchangeRateService->getCurrentRate();
    }

    /**
     * Display price in both currencies
     *
     * @param float $usdPrice
     * @return array
     */
    public static function getBothPrices(float $usdPrice): array
    {
        $vesPrice = self::convertToVES($usdPrice);
        
        return [
            'usd' => [
                'amount' => $usdPrice,
                'formatted' => self::formatPrice($usdPrice, 'USD'),
                'currency' => 'USD'
            ],
            'ves' => [
                'amount' => $vesPrice,
                'formatted' => self::formatPrice($vesPrice, 'VES'),
                'currency' => 'VES'
            ]
        ];
    }

    /**
     * Javascript object with currency data for frontend use
     *
     * @return string
     */
    public static function getJavascriptData(): string
    {
        $rate = self::getCurrentExchangeRate();
        
        return json_encode([
            'exchangeRate' => $rate,
            'enabled' => \App\Models\Config::isCurrencyModuleEnabled(),
            'symbols' => [
                'USD' => '$',
                'VES' => 'Bs.'
            ],
            'decimals' => [
                'USD' => 2,
                'VES' => 2
            ]
        ]);
    }
}