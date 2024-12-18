<?php

namespace Modules\Ticket\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Ticket\Http\Requests\TicketRequest;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Transformers\TicketResource;
use PhpParser\Node\Expr\FuncCall;

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

    public function index()
    {
        $tickets = Ticket::paginate(10);
        return $this->paginatedResponse($tickets , TicketResource::class);
    }

    public function show($id)
    {
        try{
            $Ticket = Ticket::findOrFail($id);
            return $this->successResponse(new TicketResource($Ticket));
        }catch (\Exception){
            return $this->errorResponse(message: 'Ticket not found');
        }
    }
    public function store(TicketRequest $request)
    {
        $validated = $request->validated();
        $validated['custom_id'] = $this->generateCustomId();
        Ticket::create($validated);
        return $this->successResponse(message:'Ticket Created Successfully');
    }

    public function update(TicketRequest $request,$id)
    {
        $validated = $request->validated();

        try{
            $ticket = Ticket::findOrFail($id);
            $ticket->update($validated);
            return $this->successResponse(message:'Ticket Updated Successfully');
        }catch (\Exception){
            return $this->errorResponse(message: 'Ticket not found');
        }
    }

    public function destroy($id)
    {
        try{
            $ticket = Ticket::findOrFail($id);
            $ticket->delete();
            return $this->successResponse(message:'Ticket Deleted Successfully');
        }catch(\Exception){
            return $this->errorResponse(message: 'Ticket not found');
        }
    }

    public function generateCustomId()
    {
        $lastTicket = Ticket::latest('id')->value('id');
        $nextId = $lastTicket ? ($lastTicket + 1) : 1;
        $customId = 'T-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        return $customId;
    }
}
