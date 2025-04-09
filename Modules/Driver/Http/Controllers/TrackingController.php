<?php

namespace Modules\Driver\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\DB;
use Modules\Driver\Events\DriverLocation;
use Modules\Driver\Models\Driver;

class TrackingController extends Controller
{
    use HttpResponse;

    public function updateDriverLocation(Request $request )
    {
        $driver = $request->user('driver');

        if (!$driver->bus) {
            return $this->errorResponse(
                message : 'This driver does not have a bus assigned.',
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
    
        $driver->refresh();

        event(new DriverLocation($driver));

        return $this->successResponse(message:'location Updated');
    }
}
