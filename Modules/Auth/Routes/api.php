<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\ForgetPasswordController;

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

Route::post('mobile/user/register', [AuthController::class, 'register']);
Route::post('mobile/user/verify-email', [AuthController::class, 'verifyOtp']);
Route::post('mobile/user/login', [AuthController::class, 'login']);
Route::post('mobile/user/forget-password', [ForgetPasswordController::class, 'SendOtp']);
Route::post('mobile/user/forget-password/verify', [ForgetPasswordController::class, 'verifyOtp']);
Route::post('mobile/user/forget-password/reset-password', [AuthController::class, 'resetPassword']);
// Route::get('test',[AuthController::class,'test']);
// Route::get('otp', function(){
//     return view('otp');
// });

Route::post('api/admin/login', [AuthController::class, 'adminLogin']);
