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

// Customer authentication check endpoint
Route::get('/customer/auth-check', function (Request $request) {
    return response()->json([
        'authenticated' => Auth::guard('customer')->check(),
        'customer' => Auth::guard('customer')->check() ? Auth::guard('customer')->user()->only(['id', 'name', 'email']) : null
    ]);
});