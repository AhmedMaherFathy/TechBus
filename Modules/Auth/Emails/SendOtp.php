<?php

namespace Modules\Auth\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $otp, public $name)
    {
        $this->otp = $otp;
        $this->name = $name;
        // info($this->otp); die;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
            ->view('otp', [$this->otp, $this->name])
            ->subject('Your OTP Code');
    }
}
