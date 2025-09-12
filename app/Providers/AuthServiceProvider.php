<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Brand'                  => 'App\Policies\BrandPolicy',
        'App\Models\Category'               => 'App\Policies\CategoryPolicy',
        'App\Models\Config'                 => 'App\Policies\ConfigPolicy',
        'App\Models\Customer'               => 'App\Policies\CustomerPolicy',
        'App\Models\Product'                => 'App\Policies\ProductPolicy',
        'App\Models\ProductImage'           => 'App\Policies\ProductImagePolicy',
        'App\Models\ProductStockTransfer'   => 'App\Policies\ProductStockTransferPolicy',
        'App\Models\SpecialOffer'           => 'App\Policies\SpecialOfferPolicy',
        'App\Models\Store'                  => 'App\Policies\StorePolicy',
        'App\User'                          => 'App\Policies\UserPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Remove all permission-based gates since we're removing the permission system
    }
}
