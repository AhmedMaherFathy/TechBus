<?php

namespace Modules\Place\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Place\Models\Zone;
use Modules\Place\Models\Route;
use Modules\Place\Models\Station;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Place\Transformers\RouteResource;

class PlaceController extends Controller
{
    public function getZone($value)
    {
        // DB::listen(fn($e) => info($e->toRawSql()));

        // $zones = cache()->remember("zones_search_{$value}", 600, function() use ($value) {
        //     return Zone::Searchable($value)->get();
        // });  //one solution

        $zones = Zone::Searchable($value)->limit(10)->get(['id', 'custom_id', 'name']);

        return response()->json(["data" => $zones]);
    }

    public function getStation($id)
    {
        // DB::listen(fn($e)=> info($e->toRawSql()));

        $stations = Zone::find($id)->stations()->get();
        return response()->json(["data" => $stations]);
    }


    public function getEndStation($stationId)
    {
        $relatedStations = DB::table('stations')
            ->join('route_station', 'stations.id', '=', 'route_station.station_id')
            ->join('routes', 'route_station.route_id', '=', 'routes.id')
            ->join('route_station as rs2', 'routes.id', '=', 'rs2.route_id')
            ->join('stations as related_stations', 'rs2.station_id', '=', 'related_stations.id')
            ->where('stations.id', $stationId)
            ->select('related_stations.*')
            ->distinct()
            ->get();

        return response()->json(["data" => $relatedStations]);
    }


    // public function getBusNumbers(Request $request)
    // {
    //     $request->validate([
    //         'start_station' => 'required|exists:stations,id',
    //         'end_station' => 'required|exists:stations,id',
    //     ]);

    //     $startStationId = $request->input('start_station');
    //     $endStationId = $request->input('end_station');

    //     // Fetch all routes containing the start station
    //     $startRoutes = DB::table('route_station')
    //         ->where('station_id', $startStationId)
    //         ->pluck('route_id');

    //     // Fetch all routes containing the end station
    //     $endRoutes = DB::table('route_station')
    //         ->where('station_id', $endStationId)
    //         ->pluck('route_id');

    //     // Find routes common to both start and end stations
    //     $commonRoutes = $startRoutes->intersect($endRoutes);

    //     if ($commonRoutes->isEmpty()) {
    //         return response()->json([
    //             'message' => 'No buses available between these stations.',
    //             'data' => [],
    //         ]);
    //     }

    //     // Filter routes where the start station comes before the end station
    //     $validRoutes = [];
    //     foreach ($commonRoutes as $routeId) {
    //         $startOrder = DB::table('route_station')
    //             ->where('route_id', $routeId)
    //             ->where('station_id', $startStationId)
    //             ->value('order');

    //         $endOrder = DB::table('route_station')
    //             ->where('route_id', $routeId)
    //             ->where('station_id', $endStationId)
    //             ->value('order');

    //         if ($startOrder < $endOrder) {
    //             $validRoutes[] = $routeId;
    //         }
    //     }

    //     // Fetch route details
    //     $routes = Route::with(['stations' => function ($query) {
    //         $query->select('name', 'lat', 'long');
    //     }])
    //         ->whereIn('id', $validRoutes)
    //         ->get(['id', 'name', 'number'])
    //         ->each(function ($route) {
    //             $route->stations->makeHidden('pivot');
    //         });
    //     $routes[0]['estimated_time'] = count($routes[0]->stations) * 7;
    //     return response()->json([
    //         'data' => RouteResource::collection($routes),
    //     ]);
    // }
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

        // Find common routes that contain both stations
        $commonRoutes = $startRoutes->intersect($endRoutes);

        if ($commonRoutes->isEmpty()) {
            return response()->json([
                'message' => 'No buses available between these stations.',
                'data' => [],
            ]);
        }

        // Process routes and determine direction
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

            if ($startOrder !== null && $endOrder !== null) {
                $validRoutes[] = [
                    'route_id' => $routeId,
                    'start_order' => min($startOrder, $endOrder),
                    'end_order' => max($startOrder, $endOrder),
                    'reverse' => $startOrder > $endOrder, // Check if route should be reversed
                ];
            }
        }

        // Fetch route details and adjust stations order
        $routes = Route::with(['stations' => function ($query) use ($validRoutes) {
            foreach ($validRoutes as $route) {
                $query->whereIn('route_station.route_id', [$route['route_id']])
                    ->whereBetween('route_station.order', [$route['start_order'], $route['end_order']])
                    ->select('stations.name', 'stations.lat', 'stations.long', 'route_station.order')
                    ->orderBy('route_station.order', $route['reverse'] ? 'desc' : 'asc'); // Reverse order if needed
            }
        }])
            ->whereIn('id', array_column($validRoutes, 'route_id'))
            ->get(['id', 'name', 'number'])
            ->each(function ($route) {
                $route->stations->makeHidden('pivot');
            });

        // Calculate estimated time for each route
        $routes->each(function ($route) {
            $route->estimated_time = count($route->stations) * 7; // Example time calculation
        });

        return response()->json([
            'data' => RouteResource::collection($routes),
        ]);
    }

    /** 
     * driver app => driver route in order with lat & long
     */
    public function getDriverStations(Request $request)
    {
        $driverID = $request->user('driver')->custom_id;

        $stations =DB::table('stations')
                                        ->join('route_station', 'stations.id', '=', 'route_station.station_id')
                                        ->join('routes', 'route_station.route_id', '=', 'routes.id')
                                        ->join('buses', 'routes.custom_id', '=', 'buses.route_id')
                                        ->where('buses.driver_id', $driverID)
                                        ->select('stations.name', 'stations.lat', 'stations.long','route_station.order')
                                        ->orderBy('route_station.order')
                                        ->get();

        return response()->json([
                                "data" => $stations,
                                "message" => "success",
                                "status" => 200
                                ]);
    }
}
