<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/sales/dateCount', [SalesController::class, 'dateCount']);
    Route::get('/sales/dateRange', [SalesController::class, 'dateRange']);
    Route::post('/sales/data', [SalesController::class, 'salesData']);
    Route::get('/sales/formfilters', [SalesController::class, 'saleFormFilters']);
    Route::get('/sales/filters', [SalesController::class, 'filters']);
    Route::post('/sales/add', [SalesController::class, 'addSale']);
    Route::post('/customers/add', [CustomerController::class, 'add']);
});
