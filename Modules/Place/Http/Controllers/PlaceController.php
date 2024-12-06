<?php

namespace Modules\Place\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Place\Models\Station;
use Modules\Place\Models\Zone;

class PlaceController extends Controller
{
    public function getZone($value)
    {
        DB::listen(fn($e)=> info($e->toRawSql()));
        
        // $zones = cache()->remember("zones_search_{$value}", 600, function() use ($value) {
        //     return Zone::Searchable($value)->get();
        // });  //one solution

        $zones = Zone::Searchable($value)->limit(10)->get();  //if does not work on server I should change to s not S

        return response()->json(["data"=>$zones]);
    }

    public function getStation($id)
    {
        // DB::listen(fn($e)=> info($e->toRawSql()));

        $stations = Zone::find($id)->stations()->get();        
        return response()->json(["data"=>$stations]);
    }

    public function getEndStation()
    {
        $stations = Station::all();
        return response()->json(["data"=>$stations]);
    }
    
    public function getBusNumbers(Request $request)
    {
        $request->validate([
            'start_station' => 'required|exists:stations,id',
            'end_station' => 'required|exists:stations,id',
        ]);

        $startStationId = $request->input('start_station');
        $endStationId = $request->input('end_station');

        // Fetch all routes containing the start station
        $startRoutes = DB::table('route_station')
            ->where('station_id', $startStationId)
            ->pluck('route_id');

        // Fetch all routes containing the end station
        $endRoutes = DB::table('route_station')
            ->where('station_id', $endStationId)
            ->pluck('route_id');

        // Find routes common to both start and end stations
        $commonRoutes = $startRoutes->intersect($endRoutes);

        if ($commonRoutes->isEmpty()) {
            return response()->json([
                'message' => 'No buses available between these stations.',
                'data' => [],
            ]);
        }

        // Filter routes where the start station comes before the end station
        $validRoutes = [];
        foreach ($commonRoutes as $routeId) {
            $startOrder = DB::table('route_station')
                ->where('route_id', $routeId)
                ->where('station_id', $startStationId)
                ->value('order');

            $endOrder = DB::table('route_station')
                ->where('route_id', $routeId)
                ->where('station_id', $endStationId)
                ->value('order');

            if ($startOrder < $endOrder) {
                $validRoutes[] = $routeId;
            }
        }

        // Fetch route details
        $routes = DB::table('routes')
            ->whereIn('id', $validRoutes)
            ->get(['id', 'name', 'number']);

        return response()->json([
            'data' => $routes,
        ]);
    }
}
