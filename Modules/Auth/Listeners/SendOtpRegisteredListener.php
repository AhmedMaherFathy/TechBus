<?php

namespace Modules\Auth\Listeners;

use Modules\Auth\Emails\SendOtp;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtpRegisteredListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        info('email sent successfully');
        Mail::to($event->email)->send(new SendOtp(
            $event->code,
            $event->name
        ));
    }
}
