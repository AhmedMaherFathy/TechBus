<?php

namespace Modules\Statistics\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Modules\Bus\Models\Bus;
use Illuminate\Http\Request;
use Modules\Place\Models\Route;
use Modules\Driver\Models\Driver;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use function Laravel\Prompts\select;

class StatisticsController extends Controller
{
    public function numberOfEmployees()
    {
        $activeBuses = Driver::has('bus')
            ->whereNotNull('lat')
            ->whereNotNull('lat')
            ->count();

        $totalBuses = Bus::count();

        $totalDrivers = Driver::count();

        $totalPassengers = User::count();

        return response()->json(
            data: [
                "ActiveBuses" => $activeBuses,
                "TotalBuses" => $totalBuses,
                "TotalDrivers" => $totalDrivers,
                "TotalPassengers" => $totalPassengers
            ],
            status: 200
        );
    }

    public function usersRegisteredAt()
    {
        $dateRange = collect(range(0, 7))
            ->map(fn($days) => now()->subDays($days)->format('Y-m-d'))
            ->reverse();

        $registrations = User::whereBetween('created_at', [
            now()->subDays(7)->startOfDay(),
            now()->endOfDay()
        ])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as registrations')
            ->groupBy('date')
            ->pluck('registrations', 'date');

        $result = $dateRange->map(function ($date) use ($registrations) {
            return [
                'date' => $date,
                'day' => Carbon::parse($date)->dayName,
                'registrations' => $registrations->get($date, 0)
            ];
        })->values(); // This removes the numeric keys

        return response()->json($result);
    }


    public function getHourlyTicketSales(Request $request)
    {
        // Validate user input
        $validated = $request->validate([
            'route_id' => 'required|string|exists:routes,custom_id',
            'date' => 'required|date',
        ]);

        $routeId = $validated['route_id'];
        $date = $validated['date']; // Date provided by user

        $route = Route::with('buses:route_id,ticket_id,driver_id')
            ->where('custom_id', $routeId)
            ->select('custom_id')
            ->firstOrFail();

        $tickets = array_column($route->buses->toArray(), 'ticket_id');

        $times1 = DB::table('user_ticket')
            ->whereIn('ticket_id', $tickets)
            ->where('date', $date)
            ->pluck('time');

        $times2 = DB::table('driver_ticket')
            ->whereIn('ticket_id', $tickets)
            ->where('date', $date)
            ->pluck('time');

        $times = $times1->merge($times2);
        
        $grouped = $times->map(function ($time) {
            return Carbon::createFromFormat('H:i:s', $time)->format('H');
        })
            ->countBy();

        $fullHours = collect(range(0, 23))->map(function ($hour) use ($grouped) {
            $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT); // 00, 01, 02...
            return [
                'hour' => $formattedHour,
                'count' => $grouped->get($formattedHour, 0),
            ];
        });

        return response()->json($fullHours);
    }

    public function getRouteIds()
    {
        $routesIds = Route::pluck('custom_id');
        return $routesIds;
    }

    public function ticketsHistory()
    {
        $driverTicket = DB::table('driver_ticket')->count();
        $userTicket = DB::table('user_ticket')->count();
        $soldTickets = $driverTicket + $userTicket;
        
        $validQr = DB::table('tickets')->where('status','valid')->count();

        return response()->json([
            "soldTickets" => $soldTickets,
            "validQr" => $validQr,
        ]);
    }
}
