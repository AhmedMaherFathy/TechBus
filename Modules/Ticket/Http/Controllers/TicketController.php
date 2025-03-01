<?php

namespace Modules\Ticket\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Bus\Models\Bus;
use Modules\Ticket\Http\Requests\TicketRequest;
use Modules\Ticket\Models\Ticket;
use Modules\Ticket\Transformers\TicketResource;
use Modules\Ticket\Transformers\UserTicketResource;
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
                    'payed' => $ticket->points
                ]);

                $user->balance->points -= $ticket->points;
                $user->balance->save();
                $data['points'] = $user->balance->points;
                return $this->successResponse(data: $data, message: 'Ticket found and attached');
            });

            return $response;
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Failed to attach ticket: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $tickets = Ticket::paginate(10);
        return $this->paginatedResponse($tickets, TicketResource::class);
    }

    public function show($id)
    {
        try {
            $Ticket = Ticket::findOrFail($id);
            return $this->successResponse(new TicketResource($Ticket));
        } catch (\Exception) {
            return $this->errorResponse(message: 'Ticket not found');
        }
    }
    public function store(TicketRequest $request)
    {
        $validated = $request->validated();
        $validated['custom_id'] = $this->generateCustomId();
        Ticket::create($validated);
        return $this->successResponse(message: 'Ticket Created Successfully');
    }

    public function update(TicketRequest $request, $id)
    {
        $validated = $request->validated();

        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->update($validated);
            return $this->successResponse(message: 'Ticket Updated Successfully');
        } catch (\Exception) {
            return $this->errorResponse(message: 'Ticket not found');
        }
    }

    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            // info($ticket);
            if ($ticket->users()->exists()) {
                return $this->errorResponse(message: 'Cannot delete this ticket because it has related bookings.');
            }
            $ticket->delete();
            return $this->successResponse(message: 'Ticket Deleted Successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
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

    public function ticketList()
    {
        $date = now()->format('Y-m-d');
        $ticketCounts = Ticket::select(
            'tickets.id',
            'tickets.custom_id',
            DB::raw("(SELECT COUNT(*) FROM user_ticket WHERE user_ticket.ticket_id = tickets.custom_id AND user_ticket.date = '{$date}') AS total"),
            DB::raw("(SELECT SUM(payed) FROM user_ticket WHERE user_ticket.ticket_id = tickets.custom_id AND user_ticket.date = '{$date}') AS totalRevenue"),
        )
            ->with(['bus:custom_id,ticket_id'])
            ->lazy()
            ->filter(function ($ticket) {
                return $ticket->total > 0; // Filter tickets with non-null and positive total
            })
            ->map(function ($ticket) use ($date) {
                $ticket->date = $date;
                // info($ticket);
                $ticket['totalRevenue'] = (int)($ticket['totalRevenue']);
                return $ticket;
            });
        return $this->successResponse($ticketCounts, message: $ticketCounts->isEmpty() ? 'No Tickets list found today' : 'Tickets list retrieved successfully');
    }

    // public function UserInvoice()
    // {
    //     $customId = Auth::guard('user')->user()->custom_id;

    //     $userInvoices = DB::table('user_ticket')
    //         ->where('user_id', $customId)
    //         ->latest('date')
    //         ->get();
    //     foreach ($userInvoices as $index => $invoice) {
    //         $userInvoices[$index]->bus = Bus::where('ticket_id', $invoice->ticket_id)->with(['Route:custom_id,number'])->get('route_id');
    //     }
    //     return $this->successResponse($userInvoices, message: 'Invoices retrieved successfully');
    // }
    public function UserInvoice(Request $request)
    {
        $customId = Auth::guard('user')->user()->custom_id;

        $userInvoices = DB::table('user_ticket')
            ->where('user_id', $customId)
            ->latest('date')
            ->leftJoin('buses', 'user_ticket.ticket_id', '=', 'buses.ticket_id')
            ->leftJoin('routes', 'buses.route_id', '=', 'routes.custom_id')
            ->select(
                'user_ticket.*',
                'buses.route_id',
                'routes.custom_id as route_custom_id',
                'routes.number as route_number'
            )
            ->cursor(); // Using cursor() instead of get()

        // Transform response to maintain structure
        $formattedInvoices = [];
        foreach ($userInvoices as $invoice) {
            $formattedInvoices[] = [
                'id' => $invoice->id,
                'user_id' => $invoice->user_id,
                'ticket_id' => $invoice->ticket_id,
                'date' => $invoice->date,
                'time' => $invoice->time,
                'payed' => $invoice->payed,
                'bus' => [
                    [
                        'route_id' => $invoice->route_id,
                        'route' => [
                            'custom_id' => $invoice->route_custom_id,
                            'number' => $invoice->route_number,
                        ]
                    ]
                ],
            ];
        }

        return $this->successResponse($formattedInvoices, message: 'Invoices retrieved successfully');
    }

    //     public function UserInvoice()
    // {
    //     // Get the authenticated user's custom ID
    //     $customId = Auth::guard('user')->user()->custom_id;

    //     // Step 1: Fetch all ticket IDs for the user
    //     $ticketIds = DB::table('user_ticket')
    //         ->where('user_id', $customId)
    //         ->pluck('ticket_id'); // Extract only ticket IDs

    //     // Step 2: Fetch all related buses in a single query
    //     $buses = Bus::whereIn('ticket_id', $ticketIds)
    //         ->select('ticket_id', 'custom_id') // Select only necessary columns
    //         ->get()
    //         ->keyBy('ticket_id'); // Create a dictionary-like structure for quick lookup

    //     // Step 3: Attach the related bus to each invoice
    //     $userInvoices = DB::table('user_ticket')
    //         ->where('user_id', $customId)
    //         ->get() // Fetch all invoices for the user
    //         ->map(function ($invoice) use ($buses) {
    //             $invoice->bus = $buses->get($invoice->ticket_id)?->custom_id; // Attach bus custom_id
    //             return $invoice;
    //         });

    //     // Step 4: Return the response
    //     return $this->successResponse(
    //         $userInvoices,
    //         message: $userInvoices->isEmpty() ? 'No invoices found for the user' : 'Invoices retrieved successfully'
    //     );
    // }
}
