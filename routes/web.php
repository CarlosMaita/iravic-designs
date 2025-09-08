<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\CustomerLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

# Ecommerce Routes
Route::group(['namespace' => 'App\Http\Controllers\ecommerce'], function () {
    # Home
    Route::get('/', 'HomeController@index')->name('ecommerce.home');
    # Catalog
    Route::get('/catalogo', 'CatalogController@index')->name('ecommerce.catalog');
    # Category
Route::get('/categoria/{slug}', 'CatalogController@category')->name('ecommerce.categoria');
    # Product Detail
    Route::get('/producto/{slug}', 'CatalogController@show')->name('ecommerce.product.detail');
});

# Customer Authentication Routes
Route::get('ingresar',           [CustomerLoginController::class, 'showLoginForm'])->name('customer.login.form');
Route::post('customer/login',    [CustomerLoginController::class, 'login'])->name('customer.login');
Route::post('customer/logout',   [CustomerLoginController::class, 'logout'])->name('customer.logout');

# Customer Registration Routes
Route::get('registrarse',        [\App\Http\Controllers\Auth\CustomerRegisterController::class, 'showRegistrationForm'])->name('customer.register.form');
Route::post('customer/register', [\App\Http\Controllers\Auth\CustomerRegisterController::class, 'register'])->name('customer.register');

# Customer Protected Routes
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/e/dashboard', [\App\Http\Controllers\Ecommerce\CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/e/perfil', [\App\Http\Controllers\Ecommerce\CustomerDashboardController::class, 'profile'])->name('customer.profile');
    
    # Order Routes
    Route::get('/e/ordenes', [\App\Http\Controllers\Ecommerce\OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/e/ordenes/{order}', [\App\Http\Controllers\Ecommerce\OrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/e/ordenes/{order}/pagos', [\App\Http\Controllers\Ecommerce\OrderController::class, 'addPayment'])->name('customer.orders.add_payment');
});

# Order Creation Routes (public API for cart)
Route::post('/api/orders/create', [\App\Http\Controllers\Ecommerce\OrderController::class, 'create'])->name('api.orders.create');

# Customer authentication check endpoint (needs session access)
Route::get('/api/customer/auth-check', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'authenticated' => Auth::guard('customer')->check(),
        'customer' => Auth::guard('customer')->check() ? Auth::guard('customer')->user()->only(['id', 'name', 'email']) : null
    ]);
});


Route::group(['namespace' => 'App\Http\Controllers\admin', 'middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Route::get('/', 'HomeController@index')->name('admin.home')->middleware('redirect.home.role');
    #
    Route::get('mi-perfil', 'MyProfileController@index')->name('my-profile.index');
    #
    Route::get('mi-perfil/edit', 'MyProfileController@edit')->name('my-profile.edit');
    #
    Route::put('mi-perfil', 'MyProfileController@update')->name('my-profile.update');

    // Banner Resource
    Route::resource('banners', 'BannerController')->except('show');

    // Special Offers Resource
    Route::resource('ofertas-especiales', 'SpecialOfferController', [
        'as' => 'special-offers'
    ])->except('show');

    # Catalog Routes
    Route::group(['prefix' => 'catalogo', 'namespace' => 'catalog'], function () {
        Route::resource('categorias', 'CategoryController')->except('show');
        Route::resource('marcas', 'BrandController')->except('show');
        Route::resource('productos', 'ProductController');
        Route::post('productos-stock', 'ProductController@updateStock')->name('productos.stock.update');
        Route::delete('productos-combinaciones', 'ProductController@destroyCombinations')->name('productos.delete_combinations');
        Route::resource('productos-stock-history', 'ProductStockHistoryController')->only('index');
        Route::resource('stock-transferencias', 'ProductStockTransferController')->except('create');
        Route::resource('producto-imagen', 'ProductImageController')->only('index' , 'store', 'destroy');
        Route::post('producto-imagen-dropzone', 'ProductImageController@destroyWithRequest')->name('producto-imagen.dropzone.destroy');
        Route::get('download', 'ProductController@download')->name('catalog.download');
        Route::get('inventario/download', 'InventoryController@download')->name("catalog.inventory.download");
        Route::post('inventario/upload', 'InventoryController@upload')->name("catalog.inventory.upload");
        Route::resource('colors', 'ColorController');
    });

    # Stock Routes - Rutas de Almacenamiento
    Route::group(['prefix' => 'almacenamiento', 'namespace' => 'stock'], function () {
         #
         Route::resource('depositos' , 'StoreController');
    });

    # Customers Routes
    Route::group(['prefix' => 'gestion-clientes', 'namespace' => 'customers'], function () {
        #
        Route::resource('clientes', 'CustomerController');
    });

    # Orders Routes
    Route::get('ordenes', 'OrderController@index')->name('admin.orders.index');
    Route::get('ordenes/{order}', 'OrderController@show')->name('admin.orders.show');
    Route::get('ordenes/{order}/edit', 'OrderController@edit')->name('admin.orders.edit');
    Route::put('ordenes/{order}', 'OrderController@update')->name('admin.orders.update');
    Route::patch('ordenes/{order}/status', 'OrderController@updateStatus')->name('admin.orders.update_status');

    # Payments Routes
    Route::get('pagos', 'PaymentController@index')->name('admin.payments.index');
    Route::get('pagos/{payment}', 'PaymentController@show')->name('admin.payments.show');
    Route::post('pagos/{payment}/verify', 'PaymentController@verify')->name('admin.payments.verify');
    Route::post('pagos/{payment}/reject', 'PaymentController@reject')->name('admin.payments.reject');
    Route::patch('pagos/{payment}/status', 'PaymentController@updateStatus')->name('admin.payments.update_status');



    # Config Routes
    Route::group(['prefix' => 'config', 'namespace' => 'config'], function () {
        # 
        Route::resource('general', 'ConfigController')->only(['index', 'store']);
        #
        Route::get('validate-descuento-password', 'ConfigController@validateDiscountPassword')->name('config.discount');
        # 
        Route::resource('usuarios', 'UserController')->except('show');
        #
        Route::resource('permisos', 'PermissionController')->only('index');
        #
        Route::resource('roles', 'RoleController')->except('show');
    });

  
});