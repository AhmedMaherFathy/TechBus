<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $otp)
    {
        $this->otp = $otp;
        // info($this->otp); die;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
                    ->view('otp',[$this->otp])
                    ->subject('Your OTP Code') ;
    }
}
