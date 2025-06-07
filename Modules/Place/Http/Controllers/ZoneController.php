<?php

namespace Modules\Place\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Place\Models\Zone;
use App\Http\Controllers\Controller;
use Modules\Place\Http\Requests\ZoneRequest;
use Modules\Place\Transformers\ZoneResource;
use Symfony\Component\Console\Input\Input;

class ZoneController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $zones = Zone::searchable(request()->input('search'), ['custom_id', 'name'])
                ->fastPaginate(10);

        return $this->paginatedResponse($zones, ZoneResource::class);
    }

    public function show($id)
    {
        $zone = Zone::findOrFail($id); 
        return $this->successResponse(new ZoneResource($zone));
    }
    public function store(ZoneRequest $request)
    {
        $validated = $request->validated();
        $validated['custom_id'] = $this->generateCustomId();
        Zone::create($validated);
        return $this->successResponse(message: 'Zone Created Successfully');
    }

    public function update(ZoneRequest $request,$id)
    {
        $zone = Zone::findOrFail($id);
        $validated = $request->validated();
        $zone->update($validated);
        return $this->successResponse(message: 'Zone Updated Successfully');
    }

    public function destroy($id)
    {
        $zone = Zone::findOrFail($id);
        $zone->delete();
        return $this->successResponse(message: 'Zone Deleted Successfully');
    }

    public function generateCustomId()
    {
        $lastZone = Zone::latest('id')->value('id');
        $nextId = $lastZone ? ($lastZone + 1) : 1;
        $customId = 'Z-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        return $customId;
    }
}
