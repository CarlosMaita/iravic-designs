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
    });

    # Customers Routes
    Route::group(['prefix' => 'gestion-clientes', 'namespace' => 'customers_management'], function () {
        #
        Route::resource('clientes', 'CustomerController');
        #
        Route::resource('zonas', 'ZoneController')->except('show');
    });

    # Customers Routes
    Route::group(['prefix' => 'cajas-ventas', 'namespace' => 'sales'], function () {
        #
        Route::resource('cajas', 'BoxController');
        #
        Route::resource('pedidos', 'OrderController')->except('destroy');
    });

    # Config Routes
    Route::group(['prefix' => 'config', 'namespace' => 'config'], function () {
        # 
        Route::resource('usuarios', 'UserController')->except('show');
        #
        Route::resource('permisos', 'PermissionController')->only('index');
        #
        Route::resource('roles', 'RoleController')->except('show');
    });
});