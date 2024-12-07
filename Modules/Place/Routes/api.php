<?php

use Illuminate\Support\Facades\Route;
use Modules\Place\Http\Controllers\PlaceController;

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
//     Route::apiResource('place', PlaceController::class)->names('place');
// });

Route::get('/zones/search/{value}',[PlaceController::class,'getZone']);
Route::get('/stations/menu/{value}',[PlaceController::class,'getStation']);
Route::get('/end-stations/menu',[PlaceController::class,'getEndStation']);
Route::post('/get-bus-numbers', [PlaceController::class, 'getBusNumbers']);