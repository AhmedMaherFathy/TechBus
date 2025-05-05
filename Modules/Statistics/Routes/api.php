<?php

use Illuminate\Support\Facades\Route;
use Modules\Statistics\Http\Controllers\StatisticsController;

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

Route::prefix('dashboard/statistics')->middleware('admin.auth')->group(function(){
    Route::get('/numbers',[StatisticsController::class,'numberOfEmployees']);
    Route::get('/chart1',[StatisticsController::class,'usersRegisteredAt']);
    Route::get('/chart2',[StatisticsController::class,'getHourlyTicketSales']);
    Route::get('/routes-ids',[StatisticsController::class,'getRouteIds']);
    Route::get('/tickets-history',[StatisticsController::class,'ticketsHistory']);
});