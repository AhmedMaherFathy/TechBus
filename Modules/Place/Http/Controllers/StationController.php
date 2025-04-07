<?php

namespace Modules\Place\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Place\Models\Station;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Modules\Place\Http\Requests\StationRequest;
use Modules\Place\Transformers\StationResource;

class StationController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $stations = Station::with('zone:id,custom_id,name')->fastPaginate(10);
        return $this->paginatedResponse($stations, StationResource::class);
    }

    public function show($id)
    {
        $station = Station::with('zone:id,custom_id,name')->findOrFail($id); 
        return $this->successResponse(new StationResource($station));
    }

    public function store(StationRequest $request)
    {
        $validated = $request->validated();
        $validated['custom_id'] = $this->generateCustomId();
        Station::create($validated);
        return $this->successResponse(message: 'Station Created Successfully');
    }

    public function update(StationRequest $request,$id)
    {
        $station = Station::findOrFail($id);
        $validated = $request->validated();
        $station->update($validated);
        return $this->successResponse(message: 'Station Updated Successfully');
    }

    public function destroy($id)
    {
        $station = Station::findOrFail($id);
        $station->delete();
        return $this->successResponse(message: 'Station Deleted Successfully');
    }

    public function generateCustomId()
    {
        $lastStation = Station::latest('id')->value('id');
        $nextId = $lastStation ? ($lastStation + 1) : 1;
        $customId = 'S-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        return $customId;
    }

    public function getStations(Request $request)
    {
        $stations = Station::Searchable($request->input('search'))
            ->select('id','name')
            ->cursor(10);

        return response()->json([
            'data' => $stations,
        ]);
    }
}
