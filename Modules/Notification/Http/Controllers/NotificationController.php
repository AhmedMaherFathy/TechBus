<?php

namespace Modules\Notification\Http\Controllers;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Driver\Models\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\Notifications\TestNotification;
use Modules\Notification\Notifications\TestFcmNotification;

class NotificationController extends Controller
{
    use HttpResponse;
    public function makeNotification($id)
    {
        Driver::find($id)->notify(new TestFcmNotification());
    }

    public function getDriverNotifications(Request $request)
    {
        $driver = $request->user('driver');

        $notifications = $driver->notifications()
            ->latest() // Order by newest first
            ->cursor() // Uses lazy-loading for memory efficiency
            ->map(function ($notification) {
                return [
                    'data' => $notification->data,
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return $this->successResponse(data: $notifications->values());
    }


    public function updateDriverFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required'
        ]);
        $request->user('driver')->update([
            'fcm_token' => $request->fcm_token
        ]);

        return $this->successResponse(message: "Fcm Token updated successfully");
    }

    
}
