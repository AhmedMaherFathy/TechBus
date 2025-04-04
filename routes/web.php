<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Modules\Place\Models\Route as ModelsRoute;

Route::get('/', function () {
    return view('welcome');
});

Route::get('check-routes',function()
{
    // $route = ModelsRoute::where('number' , 'M9')->with(['stations:id,name,zone_id','stations.zone:id,name'])->first();
    // return $route->stations;

    // $user = User::where('custom_id','U-001')->first();
    // $user->balance->create(['points'=> 10000]);

    // $users = DB::table('users')
    //             ->join('balances','users.custom_id','=','balances.user_id')
    //             ->select(['users.first_name as first','users.last_name','balances.points'])
    //             ->get();

    $collection = collect([
        ['name' => 'Kholoud', 'points' => 1000],
        ['name' => 'Ahmed', 'points' => 500]
    ]);

    return gettype($collection);
});