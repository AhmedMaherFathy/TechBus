<?php

namespace Modules\Ticket\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Ticket\Models\Ticket;

class TicketController extends Controller
{
    use HttpResponse;
    public function verifyQr($qr)
    {
        // DB::listen(fn($e) => info($e->toRawSql()));
        $ticket = Ticket::with('bus')->where('qr_code', $qr)->first();

        if (!$ticket) {
            return $this->errorResponse(message: 'Ticket not found');
        }

        $user = Auth::guard('user')->user();

        try {
            $response = DB::transaction(function () use ($ticket, $user) {
                if (!isset($user->balance)) {
                    return $this->errorResponse('Please charge your balance first');
                }
                if (!($user->balance->points >= $ticket->points)) {
                    return $this->errorResponse(message: 'Not enough points, please charge your points first');
                }

                DB::table('user_ticket')->insert([
                    'ticket_id' => $ticket->custom_id,
                    'user_id' => $user->custom_id,
                    'date' => now()->format('Y-m-d'),
                    'time' => now()->format('H:i:s'), 
                ]);

                $user->balance->points -= $ticket->points;
                $user->balance->save();

                return $this->successResponse( message: 'Ticket found and attached');
            });

            return $response;
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Failed to attach ticket: ' . $e->getMessage());
        }
    }
}
