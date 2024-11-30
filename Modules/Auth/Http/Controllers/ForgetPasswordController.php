<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Auth\Models\Otp;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\forgetPasswordOtp;
use Modules\Auth\Http\Requests\VerifyRequest;
use Modules\Auth\Http\Requests\forgetPasswordRequest;

class ForgetPasswordController extends Controller
{
    use HttpResponse;

    public function SendOtp(forgetPasswordRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();

        $otp = Otp::generateOtp($user->email);

        Mail::to($user->email)->send(new forgetPasswordOtp(
            $otp->code,
            $user['first_name']
        ));

        return $this->successResponse(data: [
            'id' => $user->id,
            'email' => $user->email,
            'first_name' => $user['first_name'],
            'otp_expires_at' => $otp->expires_at,
        ],
            message: 'Otp sent successfully');
    }

    public function verifyOtp(VerifyRequest $request)
    {
        $validated = $request->validated();

        $otpRecord = Otp::where('identifier', $validated['email'])->first();

        if (! $otpRecord || $otpRecord->code != $validated['otp']) {
            return $this->errorResponse(message: 'Invalid otp');
        }

        if ($otpRecord->expires_at < now()) {
            $otpRecord->delete();

            return $this->errorResponse(message: 'Otp expired');
        }

        User::where('email', $validated['email'])->first()->MarkEmailAsVerified();

        $otpRecord->delete();

        return $this->successResponse(message: 'Verified Successfully please reset password');
    }

    public function resetPassword()
    {

    }
}
