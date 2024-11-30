<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Http\Requests\AdminRequest;
use Modules\Admin\Transformers\AdminResource;
use Modules\Auth\Models\Admin;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use HttpResponse;

    public function index()
    {
        $admin = Admin::paginate(10);
        return $this->paginatedResponse($admin ,AdminResource::class, message:'Admin Fetched Successfully');
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

    public function delete($id)
    {
        try{
        $user = Admin::findOrFail($id);
        $user->delete();

        return $this->successResponse(message:"Admin deleted successfully");
        }catch(\Exception $e){
            return $this->errorResponse(message:"No Admin found");
        }
    }

    public function show($id)
    {
        $admin = Admin::find($id);

        if($admin)
            return $this->successResponse(AdminResource::make($admin),message:"Fetched Successfully");
        else
            return $this->successResponse(message:"Admin Not found");
    }
}
