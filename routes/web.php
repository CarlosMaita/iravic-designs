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

# Sitemap Route
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

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

# Google Authentication Routes
Route::get('auth/google',         [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('customer.google.redirect');
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])->name('customer.google.callback');
Route::get('google/register',     [\App\Http\Controllers\Auth\GoogleController::class, 'showGoogleRegistrationForm'])->name('customer.google.register.form');
Route::post('google/register',    [\App\Http\Controllers\Auth\GoogleController::class, 'completeGoogleRegistration'])->name('customer.google.register.complete');

# Customer Protected Routes
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/e/dashboard', [\App\Http\Controllers\Ecommerce\CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::get('/e/perfil', [\App\Http\Controllers\Ecommerce\CustomerDashboardController::class, 'profile'])->name('customer.profile');
    // Customer shipping info endpoints
    Route::get('/api/customer/shipping', [\App\Http\Controllers\Ecommerce\CustomerDashboardController::class, 'getShippingJson'])->name('customer.shipping.json');
    Route::post('/e/perfil/shipping', [\App\Http\Controllers\Ecommerce\CustomerDashboardController::class, 'updateShipping'])->name('customer.profile.shipping.update');
    
    # Order Routes
    Route::get('/e/ordenes', [\App\Http\Controllers\Ecommerce\OrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/e/ordenes/{order}', [\App\Http\Controllers\Ecommerce\OrderController::class, 'show'])->name('customer.orders.show');
    Route::post('/e/ordenes/{order}/pagos', [\App\Http\Controllers\Ecommerce\OrderController::class, 'addPayment'])->name('customer.orders.add_payment');
    Route::post('/e/ordenes/{order}/cancel', [\App\Http\Controllers\Ecommerce\OrderController::class, 'cancel'])->name('customer.orders.cancel');
    
    # Payment Routes
    Route::get('/e/pagos', [\App\Http\Controllers\Ecommerce\PaymentController::class, 'index'])->name('customer.payments.index');
    
    # Favorites Routes
    Route::get('/e/favoritos', [\App\Http\Controllers\Ecommerce\FavoriteController::class, 'index'])->name('customer.favorites.index');
    Route::post('/api/favorites/toggle', [\App\Http\Controllers\Ecommerce\FavoriteController::class, 'toggle'])->name('api.favorites.toggle');
    Route::post('/api/favorites', [\App\Http\Controllers\Ecommerce\FavoriteController::class, 'store'])->name('api.favorites.store');
    Route::delete('/api/favorites', [\App\Http\Controllers\Ecommerce\FavoriteController::class, 'destroy'])->name('api.favorites.destroy');
    
    # Notification Routes
    Route::get('/api/notifications', [\App\Http\Controllers\Ecommerce\NotificationController::class, 'index'])->name('api.notifications.index');
    Route::get('/api/notifications/unread-count', [\App\Http\Controllers\Ecommerce\NotificationController::class, 'unreadCount'])->name('api.notifications.unread-count');
    Route::post('/api/notifications/{id}/read', [\App\Http\Controllers\Ecommerce\NotificationController::class, 'markAsRead'])->name('api.notifications.mark-read');
    Route::post('/api/notifications/read-all', [\App\Http\Controllers\Ecommerce\NotificationController::class, 'markAllAsRead'])->name('api.notifications.mark-all-read');
});

# Order Creation Routes (public API for cart)
Route::post('/api/orders/create', [\App\Http\Controllers\Ecommerce\OrderController::class, 'create'])->name('api.orders.create');

# Customer authentication check endpoint (needs session access)
Route::get('/api/customer/auth-check', [\App\Http\Controllers\Api\CustomerAuthController::class, 'authCheck']);


Route::group(['namespace' => 'App\Http\Controllers\admin', 'middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Route::get('/', 'HomeController@index')->name('admin.home');
    #
    Route::get('mi-perfil', 'MyProfileController@index')->name('my-profile.index');
    #
    Route::get('mi-perfil/edit', 'MyProfileController@edit')->name('my-profile.edit');
    #
    Route::put('mi-perfil', 'MyProfileController@update')->name('my-profile.update');

    // Banner Resource
    Route::resource('banners', 'BannerController')->except('show');

    // Special Offers Resource
    Route::resource('ofertas-especiales', 'SpecialOfferController')
        ->parameters(['ofertas-especiales' => 'special_offer'])
        ->names([
        'index' => 'special-offers.index',
        'create' => 'special-offers.create',
        'store' => 'special-offers.store',
        'edit' => 'special-offers.edit',
        'update' => 'special-offers.update',
        'destroy' => 'special-offers.destroy',
        'show' => 'special-offers.show',
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
    Route::get('ordenes/archivadas', 'OrderController@archived')->name('admin.orders.archived');
    Route::get('ordenes/{order}', 'OrderController@show')->name('admin.orders.show');
    Route::get('ordenes/{order}/edit', 'OrderController@edit')->name('admin.orders.edit');
    Route::put('ordenes/{order}', 'OrderController@update')->name('admin.orders.update');
    Route::patch('ordenes/{order}/status', 'OrderController@updateStatus')->name('admin.orders.update_status');
    Route::post('ordenes/{order}/cancel', 'OrderController@cancel')->name('admin.orders.cancel');
    Route::post('ordenes/{order}/archive', 'OrderController@archive')->name('admin.orders.archive');
    Route::post('ordenes/{order}/unarchive', 'OrderController@unarchive')->name('admin.orders.unarchive');

    # Payments Routes
    Route::get('pagos', 'PaymentController@index')->name('admin.payments.index');
    Route::get('pagos/archivados', 'PaymentController@archived')->name('admin.payments.archived');
    Route::get('pagos/{payment}', 'PaymentController@show')->name('admin.payments.show');
    Route::post('pagos', 'PaymentController@store')->name('admin.payments.store');
    Route::post('pagos/{payment}/verify', 'PaymentController@verify')->name('admin.payments.verify');
    Route::post('pagos/{payment}/reject', 'PaymentController@reject')->name('admin.payments.reject');
    Route::post('pagos/{payment}/archive', 'PaymentController@archive')->name('admin.payments.archive');
    Route::post('pagos/{payment}/unarchive', 'PaymentController@unarchive')->name('admin.payments.unarchive');
    Route::patch('pagos/{payment}/status', 'PaymentController@updateStatus')->name('admin.payments.update_status');



    # Config Routes
    Route::group(['prefix' => 'config', 'namespace' => 'config'], function () {
        # 
        Route::resource('general', 'ConfigController')->only(['index', 'store']);
        #
        Route::get('validate-descuento-password', 'ConfigController@validateDiscountPassword')->name('config.discount');
        # 
        # Exchange Rate Routes
        Route::get('tasa-cambio', 'ExchangeRateController@index')->name('admin.exchange-rate.index');
        Route::post('tasa-cambio/actualizar-bcv', 'ExchangeRateController@updateFromBCV')->name('admin.exchange-rate.update-bcv');
        Route::post('tasa-cambio/actualizar-manual', 'ExchangeRateController@updateManual')->name('admin.exchange-rate.update-manual');
        Route::post('tasa-cambio/toggle-module', 'ExchangeRateController@toggleCurrencyModule')->name('admin.exchange-rate.toggle-module');
        Route::get('tasa-cambio/current', 'ExchangeRateController@getCurrentRate')->name('admin.exchange-rate.current');
        #
        Route::resource('usuarios', 'UserController')->except('show');
    });

    // Debug route for testing authorization
    Route::get('debug-auth', 'DebugController@authDebug');

  
});