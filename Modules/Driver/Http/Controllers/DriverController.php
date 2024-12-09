<?php

namespace Modules\Driver\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Driver\Models\Driver;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Driver\Http\Requests\DriverRequest;
use Modules\Driver\Transformers\DriverResource;


class DriverController extends Controller
{
    use HttpResponse;

    public function index()
    {
        $driver = Driver::fastPaginate(10);

        return $this->paginatedResponse($driver, DriverResource::class, message: 'driver Fetched Successfully');
    }


    public function store(DriverRequest $request)
    {
        $validated = $request->validated();
        $customId = $this->generateCustomId();

        $validated['password'] = Hash::make($validated['password']);
        $validated['custom_id'] = $customId;

        $driver = Driver::create($validated);

        if ($request->hasFile('photo')) {
            $driver->attachMedia($request->file('photo'));
        }
        return $this->successResponse(message: 'Driver Created Successfully');
    
    }
    
    /**
     * Generate a custom ID for the driver.
     *
     * @return string
     */
    private function generateCustomId(): string
    {
        $lastUser = Driver::latest('id')->first();
        $nextId = $lastUser ? ((int) (str_replace('D-', '', $lastUser->id)) + 1) : 1;
    
        return 'D-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
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
            return $this->errorResponse(message: 'Driver Not found');
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
                // info("what");die;
                $validated['password'] = Hash::make($validated['password']);
            }
            else{
                unset($validated['password']);
            }

            if ($request->hasFile('photo')) {
                $driver->updateMedia($request->validated('photo'));
            }
            $driver->update($validated);

            return $this->successResponse(DriverResource::make($driver), message: 'Driver updated successfully');
        } catch (\Exception $e) {

            return $this->errorResponse(message: $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = Driver::findOrFail($id);
            $user->detachMedia();
            $user->delete();

            return $this->successResponse(message: 'Driver deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'No Driver found');
        }
    }
}
