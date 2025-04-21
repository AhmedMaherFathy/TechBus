<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Report\Http\Requests\ReportRequest;
use Modules\Report\Models\Report;
use Modules\Report\Transformers\ReportResource;

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

    public function index()
    {
        $reports = Report::with([
            'user' => function($query) {
                $query->select('custom_id', 'first_name', 'last_name', 'email');
            },
            ])
            ->latest()
            ->fastPaginate();
        
        return $this->paginatedResponse($reports ,ReportResource::class, message: 'Reports retrieved successfully');
    }

    public function show($id)
    {
        $report = Report::with([
            'user' => function($query) {
                $query->select('custom_id', 'first_name', 'last_name', 'email');
            },
            ])
            ->where('id', $id)
            ->firstOrFail();
        
        return $this->successResponse(ReportResource::make($report), message: 'Report retrieved successfully');
    }
    
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        
        return $this->successResponse(message: 'Report deleted successfully');
    }
}