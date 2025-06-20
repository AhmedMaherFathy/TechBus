<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\ForgetPasswordController;
use Modules\Auth\Http\Controllers\AdminForgetPasswordController;
use Modules\Auth\Http\Controllers\DriverForgetPasswordController;

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

Route::prefix('mobile/user')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify-email', [AuthController::class, 'verifyOtp']);
    Route::post('login', [AuthController::class, 'userLogin']);
    Route::middleware('user.auth')->post('logout', [AuthController::class, 'userLogout']);
    Route::middleware('user.auth')->post('update-profile', [AuthController::class, 'userUpdateProfile']);
    Route::middleware('user.auth')->get('show/update-profile', [AuthController::class, 'showUserProfile']);

    Route::prefix('forget-password')->group(function () {
        Route::post('/', [ForgetPasswordController::class, 'SendOtp']);         //mobile/user/forget-password
        Route::post('verify', [ForgetPasswordController::class, 'verifyOtp']);  ///mobile/user/forget-password
        Route::post('reset-password', [ForgetPasswordController::class, 'resetPassword']);  ///mobile/user/forget-password/reset-password
    });
});


Route::prefix('mobile/driver')->group(function () {
    Route::post('login', [AuthController::class, 'driverLogin']);
    Route::middleware('driver.auth')->post('logout', [AuthController::class, 'driverLogout']);
    Route::middleware('driver.auth')->post('update-profile', [AuthController::class, 'driverUpdateProfile']);
    Route::middleware('driver.auth')->get('show/update-profile', [AuthController::class, 'showDriverProfile']);
    
    Route::prefix('forget-password')->group(function () {
        Route::post('/', [DriverForgetPasswordController::class, 'SendOtp']);         //mobile/driver/forget-password
        Route::post('verify', [DriverForgetPasswordController::class, 'verifyOtp']);  ///mobile/driver/forget-password
        Route::post('reset-password', [DriverForgetPasswordController::class, 'resetPassword']);  ///mobile/driver/forget-password/reset-password
    });
});

// Route::get('test',[AuthController::class,'test']);
// Route::get('otp', function(){
//     return view('otp');
// });

Route::post('api/admin/login', [AuthController::class, 'adminLogin']);

Route::prefix('admin/forget-password')->group(function () {
    Route::post('/', [AdminForgetPasswordController::class, 'SendOtp'])->middleware('throttle:otp');         //mobile/user/forget-password
    Route::post('verify', [AdminForgetPasswordController::class, 'verifyOtp']);  ///mobile/user/forget-password
    Route::post('reset-password', [AdminForgetPasswordController::class, 'resetPassword']);  ///mobile/user/forget-password/reset-password
});


Route::prefix('api/dashboard')->middleware('admin.auth')->group(function () {
    Route::get('logout', [AuthController::class, 'adminLogout']);
    Route::get('passengers', [AuthController::class, 'getAllPassengers']);
});