<?php

namespace App\Http\View\Composers;

use App\Services\ExchangeRateService;
use Illuminate\View\View;

class ExchangeRateComposer
{
    /**
     * The exchange rate service implementation.
     *
     * @var ExchangeRateService
     */
    protected $exchangeRateService;

    /**
     * Create a new exchange rate composer.
     *
     * @param ExchangeRateService $exchangeRateService
     */
    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $exchangeRate = $this->exchangeRateService->getCurrentRate();
        $exchangeRateInfo = $this->exchangeRateService->getRateInfo();
        
        $view->with([
            'exchangeRate' => $exchangeRate,
            'exchangeRateInfo' => $exchangeRateInfo
        ]);
    }
}