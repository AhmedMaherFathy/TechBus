<?php

namespace Modules\Driver\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Driver\Http\Requests\DriverRequest;
use Modules\Driver\Models\Driver;
use Modules\Driver\Transformers\DriverResource;
use Illuminate\Support\Facades\Hash;


class DriverController extends Controller
{
    use HttpResponse;


    public function index()
    {
        $driver = Driver::paginate(10);

        return $this->paginatedResponse($driver, DriverResource::class, message: 'driver Fetched Successfully');
    }


    public function store(DriverRequest $request)
    {
        $validated = $request->validated();

        $lastUser = Driver::latest('id')->first();
        $nextId = $lastUser ? ((int) (str_replace('A-', '', $lastUser->id)) + 1) : 1;
        $customId = 'D-'.str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $validated['password'] = Hash::make($validated['password']);
        $validated['custom_id'] = $customId;
        Driver::create($validated);

        return $this->successResponse(message: 'Driver Created Successfully');

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $driver = Driver::find($id);

        if ($driver) {
            return $this->successResponse(DriverResource::make($driver), message: 'Fetched Successfully');
        } else {
            return $this->successResponse(message: 'Driver Not found');
        }

    }



    /**
     * Update the specified resource in storage.
     */
    public function update(DriverRequest  $request, $id)
    {
        try {
            // Find the driver by ID
            $driver = Driver::findOrFail($id);

            $validated = $request->validated();

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            $driver->update($validated);

            return $this->successResponse(DriverResource::make($driver), message: 'Driver updated successfully');
        } catch (\Exception $e) {

            return $this->errorResponse(message: 'Driver not found or update failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = Driver::findOrFail($id);
            $user->delete();

            return $this->successResponse(message: 'Driver deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'No Driver found');
        }
    }
}
