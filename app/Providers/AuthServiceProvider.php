<?php

namespace App\Providers;

use App\User;
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
        'App\Models\Box'                    => 'App\Policies\BoxPolicy',
        'App\Models\Brand'                  => 'App\Policies\BrandPolicy',
        'App\Models\Category'               => 'App\Policies\CategoryPolicy',
        'App\Models\Config'                 => 'App\Policies\ConfigPolicy',
        'App\Models\Customer'               => 'App\Policies\CustomerPolicy',
        'App\Models\Order'                  => 'App\Policies\OrderPolicy',
        'App\Models\Payment'                => 'App\Policies\PaymentPolicy',
        'App\Models\Permission'             => 'App\Policies\PermissionPolicy',
        'App\Models\Product'                => 'App\Policies\ProductPolicy',
        'App\Models\ProductImage'           => 'App\Policies\ProductImagePolicy',
        'App\Models\ProductStockTransfer'   => 'App\Policies\ProductStockTransferPolicy',
        'App\Models\Role'                   => 'App\Policies\RolePolicy',
        'App\Models\Schedule'               => 'App\Policies\SchedulePolicy',
        'App\Models\Spending'               => 'App\Policies\SpendingPolicy',
        'App\Models\Visit'                  => 'App\Policies\VisitPolicy',
        'App\Models\Zone'                   => 'App\Policies\ZonePolicy',
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

        Gate::before(function (User $user) {
            if (!empty(array_intersect(array('superadmin', 'admin'), $user->roles->pluck('name')->toArray()))) {
                return true;
            }
        });

        // Gate::define('create-user', function ($user) {
        //     return $user->permissions()->contains('create-user');
        // });
    }
}
