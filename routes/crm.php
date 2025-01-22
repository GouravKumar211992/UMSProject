<?php

// use App\Http\Controllers\CRM\NotesController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| CRM Routes
|--------------------------------------------------------------------------
|
| Here is where you can register crm routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group with "crm" prefix. Make something great!
|
*/

Route::middleware(['user.auth'])->group(function () {
    Route::get('/home', 'IndexController@index')->name('crm.home');

    // Notes route
    Route::controller(NotesController::class)->prefix('notes')->group(function () {
        Route::get('/', 'index')->name('notes.index');
        Route::get('/prospects', 'prospects')->name('notes.prospects');
        Route::get('/create', 'create')->name('notes.create');
        Route::post('/render-diaries', 'renderDiaries')->name('notes.render-diaries');
        Route::get('/get-customers/{erpCustomer}', 'getCustomers')->name('customers.get');
    });

    // Orders
    Route::controller(CustomersController::class)->prefix('customers')->group(function () {
        Route::get('/dashboard', 'dashbaord')->name('customers.dashboard');
        Route::get('/view/{customerCode}', 'view')->name('customers.view');
        Route::get('/', 'index')->name('customers.index');
        Route::get('/orders/{customerCode}', 'getOrders')->name('customers.orders');
        Route::get('/order/csv', 'orderCsv')->name('customers.order-csv');
        Route::get('/order-detail/csv/{customerCode}', 'orderDetailCsv')->name('customers.order-detail.csv');
        
    });
    
    Route::controller(ProspectsController::class)->prefix('prospects')->group(function () {
        Route::get('/dashboard', 'dashboard')->name('prospects.dashboard');
        Route::get('/', 'index')->name('prospects.index');
        Route::get('/csv', 'prospectsCsv')->name('prospects.csv');
        Route::get('/{customerCode}', 'view')->name('prospects.view');
        Route::post('/update-status/{id}', 'updateLeadStatus')->name('prospects.update-status');
    });

    // Route::get('/get-locations/{erpCustomer}', 'NotesController@getLocations')->name('locations.get');

    // Route::get('/get-sales-summary/{date_filter}', 'IndexController@getSalesSummary')->name('salessummary.get');

    // Route::get('/get-team-members', 'IndexController@getTeamMembers')->name('teammembers.get');

});

Route::group(['middleware' => ['user.auth', 'apiresponse']], function () {
    Route::controller(IndexController::class)->group(function () {
        Route::get('/get-states/{country}', 'getStates')->name('crm.get-states');
        Route::get('/get-cities/{state}', 'getCities')->name('crm.get-cities');
        Route::get('/get-countries-states/{type}', 'getCountriesStates')->name('crm.get-countries-states');
    });

    // Notes route
    Route::controller(NotesController::class)->prefix('notes')->group(function () {
        Route::post('/store', 'store')->name('notes.store');
    });
});


