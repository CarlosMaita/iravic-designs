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

Auth::routes(['register' => false]);

// Route::get('/', 'HomeController@index')->name('homepage');
Route::get('/', function () { return redirect('/login'); });


Route::group(['namespace' => 'admin', 'middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Route::get('/', function () { 
        return view('dashboard.homepage');
    })
    ->name('admin.home')
    ->middleware('redirect.home.role');

    #
    Route::get('mi-perfil/edit', 'MyProfileController@edit')->name('my-profile.edit');
    #
    Route::put('mi-perfil', 'MyProfileController@update')->name('my-profile.update');

    # Catalog Routes
    Route::group(['prefix' => 'catalogo', 'namespace' => 'catalog'], function () {
        #
        Route::resource('categorias', 'CategoryController')->except('show');
        #
        Route::resource('marcas', 'BrandController')->except('show');
        #
        Route::resource('productos', 'ProductController');
        #
        Route::post('productos-stock', 'ProductController@updateStock')->name('productos.stock.update');
        #
        Route::delete('productos-combinaciones', 'ProductController@destroyCombinations')->name('productos.delete_combinations');
        #
        Route::resource('productos-stock-history', 'ProductStockHistoryController')->only('index');
        #
        Route::resource('stock-transferencias', 'ProductStockTransferController')->except('create');
        #
        Route::resource('producto-imagen', 'ProductImageController')->only('index', 'destroy');
        #
        Route::get('download', 'ProductController@download')->name('catalog.download');
    });

    # Customers Routes
    Route::group(['prefix' => 'gestion-clientes', 'namespace' => 'customers'], function () {
        #
        Route::resource('clientes', 'CustomerController');
        #
        Route::resource('zonas', 'ZoneController');
    });

    # Schedules Routes
    Route::group(['prefix' => 'gestion-agendas', 'namespace' => 'schedules'], function () {
        #
        Route::resource('agendas', 'ScheduleController')->except('create', 'store', 'edit', 'update');
        #
        Route::resource('visitas', 'VisitController')->except('create', 'show');
        Route::put('visitas/{visita}/update-responsable', 'VisitController@updateResponsable');
        Route::put('visitas/{visita}/complete', 'VisitController@complete');
    });

    # Customers Routes
    Route::group(['prefix' => 'cajas-ventas', 'namespace' => 'sales'], function () {
        #
        Route::resource('cajas', 'BoxController');
        #
        Route::resource('devoluciones', 'RefundController', ['parameters' => [
            'devoluciones' => 'devolucion'
        ]])->except('edit', 'update', 'destroy');
        #
        Route::resource('gastos', 'SpendingController')->except('create');
        #
        Route::resource('pagos', 'PaymentController')->except('create');
        #
        Route::resource('pedidos', 'OrderController')->except('destroy');
        #
        Route::get('pedidos-descuento', 'OrderController@calculateDiscount')->name('pedidos.discount');
    });

    # Config Routes
    Route::group(['prefix' => 'config', 'namespace' => 'config'], function () {
        # 
        Route::resource('general', 'ConfigController')->only(['index', 'store']);
        # 
        Route::resource('usuarios', 'UserController')->except('show');
        #
        Route::resource('permisos', 'PermissionController')->only('index');
        #
        Route::resource('roles', 'RoleController')->except('show');
    });
});