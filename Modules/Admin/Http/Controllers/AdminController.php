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

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
