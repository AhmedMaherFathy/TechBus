<?php

namespace Modules\Ticket\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Ticket\Models\Ticket;

class TicketController extends Controller
{
    use HttpResponse;
    public function verifyQr($qr)
    {
        $Ticket = Ticket::where('qr_code',$qr)->first();
        if($Ticket)
        {
            return $this->successResponse(message: 'Ticket found');
        }
        return $this->errorResponse(message: 'Ticket NOT found');
    }
}