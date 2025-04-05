<?php

namespace Modules\Balance\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Balance\Http\Requests\BalanceRequest;
use Modules\Balance\Models\Balance;

class BalanceController extends Controller
{
    use HttpResponse;

    public function userAddBalance(BalanceRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user('user');
        $validated['user_id'] = $user->custom_id;
        
        DB::transaction(function () use ($user, $validated) {
            $balance = $user->balance()->firstOrNew();
            
            if ($balance->exists) {
                $balance->increment('points', $validated['points']);
            } else {
                $balance->fill($validated)->save();
            }
        });

        return $this->successResponse(
            data:[
                "Points" => $user->balance->points,
                "UserId" => $user->balance->user_id
            ],
            message:'Balance added successfully', 
        );
    }
}
