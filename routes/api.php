<?php

use App\Http\Controllers\CRM\API\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['apiresponse']], function () {
    Route::controller(ServiceController::class)->prefix('v1/crm')->group(function () {
        Route::post('/sync-order-summary', 'syncOrderSummmary')->name('api.crm.sync-order-summary');
        Route::post('/sync-customer-target', 'syncCustomerTarget')->name('api.crm.sync-customer-target');
        Route::post('/sync-sales-order-summary', 'syncSalesOrderSummary')->name('api.crm.sync-sales-order-summary');
    });
});