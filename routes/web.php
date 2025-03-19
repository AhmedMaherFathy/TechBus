<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Modules\Place\Models\Route as ModelsRoute;

Route::get('/', function () {
    return view('welcome');
});

Route::get('check-routes',function()
{
    $route = ModelsRoute::where('number' , 'M9')->with(['stations:id,name,zone_id','stations.zone:id,name'])->first();
    return $route->stations;

    $user = User::where('custom_id','U-001')->first();
    $user->balance->create(['points'=> 10000]);
});