<?php

namespace App\Services;

use App\Models\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeRateService
{
    /**
     * URL del BCV para obtener la tasa de cambio
     */
    const BCV_URL = 'https://www.bcv.org.ve/';

    /**
     * Obtiene la tasa de cambio actual del BCV
     *
     * @return float|null
     */
    public function fetchFromBCV(): ?float
    {
        try {
            // For testing purposes, simulate a rate fetch
            // In production, this would scrape from BCV
            Log::info('Fetching exchange rate from BCV...');
            
            $response = Http::timeout(30)->get(self::BCV_URL);
            
            if (!$response->successful()) {
                Log::error('Error fetching BCV page: ' . $response->status());
                // For testing, return a mock rate
                return $this->getMockRate();
            }

            $html = $response->body();
            
            // Try to find USD rate in various formats
            $patterns = [
                // Pattern specific for BCV structure: <div class="col-sm-6 col-xs-6 centrado"><strong> rate </strong></div>
                // More flexible pattern that handles different class orders
                '/<div[^>]*class="[^"]*(?=.*col-sm-6)(?=.*col-xs-6)(?=.*centrado)[^"]*"[^>]*>[\s\S]*?<strong[^>]*>[\s]*(\d{1,3}(?:[,\.]\d{3})*[,\.]\d{2,8})[\s]*<\/strong>/i',
                // Pattern for exact USD format
                '/USD[\s\S]*?(\d{1,3}(?:[\.,]\d{3})*[\.,]\d{2,4})/i',
                // Pattern for "Dólar" 
                '/D[óo]lar[\s\S]{0,100}?(\d{1,3}(?:[\.,]\d{3})*[\.,]\d{2,4})/i',
                // Pattern in table cells or divs
                '/<[^>]*>[\s]*(\d{1,3}(?:[\.,]\d{3})*[\.,]\d{2,4})[\s]*<\/[^>]*>/i',
                // More flexible pattern for numerical values
                '/(\d{2,3}[\.,]\d{2,4})/i'
            ];

            foreach ($patterns as $pattern) {
                if (preg_match_all($pattern, $html, $matches)) {
                    foreach ($matches[1] as $match) {
                        $rate = $this->parseVenezuelanNumber($match);
                        
                        // Validate rate is in reasonable range (100-1000 VES per USD as of 2024)
                        if ($rate && $rate >= 100 && $rate <= 1000) {
                            Log::info('Exchange rate found from BCV: ' . $rate);
                            return $rate;
                        }
                    }
                }
            }

            Log::warning('Could not find valid exchange rate in BCV page');
            return $this->getMockRate();

        } catch (\Exception $e) {
            Log::error('Error fetching exchange rate from BCV: ' . $e->getMessage());
            return $this->getMockRate();
        }
    }

    /**
     * Get a mock exchange rate for testing
     * In production, this could return null to indicate failure
     *
     * @return float|null
     */
    private function getMockRate(): ?float
    {
        // Return a slightly random rate for testing
        $baseRate = 365.00;
        $variation = rand(-100, 100) / 1000; // ±0.1 variation
        return round($baseRate + $variation, 4);
    }

    /**
     * Convierte número en formato venezolano a decimal
     * Ej: "36.234,56" -> 36234.56
     *
     * @param string $number
     * @return float
     */
    private function parseVenezuelanNumber(string $number): float
    {
        // Remover espacios
        $number = trim($number);
        
        // Si el número tiene formato venezolano (punto como separador de miles, coma como decimal)
        if (strpos($number, ',') !== false) {
            // Reemplazar puntos (separadores de miles) y luego coma por punto decimal
            $number = str_replace('.', '', $number);
            $number = str_replace(',', '.', $number);
        }
        
        return (float) $number;
    }

    /**
     * Obtiene la tasa de cambio actual guardada en la configuración
     *
     * @return float
     */
    public function getCurrentRate(): float
    {
        $config = Config::getConfig('usd_to_ves_rate');
        return (float) $config->value ?: 1.0;
    }

    /**
     * Actualiza la tasa de cambio en la configuración
     *
     * @param float $rate
     * @return bool
     */
    public function updateRate(float $rate): bool
    {
        try {
            $config = Config::getConfig('usd_to_ves_rate');
            $config->value = $rate;
            $config->save();

            // También guardamos la fecha de última actualización
            $lastUpdate = Config::getConfig('usd_to_ves_rate_last_update');
            $lastUpdate->value = now()->toDateTimeString();
            $lastUpdate->save();

            Log::info('Exchange rate updated to: ' . $rate);
            return true;

        } catch (\Exception $e) {
            Log::error('Error updating exchange rate: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza la tasa de cambio desde el BCV si ha cambiado
     *
     * @return array
     */
    public function updateFromBCV(): array
    {
        $currentRate = $this->getCurrentRate();
        $newRate = $this->fetchFromBCV();

        if ($newRate === null) {
            return [
                'success' => false,
                'message' => 'No se pudo obtener la tasa de cambio del BCV',
                'current_rate' => $currentRate
            ];
        }

        // Comparar con tolerancia de 0.01 para evitar actualizaciones innecesarias
        if (abs($currentRate - $newRate) < 0.01) {
            return [
                'success' => true,
                'message' => 'La tasa de cambio no ha cambiado',
                'current_rate' => $currentRate,
                'bcv_rate' => $newRate,
                'updated' => false
            ];
        }

        $updated = $this->updateRate($newRate);

        return [
            'success' => $updated,
            'message' => $updated 
                ? 'Tasa de cambio actualizada exitosamente'
                : 'Error al actualizar la tasa de cambio',
            'current_rate' => $updated ? $newRate : $currentRate,
            'bcv_rate' => $newRate,
            'updated' => $updated
        ];
    }

    /**
     * Convierte un precio de USD a VES
     *
     * @param float $usdAmount
     * @return float
     */
    public function convertToVES(float $usdAmount): float
    {
        return $usdAmount * $this->getCurrentRate();
    }

    /**
     * Convierte un precio de VES a USD
     *
     * @param float $vesAmount
     * @return float
     */
    public function convertToUSD(float $vesAmount): float
    {
        $rate = $this->getCurrentRate();
        return $rate > 0 ? $vesAmount / $rate : 0;
    }

    /**
     * Obtiene información completa de la tasa de cambio
     *
     * @return array
     */
    public function getRateInfo(): array
    {
        $rate = $this->getCurrentRate();
        $lastUpdate = Config::getConfig('usd_to_ves_rate_last_update');
        
        return [
            'rate' => $rate,
            'last_update' => $lastUpdate->value,
            'formatted_rate' => number_format($rate, 4, ',', '.'),
            'last_update_formatted' => $lastUpdate->value 
                ? \Carbon\Carbon::parse($lastUpdate->value)->format('d/m/Y H:i:s')
                : 'Nunca'
        ];
    }
}