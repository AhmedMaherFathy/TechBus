<?php

namespace Modules\Bus\Http\Controllers;

use Modules\Bus\Models\Bus;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Place\Models\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Bus\Http\Requests\BusRequest;
use Modules\Bus\Transformers\BusResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Ticket\Models\Ticket;

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
            return $this->successResponse(message:"Bus created successfully");
        else
            return $this->errorResponse(message: 'Failed to create bus');
    }

    public function update(BusRequest $request, $id)
    {
        try {
            $validated = $request->validated();

            $bus = Bus::findOrFail($id);

            $bus->update($validated);

            return $this->successResponse(
                message: 'Bus updated successfully'
            );
        } catch (ModelNotFoundException) {
            return $this->errorResponse(message: 'Bus not found');
        } catch (\Exception) {
            return $this->errorResponse(message: 'An error occurred while updating the bus');
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

    // public function driverSelectMenu()
    // {
    //     $tickets = Ticket::select('custom_id')->get();
    //     return response()->json($tickets);
    // }


}
