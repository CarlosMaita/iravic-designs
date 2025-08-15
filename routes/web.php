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
Route::group(['namespace' => 'ecommerce'], function () {
    # Home
    Route::get('/', 'HomeController@index')->name('ecommerce.home');
    # Catalog
    Route::get('/catalogo', 'CatalogController@index')->name('ecommerce.catalog');
    # Category
Route::get('/categoria/{slug}', 'CatalogController@category')->name('ecommerce.categoria');
    # Product Detail
    Route::get('/producto/{slug}', 'CatalogController@show')->name('ecommerce.product.detail');
});

#
Route::get('ingresar',           [CustomerLoginController::class, 'showLoginForm'])->name('customer.login.form');
Route::post('customer/login',    [CustomerLoginController::class, 'login'])->name('customer.login');
Route::post('customer/logout',   [CustomerLoginController::class, 'logout'])->name('customer.logout');

#
Route::middleware(['auth:customer'])->group(function () {

});


Route::group(['namespace' => 'admin', 'middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Route::get('/', 'HomeController@index')->name('admin.home')->middleware('redirect.home.role');
    #
    Route::get('mi-perfil', 'MyProfileController@index')->name('my-profile.index');
    #
    Route::get('mi-perfil/edit', 'MyProfileController@edit')->name('my-profile.edit');
    #
    Route::put('mi-perfil', 'MyProfileController@update')->name('my-profile.update');

    // Banner Resource
    Route::resource('banners', 'BannerController')->except('show');

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
        #
        Route::get('morosos', 'CustomerController@indexDebtors')->name('clientes.debtors');
        #
        Route::resource('zonas', 'ZoneController');
        #
        Route::post('zonas-ordenar', 'ZoneController@sort')->name('zonas.sort');
    });

    # Box && Orders Routes
    Route::group(['prefix' => 'cajas-ventas', 'namespace' => 'sales'], function () {
        #
        Route::resource('cajas', 'BoxController');
        #
        Route::resource('devoluciones', 'RefundController', ['parameters' => [
            'devoluciones' => 'devolucion'
        ]])->except('edit', 'update', 'destroy');
        #
        Route::resource('deudas', 'DebtController')->except('create');
        #
        Route::resource('gastos', 'SpendingController')->except('create');
        #
        Route::resource('operaciones', 'OperationController')->only('index');
        #
        Route::get('operaciones-pdf', 'OperationController@download')->name('operaciones.download');
        #
        Route::resource('pagos', 'PaymentController')->except('create');
        #
        Route::resource('ventas', 'OrderController')->except('edit', 'update', 'destroy');
        #
        Route::get('ventas-descuento', 'OrderController@calculateDiscount')->name('ventas.discount');

    });

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