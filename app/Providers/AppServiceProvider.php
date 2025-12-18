<?php

namespace App\Providers;

use App\Services\Menu\MenuServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Services\ExchangeRateService::class, function ($app) {
            return new \App\Services\ExchangeRateService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        date_default_timezone_set('America/Araguaina');
        Schema::defaultStringLength(191);
        $menuService = new MenuServices();
        $url = $request->getPathInfo() . ($request->getQueryString() ? '?' . $request->getQueryString() : '');
        View::share('menuService', $menuService);
        View::share('url', $url);

        // Share submenu links globally for ecommerce views
        try {
            $submenuLinks = \App\Models\SubmenuLink::active()->ordered()->get();
            View::share('submenuLinks', $submenuLinks);
        } catch (\Exception $e) {
            // If table doesn't exist yet, use empty collection
            View::share('submenuLinks', collect());
        }

        // Register exchange rate view composer for ecommerce views
        View::composer([
            'ecommerce.*',
            'dashboard.config.exchange-rate.*'
        ], \App\Http\View\Composers\ExchangeRateComposer::class);
    }
}
