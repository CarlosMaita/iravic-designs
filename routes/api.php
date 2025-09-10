<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/products', 'App\Http\Controllers\Api\ProductController@index');

// Currency conversion routes
Route::prefix('currency')->group(function () {
    Route::get('/exchange-rate', 'App\Http\Controllers\Api\CurrencyController@getExchangeRate');
    Route::post('/convert', 'App\Http\Controllers\Api\CurrencyController@convertPrice');
    Route::post('/both-prices', 'App\Http\Controllers\Api\CurrencyController@getBothPrices');
});