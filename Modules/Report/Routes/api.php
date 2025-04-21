<?php

use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\ReportController;

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


Route::post('mobile/report',[ReportController::class,'store'])->middleware('user.auth');

Route::prefix('dashboard/reports')->middleware('admin.auth')->group(function () {
    Route::get('',[ReportController::class,'index']);
    Route::get('{id}',[ReportController::class,'show']);
    Route::delete('{id}',[ReportController::class,'destroy']);
});
