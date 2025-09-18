<?php

namespace App\Http\Controllers\admin\config;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    private $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Display the exchange rate management page
     */
    public function index()
    {        $rateInfo = $this->exchangeRateService->getRateInfo();
        $currencyModuleEnabled = Config::getConfig('currency_module_enabled');
        
        return view('dashboard.config.exchange-rate.index', compact('rateInfo', 'currencyModuleEnabled'));
    }

    /**
     * Update exchange rate from BCV
     */
    public function updateFromBCV()
    {        $result = $this->exchangeRateService->updateFromBCV();

        if ($result['success']) {
            if ($result['updated'] ?? true) {
                flash('Tasa de cambio actualizada exitosamente: ' . number_format($result['current_rate'], 4, ',', '.') . ' VES por USD')->success();
            } else {
                flash('La tasa de cambio no ha cambiado: ' . number_format($result['current_rate'], 4, ',', '.') . ' VES por USD')->info();
            }
        } else {
            flash('Error al actualizar la tasa de cambio: ' . $result['message'])->error();
        }

        return redirect()->back();
    }

    /**
     * Update exchange rate manually
     */
    public function updateManual(Request $request)
    {        $request->validate([
            'exchange_rate' => 'required|numeric|min:0.01|max:999999.9999'
        ], [
            'exchange_rate.required' => 'La tasa de cambio es requerida',
            'exchange_rate.numeric' => 'La tasa de cambio debe ser un número',
            'exchange_rate.min' => 'La tasa de cambio debe ser mayor a 0.01',
            'exchange_rate.max' => 'La tasa de cambio no puede ser mayor a 999,999.9999'
        ]);

        $rate = (float) $request->input('exchange_rate');
        
        if ($this->exchangeRateService->updateRate($rate)) {
            flash('Tasa de cambio actualizada manualmente: ' . number_format($rate, 4, ',', '.') . ' VES por USD')->success();
        } else {
            flash('Error al actualizar la tasa de cambio manualmente')->error();
        }

        return redirect()->back();
    }

    /**
     * Get current exchange rate as JSON
     */
    public function getCurrentRate()
    {
        return response()->json([
            'rate' => $this->exchangeRateService->getCurrentRate(),
            'info' => $this->exchangeRateService->getRateInfo()
        ]);
    }

    /**
     * Toggle currency module enabled/disabled
     */
    public function toggleCurrencyModule(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean'
        ]);

        $config = Config::getConfig('currency_module_enabled');
        $config->value = $request->input('enabled') ? '1' : '0';
        $config->save();

        $status = $request->input('enabled') ? 'habilitado' : 'deshabilitado';
        flash("Módulo de cambio de moneda {$status} exitosamente.")->success();

        return redirect()->back();
    }
}
