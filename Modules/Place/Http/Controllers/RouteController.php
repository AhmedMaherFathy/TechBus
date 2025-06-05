<?php

namespace Modules\Place\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Place\Models\Route;
use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\DB;
use Modules\Place\Http\Requests\RouteRequest;
use Modules\Place\Transformers\RouteStationResource;

class RouteController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $routes = Route::with('stations')
                                        ->searchable(request()->input('search'), ['custom_id', 'name' , 'number'])
                                        ->fastPaginate();

        return $this->paginatedResponse($routes ,RouteStationResource::class);
    }

    public function store(RouteRequest $request)
    {
        $validated = $request->validated();
        $validated['custom_id'] = $this->generateCustomId();
        
        DB::transaction(function () use ($validated) {
            $route = Route::create($validated);
            if(isset($validated['stations'])){
                $route->stations()->attach($validated['stations']);
            }
        });
        
        return $this->successResponse(message:"Route Created Successfully");
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return $this->successResponse(new RouteStationResource(Route::with('stations')->findOrFail($id)));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(RouteRequest $request, $id)
    {
        $validated = $request->validated();
        $route = Route::with('stations')->findOrFail($id);

        DB::transaction(function () use ($route, $validated) {
            (isset($validated['stations'])) ? $route->stations()->sync($validated['stations']) : $route->stations()->detach();
            $route->update($validated);
        });

        return $this->successResponse(message:"Route Updated Successfully");
    }
    

    public function destroy($id)
    {
        $route = Route::findOrFail($id);

        DB::transaction(function () use ($route) {
            $route->stations()->detach();
            $route->delete();
        });
        
        return $this->successResponse(message:"Route Deleted Successfully");
    }

    public function generateCustomId()
    {
        $lastRoute = Route::latest('id')->value('id');
        $nextId = $lastRoute ? ($lastRoute + 1) : 1;
        $customId = 'R-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        return $customId;
    }
}
