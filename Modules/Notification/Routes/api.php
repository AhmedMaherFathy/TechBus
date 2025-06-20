<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

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

Route::get('test/notification/{id}',[NotificationController::class,'makeNotification']);
Route::get('mobile/driver/notifications',[NotificationController::class,'getDriverNotifications'])->middleware('driver.auth');
Route::patch('mobile/driver/update-fcm-token',[NotificationController::class,'updateDriverFcmToken'])->middleware('driver.auth');
