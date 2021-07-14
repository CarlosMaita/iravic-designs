<?php

namespace App\Providers;

use App\Services\Menu\MenuServices;
use Illuminate\Http\Request;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $menuService = new MenuServices();
        $url = $request->getPathInfo() . ($request->getQueryString() ? '?' . $request->getQueryString() : '');
        View::share('menuService', $menuService);
        View::share('url', $url);
    }
}
