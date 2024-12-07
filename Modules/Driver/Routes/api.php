<?php

use Illuminate\Support\Facades\Route;
use Modules\Driver\Http\Controllers\DriverController;

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

Route::prefix('drivers')->group(function() {
    Route::get('', [DriverController::class, 'index']);
    Route::get('{id}', [DriverController::class, 'show']);
    Route::post('', [DriverController::class, 'store']);
    Route::post('{id}', [DriverController::class, 'update']);
    Route::delete('{id}', [DriverController::class, 'destroy']);
});