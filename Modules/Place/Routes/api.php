<?php

use Illuminate\Support\Facades\Route;
use Modules\Place\Http\Controllers\ZoneController;
use Modules\Place\Http\Controllers\PlaceController;
use Modules\Place\Http\Controllers\RouteController;
use Modules\Place\Http\Controllers\StationController;

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
Route::get('/end-stations/menu/{id}',[PlaceController::class,'getEndStation']);
Route::post('/get-bus-numbers', [PlaceController::class, 'getBusNumbers']);

Route::prefix('dashboard/zones')->middleware('admin.auth')->group(function(){
    Route::get('',[ZoneController::class,'index']);
    Route::get('{zone}',[ZoneController::class,'show']);
    Route::post('',[ZoneController::class,'store']);
    Route::put('{zone}',[ZoneController::class,'update']);
    Route::delete('{zone}',[ZoneController::class,'destroy']);
});


Route::prefix('dashboard/stations')->middleware('admin.auth')->group(function(){
    Route::get('',[StationController::class,'index']);
    Route::get('{station}',[StationController::class,'show']);
    Route::post('',[StationController::class,'store']);
    Route::put('{station}',[StationController::class,'update']);
    Route::delete('{station}',[StationController::class,'destroy']);
});


Route::prefix('dashboard/routes')->middleware('admin.auth')->group(function(){
    Route::get('',[RouteController::class,'index']);
    Route::get('{route}',[RouteController::class,'show']);
    Route::post('',[RouteController::class,'store']);
    Route::put('{route}',[RouteController::class,'update']);
    Route::delete('{route}',[RouteController::class,'destroy']);
});