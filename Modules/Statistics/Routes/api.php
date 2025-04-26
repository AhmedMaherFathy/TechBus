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

Route::get('dashboard/statistics/numbers',[StatisticsController::class,'numberOfEmployees']);
Route::get('dashboard/statistics/chart1',[StatisticsController::class,'usersRegisteredAt']);
Route::get('dashboard/statistics/chart2',[StatisticsController::class,'getHourlyTicketSales']);
