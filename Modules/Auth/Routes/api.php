<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

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
//     Route::apiResource('auth', AuthController::class)->names('auth');
// });

Route::post('mobile/user/register', [AuthController::class,'register']);
Route::post('mobile/user/verify-email',[AuthController::class,'verifyOtp']);
// Route::get('test',[AuthController::class,'test']);
// Route::get('otp', function(){
//     return view('otp');
// });