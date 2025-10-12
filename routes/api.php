<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get_data', [PurchaseController::class, 'fetch']);
Route::post('/purchase/store', [PurchaseController::class, 'store']);   
Route::get('/purchase/show/{id}', [PurchaseController::class, 'show']);
Route::post('/purchase/update/{id}', [PurchaseController::class, 'update']);

Route::get('/get_saleData', [SaleController::class,'fetch']);
