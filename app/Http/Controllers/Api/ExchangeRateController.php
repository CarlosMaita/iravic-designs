<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;

class ExchangeRateController extends Controller
{
    protected $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Get current exchange rate
     *
     * @return JsonResponse
     */
    public function getCurrentRate(): JsonResponse
    {
        try {
            $rateInfo = $this->exchangeRateService->getRateInfo();
            
            return response()->json([
                'success' => true,
                'rate' => $rateInfo['rate'],
                'formatted_rate' => $rateInfo['formatted_rate'],
                'last_update' => $rateInfo['last_update_formatted'],
                'data' => $rateInfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error obteniendo la tasa de cambio',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Convert amount between currencies
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function convert(\Illuminate\Http\Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'from' => 'required|in:USD,VES',
            'to' => 'required|in:USD,VES'
        ]);

        try {
            $amount = $request->amount;
            $from = $request->from;
            $to = $request->to;

            if ($from === $to) {
                return response()->json([
                    'success' => true,
                    'original_amount' => $amount,
                    'converted_amount' => $amount,
                    'from_currency' => $from,
                    'to_currency' => $to,
                    'exchange_rate' => 1
                ]);
            }

            $convertedAmount = $from === 'USD' 
                ? $this->exchangeRateService->convertToVES($amount)
                : $this->exchangeRateService->convertToUSD($amount);

            return response()->json([
                'success' => true,
                'original_amount' => $amount,
                'converted_amount' => round($convertedAmount, 2),
                'from_currency' => $from,
                'to_currency' => $to,
                'exchange_rate' => $this->exchangeRateService->getCurrentRate()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en la conversión de moneda',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}