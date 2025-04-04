<?php

namespace Modules\Place\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Place\Models\Route;
use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Modules\Place\Transformers\RouteResource;

class RouteController extends Controller
{
    use HttpResponse;

    public function index()
    {
        return $this->paginatedResponse(Route::with('stations:name')->fastPaginate(),RouteResource::class);
    }


    public function create()
    {
        return view('place::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('place::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('place::edit');
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
