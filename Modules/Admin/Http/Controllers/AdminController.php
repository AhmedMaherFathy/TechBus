<?php

namespace Modules\Admin\Http\Controllers;

use App\Traits\HttpResponse;
use Modules\Auth\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Http\Requests\AdminRequest;
use Modules\Admin\Transformers\AdminResource;
use Modules\Admin\Http\Requests\AdminUpdateRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use HttpResponse;

    public function index()
    {
        $admin = Admin::searchable(request()->input('search'), ['phone', 'email', 'custom_id'])
                        ->paginate(10);

        return $this->paginatedResponse($admin, AdminResource::class, message: 'Admin Fetched Successfully');
    }

    public function update(AdminUpdateRequest $request , $id)
    {
        try {
            // Find the driver by ID
            $driver = Admin::findOrFail($id);

            $validated = $request->validated();

            if (isset($validated['password'])) {
                // info("what");die;
                $validated['password'] = Hash::make($validated['password']);
            }
            else{
                unset($validated['password']);
            }

            $driver->update($validated);

            return $this->successResponse(AdminResource::make($driver), message: 'Admin updated successfully');
        } catch (\Exception $e) {

            return $this->errorResponse(message: $e->getMessage());
        }
    }

    public function store(AdminRequest $request)
    {
        $validated = $request->validated();

        $lastUser = Admin::latest('id')->first();
        $nextId = $lastUser ? ((int) (str_replace('A-', '', $lastUser->id)) + 1) : 1;
        $customId = 'A-'.str_pad($nextId, 3, '0', STR_PAD_LEFT);

        $validated['password'] = Hash::make($validated['password']);
        $validated['custom_id'] = $customId;
        Admin::create($validated);

        return $this->successResponse(message: 'Admin Created Successfully');
    }



    public function show($id)
    {
        $admin = Admin::find($id);

        if ($admin) {
            return $this->successResponse(AdminResource::make($admin), message: 'Fetched Successfully');
        } else {
            return $this->successResponse(message: 'Admin Not found');
        }
    }

    public function delete($id)
    {
        try {
            $user = Admin::findOrFail($id);
            // info(Auth::guard('admin')->user()->id);die;
            if (Auth::guard('admin')->user()->id === $user->id) {
                return $this->errorResponse(message: 'You cannot delete your own account.');
            }
            $user->delete();

            return $this->successResponse(message: 'Admin deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'No Admin found');
        }
    }
}
