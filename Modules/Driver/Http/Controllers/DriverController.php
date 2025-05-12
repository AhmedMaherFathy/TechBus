<?php

namespace Modules\Driver\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Driver\Models\Driver;
use Modules\Ticket\Models\Ticket;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Driver\Http\Requests\DriverRequest;
use Modules\Driver\Transformers\DriverResource;
use Modules\Driver\Transformers\DriverInfoResource;

class DriverController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $driver = Driver::with([
            'bus' => fn($q) => $q->select('id','custom_id','driver_id'),
            ])
            ->fastPaginate(10);

        return $this->paginatedResponse($driver, DriverResource::class, message: 'driver Fetched Successfully');
    }

    public function store(DriverRequest $request)
    {
        $validated = $request->validated();
        $customId = $this->generateCustomId();
        $validated['password'] = Hash::make($validated['password']);
        $validated['custom_id'] = $customId;
        $validated['days'] = json_encode($validated['days'] ?? []); // Encode to JSON
        // info($validated);die;
        $driver = Driver::create($validated);

        if (isset($validated['photo'])) {
            $driver->attachMedia($request->file('photo'));
        }
        return $this->successResponse(message: 'Driver Created Successfully');
    }
    /**
     * Generate a custom ID for the driver.
     *
     * @return string
     */
    private function generateCustomId(): string
    {
        $lastUser = Driver::latest('id')->first();
        $nextId = $lastUser ? ((int) (str_replace('D-', '', $lastUser->id)) + 1) : 1;
    
        return 'D-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    }
    
    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $driver = Driver::with([
            'bus' => fn($q) => $q->select('id','custom_id','driver_id'),
            ])
            ->find($id);

        if ($driver) {
            return $this->successResponse(DriverResource::make($driver), message: 'Fetched Successfully');
        } else {
            return $this->errorResponse(message: 'Driver Not found');
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(DriverRequest  $request, $id)
    {
        try {
            // Find the driver by ID
            $driver = Driver::findOrFail($id);

            $validated = $request->validated();

            if (isset($validated['password'])) {
                // info("what");die;
                $validated['password'] = Hash::make($validated['password']);
            }
            else{
                unset($validated['password']);
            }

            if ($request->hasFile('photo')) {
                $driver->updateMedia($request->validated('photo'));
            }
            $driver->update($validated);

            return $this->successResponse(DriverResource::make($driver), message: 'Driver updated successfully');
        } catch (\Exception $e) {

            return $this->errorResponse(message: $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = Driver::findOrFail($id);
            $user->detachMedia();
            $user->delete();

            return $this->successResponse(message: 'Driver deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'No Driver found');
        }
    }
    /*
        home page driver app
     */
    public function driverInfo(Request $request) //02:25
    {
        $driver = Driver::select('custom_id', 'full_name', 'start_time', 'end_time', 'days')
                        ->with([
                            'bus' => fn($query) => $query->select('driver_id', 'plate_number', 'route_id'),
                            'bus.route' => fn($query) => $query->select('custom_id', 'number')
                        ])
                        ->where('id', $request->user('driver')->id)
                        ->first();
        // info($driver);die;
        return $this->successResponse(DriverInfoResource::make($driver), message: 'Driver Info Fetched Successfully');
    }

    /**
     * driver book ticket for whose has not app or not enough points 
     *
     * @return \Illuminate\Http\JsonResponse
     */ 
    public function driverBookTicket(Request $request)
    {
        // $prices = $this->ticketsPrice();
        $validated = $request->validate([
            "price" => ['required'],
        ]);
        // info($prices);die;

        $driver = Driver::with(['bus:ticket_id,driver_id','bus.ticket:points,custom_id'])
            ->select('custom_id')
            ->where('id', $request->user('driver')->id)
            ->firstOrFail();

        DB::table('driver_ticket')
            ->where('id',$request->user('driver')->id)
            ->insert([
                'ticket_id' => $driver->bus->ticket->custom_id,
                'driver_id' => $driver->custom_id,
                'date' => now()->format('Y-m-d'),
                'time' => now()->format('H:i:s'),
                'payed' => $validated['price'],
            ]);

        // $totalAmount = $this->getTotalAmount($request);
        
        // $PassengersNumber = $this->getNumberOfNonAppPassengers($request);
        
        return $this->successResponse(
                                            message: 'Ticket Booked Successfully');
    }

    public function ticketsPrice()
    {
        $prices = Ticket::distinct()->pluck('points');
        // $prices = DB::table('tickets')->distinct()->pluck("points")->toArray();
        info(gettype($prices));
        return $prices;
    }

    public function getDriverBookTicket(Request $request)
    {
        $totalAmount = $this->getTotalAmount($request);
        
        $PassengersNumber = $this->getNumberOfNonAppPassengers($request);
        
        return $this->successResponse(data: [
                                            "passengersNumbers" => $PassengersNumber,
                                            "TotalAmount" => $totalAmount
                                            ],
                                            message: 'Ticket Booked Successfully');
    }

    public function getNumberOfNonAppPassengers($request)
    {
        return DB::table('driver_ticket')
                    ->where('date', now()->today())
                    ->where('driver_id', $request->user('driver')->custom_id)
                    ->count();
    }

    public function getTotalAmount($request)
    {
        return DB::table('driver_ticket')
                        ->where('driver_id', $request->user('driver')->custom_id)
                        ->where('driver_id', $request->user('driver')->custom_id)
                        ->where('date', now()->today())
                        ->sum('payed');
    }
}
