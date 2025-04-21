<?php

namespace Modules\Driver\Http\Controllers;

use Modules\Bus\Models\Bus;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Modules\Driver\Models\Driver;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Driver\Events\AllDriverLocations;
use Modules\Driver\Events\DriverLocation;

class TrackingController extends Controller
{
    use HttpResponse;

    public function updateDriverLocation(Request $request)
    {
        // DB::listen(fn($query) => info($query->toRawSql()));
        // info($request->user('driver'));
        // $driver = $request->user('driver')->only('id','custom_id');
        // info("continue");
        // info("$driver");die;
        $driver = DB::table('drivers')
            ->select('id', 'custom_id')
            ->where('id', $request->user('driver')->id)
            ->first();

            // info($driver);die;
        if (!DB::table('buses')->where('driver_id', $driver->custom_id)->exists()) {
            return $this->errorResponse(
                message: 'This driver does not have a bus assigned.',
            );
        }

        $validated = $request->validate([
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        // Benchmark::dd(fn() =>
        // $driver->update([
        //     'lat' => $validated['lat'],
        //     'long' => $validated['long'],
        // ]),5);

        // Benchmark::dd(fn() =>
        // DB::table('drivers')
        //     ->where('id', $driver->id)
        //     ->update([
        //         'lat' => $validated['lat'],
        //         'long' => $validated['long'],
        //     ]);
        // ,5);

        DB::update('UPDATE drivers SET lat = ?, `long` = ? WHERE id = ?', [
            $validated['lat'],
            $validated['long'],
            $driver->id
        ]);

        // info($driver->custom_id);die;

        // event(new DriverLocation(
        //                             $driver->id,
        //                             $driver->custom_id,
        //                             $validated['lat'],
        //                             $validated['long']
        //                         ));
        
        $this->allDriverLocations();

        return response()->json(['status' => 200]);
        // return $this->successResponse(message: 'location Updated');
    }

    public function disableLocation()
    {
        $driver = request()->user('driver');

        if (!$driver->bus) {
            return $this->errorResponse(
                message: 'This driver does not have a bus assigned.',
            );
        }

        $driver->update([
            'lat' => null,
            'long' => null,
        ]);
        event(new DriverLocation(
                                    $driver->id,
                                    $driver->custom_id,
                                    null,
                                    null
                                ));


        return $this->successResponse(message: 'location disabled');
    }



    public function allDriverLocations()
    {
        $drivers = DB::table('drivers')
            ->select('custom_id', 'lat', 'long')
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->get();
            // ->toArray();

        event(new AllDriverLocations(
            $drivers
        ));

        return response()->json([
            'status' => 200
        ]);
    }

    public function getActiveBuses()
    {
        $buses = Driver::with(['bus','bus.route'])
            ->whereHas('bus')
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->get()
            ->map(function ($driver) {
                return [
                    'driverId' => $driver->id,
                    'number' => $driver->bus->route->number,
                    'plateNumber' => $driver->bus->plate_number,
                    'lat' => $driver->lat,
                    'long' => $driver->long,
                ];
            });

        return response()->json([
            'data' => $buses
        ]);
    }
}
