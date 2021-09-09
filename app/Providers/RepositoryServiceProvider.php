<?php

namespace App\Providers;

use App\Repositories\BoxRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\OrderProductRepositoryInterface;
use App\Repositories\PermissionRepositoryInterface;
use App\Repositories\ProductStockHistoryRepositoryInterface;
use App\Repositories\ProductStockTransferRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\ScheduleRepositoryInterface;
use App\Repositories\SpendingRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\VisitRepositoryInterface;
use App\Repositories\ZoneRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\BoxRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\OrderProductRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\ProductStockHistoryRepository;
use App\Repositories\Eloquent\ProductStockTransferRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\ScheduleRepository;
use App\Repositories\Eloquent\SpendingRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\VisitRepository;
use App\Repositories\Eloquent\ZoneRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BoxRepositoryInterface::class, BoxRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CustomerRepository::class, CustomerRepositoryInterface::class);
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(OrderRepository::class, OrderRepositoryInterface::class);
        $this->app->bind(OrderProductRepository::class, OrderProductRepositoryInterface::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ProductStockHistoryRepositoryInterface::class, ProductStockHistoryRepository::class);
        $this->app->bind(ProductStockTransferRepositoryInterface::class, ProductStockTransferRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
        $this->app->bind(SpendingRepositoryInterface::class, SpendingRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(VisitRepositoryInterface::class, VisitRepository::class);
        $this->app->bind(ZoneRepositoryInterface::class, ZoneRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
