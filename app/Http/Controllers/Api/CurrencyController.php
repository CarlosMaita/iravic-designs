<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CurrencyHelper;
use App\Http\Controllers\Controller;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    private $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Get current exchange rate information
     */
    public function getExchangeRate()
    {
        return response()->json([
            'rate' => $this->exchangeRateService->getCurrentRate(),
            'info' => $this->exchangeRateService->getRateInfo(),
            'currency_data' => json_decode(CurrencyHelper::getJavascriptData(), true)
        ]);
    }

    /**
     * Convert prices between currencies
     */
    public function convertPrice(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'from' => 'required|in:USD,VES',
            'to' => 'required|in:USD,VES'
        ]);

        $amount = (float) $request->input('amount');
        $from = $request->input('from');
        $to = $request->input('to');

        if ($from === $to) {
            $convertedAmount = $amount;
        } elseif ($from === 'USD' && $to === 'VES') {
            $convertedAmount = CurrencyHelper::convertToVES($amount);
        } else {
            $convertedAmount = CurrencyHelper::convertToUSD($amount);
        }

        return response()->json([
            'original' => [
                'amount' => $amount,
                'currency' => $from,
                'formatted' => CurrencyHelper::formatPrice($amount, $from)
            ],
            'converted' => [
                'amount' => $convertedAmount,
                'currency' => $to,
                'formatted' => CurrencyHelper::formatPrice($convertedAmount, $to)
            ],
            'rate' => $this->exchangeRateService->getCurrentRate()
        ]);
    }

    /**
     * Get prices in both currencies
     */
    public function getBothPrices(Request $request)
    {
        $request->validate([
            'usd_price' => 'required|numeric|min:0'
        ]);

        $usdPrice = (float) $request->input('usd_price');
        $prices = CurrencyHelper::getBothPrices($usdPrice);

        return response()->json($prices);
    }
}
