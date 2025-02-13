<?php

use Illuminate\Support\Facades\Route;
use Modules\Ticket\Http\Controllers\TicketController;

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
//     Route::apiResource('ticket', TicketController::class)->names('ticket');
// });

Route::middleware('admin.auth')->group(function() {
    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('tickets/{id}', [TicketController::class, 'show']);
    Route::post('create/tickets', [TicketController::class, 'store']);
    Route::patch('update/tickets/{id}', [TicketController::class, 'update']);
    Route::delete('tickets/{id}', [TicketController::class, 'destroy']);
    Route::get('ticket/list',[TicketController::class,'ticketList']);
});

Route::get('mobile/scan/ticket/{qr}',[TicketController::class,'verifyQr'])->middleware('user.auth');
