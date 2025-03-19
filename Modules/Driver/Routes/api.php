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
Route::prefix('mobile')->group(function(){
    Route::put('update-driver-location/{driver}',[TrackingController::class,'updateDriverLocation']);
});