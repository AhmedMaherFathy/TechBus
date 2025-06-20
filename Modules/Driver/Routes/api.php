<?php

use Illuminate\Support\Facades\Route;
use Modules\Driver\Http\Controllers\DriverController;
use Modules\Driver\Http\Controllers\TrackingController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('driver', DriverController::class)->names('driver');
// });

Route::middleware('admin.auth')->group(function() {
    Route::get('drivers', [DriverController::class, 'index']);
    Route::get('drivers/{id}', [DriverController::class, 'show']);
    Route::post('create/drivers', [DriverController::class, 'store']);
    Route::post('update/drivers/{id}', [DriverController::class, 'update']);
    Route::delete('drivers/{id}', [DriverController::class, 'destroy']);
});

//Driver App 
// ->middleware('user.auth')
Route::prefix('mobile')->middleware('driver.auth')->group(function(){
    Route::put('update-driver-location',[TrackingController::class,'updateDriverLocation']);
    Route::get('disable-driver-location',[TrackingController::class,'disableLocation']);
    Route::get('/driver/home',[DriverController::class,'driverInfo']);
    Route::post('/driver/book-ticket/store',[DriverController::class,'driverBookTicket']);
    Route::get('/driver/book-ticket',[DriverController::class,'getDriverBookTicket']);
    Route::get('tickets/price',[DriverController::class,'ticketsPrice']);
});

Route::get('dashboard/drivers/active',[TrackingController::class,'getActiveBuses']);