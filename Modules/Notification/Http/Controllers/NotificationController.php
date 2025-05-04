<?php

namespace Modules\Notification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Notification\Notifications\TestNotification;

class NotificationController extends Controller
{
    public function makeNotification(Request $request)
    {
        $request->user('driver')->notify(new TestNotification());
    }

    public function getDriverNotifications(Request $request)
    {
        $notifications = $request->user('driver')->notifications->map(function ($notification) {
            return [
                'data' => $notification->data,
                'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
            ];
        });
        
        return response()->json($notifications);
    }
}
