<?php

namespace Modules\Statistics\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Modules\Bus\Models\Bus;
use Illuminate\Http\Request;
use Modules\Place\Models\Route;
use Modules\Driver\Models\Driver;
use App\Http\Controllers\Controller;

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

    public function getHourlyTicketSales()
    {
        $today = Carbon::today()->toDateString();

        $route = Route::with(['buses.ticket.users'] 
        // => function($query) use ($today) {
        //     $query->wherePivot('date', $today)
        //           ->withPivot(['time']); // Explicitly include pivot columns
        )
        ->where('custom_id', 'R-001')
        ->firstOrFail();
        return response()->json($route);
            // ->flatMap(function ($bus) {
            //     return $bus->ticket->users->map(function ($user) {
            //         return Carbon::parse($user->pivot->time)->format('H:00');
            //     });
            // })
            // ->countBy()
            // ->map(function ($count, $hour) {
            //     return [
            //         'hour' => $hour,
            //         'tickets_sold' => $count
            //     ];
            // })
            // ->sortKeys()
            // ->values()
            // ->toArray();
    }
}
