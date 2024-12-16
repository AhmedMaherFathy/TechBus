<?php

use Illuminate\Support\Facades\Route;
use Modules\Bus\Http\Controllers\BusController;

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

Route::middleware('admin.auth')->group(function() {
    Route::get('buses', [BusController::class, 'index']);
    Route::get('buses/{id}', [BusController::class, 'show']);
    Route::post('create/buses', [BusController::class, 'store']);
    Route::patch('update/buses/{id}', [BusController::class, 'update']);
    Route::delete('buses/{id}', [BusController::class, 'destroy']);
    Route::get('route/select-menu', [BusController::class, 'routeSelectMenu']);
    Route::get('ticket/select-menu', [BusController::class, 'ticketSelectMenu']);
    Route::get('driver/select-menu', [BusController::class, 'driverSelectMenu']);
    Route::get('bus/assign-driver-to-bus', [BusController::class, 'assignDriverToBus']);
});