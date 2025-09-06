<?php

namespace App\Providers;

use App\Repositories\BoxRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CustomerRepositoryInterface;
use App\Repositories\DebtRepositoryInterface;
use App\Repositories\DebtOrderProductRepositoryInterface;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\OperationRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\OrderProductRepositoryInterface;
use App\Repositories\PermissionRepositoryInterface;
use App\Repositories\ProductStockHistoryRepositoryInterface;
use App\Repositories\ProductStockTransferRepositoryInterface;
use App\Repositories\RefundRepositoryInterface;
use App\Repositories\RefundProductRepositoryInterface;
use App\Repositories\RoleRepositoryInterface;
use App\Repositories\SpendingRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\BoxRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\DebtRepository;
use App\Repositories\Eloquent\DebtOrderProductRepository;
use App\Repositories\Eloquent\OperationRepository;
use App\Repositories\Eloquent\OrderRepository;
use App\Repositories\Eloquent\OrderProductRepository;
use App\Repositories\Eloquent\PermissionRepository;
use App\Repositories\Eloquent\ProductStockHistoryRepository;
use App\Repositories\Eloquent\ProductStockTransferRepository;
use App\Repositories\Eloquent\RefundRepository;
use App\Repositories\Eloquent\RefundProductRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\SpendingRepository;
use App\Repositories\Eloquent\UserRepository;
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
        $this->app->bind(DebtRepository::class, DebtRepositoryInterface::class);
        $this->app->bind(DebtOrderProductRepository::class, DebtOrderProductRepositoryInterface::class);
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(OperationRepository::class, OperationRepositoryInterface::class);
        $this->app->bind(OrderRepository::class, OrderRepositoryInterface::class);
        $this->app->bind(OrderProductRepository::class, OrderProductRepositoryInterface::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(ProductStockHistoryRepositoryInterface::class, ProductStockHistoryRepository::class);
        $this->app->bind(ProductStockTransferRepositoryInterface::class, ProductStockTransferRepository::class);
        $this->app->bind(RefundRepositoryInterface::class, RefundRepository::class);
        $this->app->bind(RefundProductRepositoryInterface::class, RefundProductRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(SpendingRepositoryInterface::class, SpendingRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
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
