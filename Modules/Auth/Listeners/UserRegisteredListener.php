<?php

namespace Modules\Auth\Listeners;

use Modules\Auth\Emails\SendOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisteredListener implements ShouldQueue
{
    use InteractsWithQueue;
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle( $event): void
    {
        // info("finish here");  die;
        Mail::to($event->email)->send(new SendOtp(
            $event->code,
            $event->name
        ));
    }
}
