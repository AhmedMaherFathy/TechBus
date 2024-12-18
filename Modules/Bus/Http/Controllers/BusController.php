<?php

namespace Modules\Bus\Http\Controllers;

use Modules\Bus\Models\Bus;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Place\Models\Route;
use Modules\Driver\Models\Driver;
use Modules\Ticket\Models\Ticket;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Bus\Http\Requests\BusRequest;
use Modules\Bus\Transformers\BusResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BusController extends Controller
{
    use HttpResponse;

    public function index()
    {
        // DB::listen(fn($e)=> info($e->toRawSql()));
        $buses = Bus::with([
            'route' => function ($query) {
                $query->select('id', 'custom_id', 'number');
            },
            'driver' => function ($query) {
                $query->select('id', 'full_name', 'custom_id');
            },
            'ticket' => function ($query) {
                $query->select('id', 'custom_id', 'points');
            }
        ])
            ->paginate(10);

        return $this->paginatedResponse($buses, BusResource::class);
    }

    public function show($id)
    {
        try {
            $bus = Bus::with([
                'route' => function ($query) {
                    $query->select('id', 'custom_id', 'number');
                },
                'driver' => function ($query) {
                    $query->select('id', 'full_name', 'custom_id');
                },
                'ticket' => function ($query) {
                    $query->select('id', 'custom_id', 'points');
                }
            ])->where('id', $id)->firstOrFail();

            return $this->successResponse(data: new BusResource($bus));
        } catch (\Exception) {
            return $this->errorResponse(message: 'No Bus found');
        }
    }

    public function store(BusRequest $request)
    {
        $validated = $request->validated();
        $validated['custom_id'] = $this->generateCustomId();
        $bus = Bus::create($validated);
        if ($bus)
            return $this->successResponse(message: "Bus created successfully");
        else
            return $this->errorResponse(message: 'Failed to create bus');
    }

    public function update(BusRequest $request, $id)
    {
        $validated = $request->validated();

        try {

            $bus = Bus::findOrFail($id);

            $bus->update($validated);

            return $this->successResponse(
                data: new BusResource($bus),
                message: 'Bus updated successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->errorResponse(message: 'Bus not found');
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'An error occurred while updating the bus ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $bus = Bus::findOrFail($id);

            $bus->delete();

            return $this->successResponse(message: 'Bus deleted successfully');
        } catch (ModelNotFoundException) {
            return $this->errorResponse(message: 'Bus not found');
        } catch (\Exception) {
            return $this->errorResponse(message: 'An error occurred while deleting the bus');
        }
    }

    public function generateCustomId()
    {
        $lastUser = Bus::latest('id')->value('id');
        $nextId = $lastUser ? ($lastUser + 1) : 1;
        $customId = 'B-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        return $customId;
    }
    
    public function routeSelectMenu()
    {
        $routes = Route::select('custom_id')->get();
        return response()->json($routes);
    }

    public function ticketSelectMenu()
    {
        $tickets = Ticket::select('custom_id')->get();
        return response()->json($tickets);
    }

    public function busSelectMenu()
    {
        $buses = Bus::select('custom_id')->whereNull('driver_id')->get();
        return response()->json($buses);
    }

    public function driverSelectMenu()
    {
        $drivers = Driver::doesntHave('bus')->select('custom_id')->get();
        return response()->json($drivers);
    }

    public function assignDriverToBus(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,custom_id',
            'driver_id' => 'required|exists:drivers,custom_id',
        ]);

        // Find a bus where driver_id is NULL
        $bus = Bus::where('custom_id', $validated['bus_id'])
            ->whereNull('driver_id')
            ->first();

        // Return error if no bus is found (either assigned or doesn't exist)
        if (!$bus) {
            return $this->errorResponse(message: 'Bus not found or already assigned to a driver');
        }

        // Find the driver based on custom_id
        $driver = Driver::where('custom_id', $validated['driver_id'])->first();

        // Assign the driver to the bus
        $bus->driver_id = $driver->custom_id;
        $bus->save();

        return $this->successResponse(message: 'Driver assigned to bus successfully');
    }
}
