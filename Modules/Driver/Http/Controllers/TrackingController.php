<?php

namespace Modules\Driver\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Driver\Events\DriverLocation;
use Modules\Driver\Models\Driver;

class TrackingController extends Controller
{
    use HttpResponse;

    public function updateDriverLocation(Request $request , $id)
    {
        $driver = Driver::with('bus:id,driver_id,plate_number')
                        ->findOrFail($id);

        if (!$driver->bus) {
            return $this->errorResponse(
                message : 'This driver does not have a bus assigned.',
            );
        }
        
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        $driver->update([
            'lat' => $validated['lat'],
            'long' => $validated['long'],
        ]);

        event(new DriverLocation($driver));

        return $this->successResponse(message:'location Updated');
    }
}
