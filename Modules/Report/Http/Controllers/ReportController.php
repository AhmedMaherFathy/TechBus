<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Report\Http\Requests\ReportRequest;
use Modules\Report\Models\Report;

class ReportController extends Controller
{
    use HttpResponse;

    public function store(ReportRequest $request)
    {
        $validated = $request->validated();
        
        $validated['user_id'] = Auth::guard('user')->user()->custom_id;
        
        Report::create($validated);
        
        return $this->successResponse(message:'Report submitted successfully');
    }

}