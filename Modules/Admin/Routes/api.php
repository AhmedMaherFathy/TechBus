<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;

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
//     Route::apiResource('admin', AdminController::class)->names('admin');
// });

Route::group(['prefix' => 'admin', 'middleware' => 'admin.auth'], function () {
    Route::get('/all', [AdminController::class, 'index']);
    Route::post('/store', [AdminController::class, 'store']);
    Route::delete('/delete/{admin}', [AdminController::class, 'delete']);
    Route::get('/show/{admin}', [AdminController::class, 'show']);
    Route::put('/update/{admin}', [AdminController::class, 'update']);
});
