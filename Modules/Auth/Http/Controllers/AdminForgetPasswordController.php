<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\ForgetPasswordOtp;
use Modules\Auth\Http\Requests\AdminForgetPasswordRequest;
use Modules\Auth\Http\Requests\ForgetPasswordRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Http\Requests\VerifyRequest;
use Modules\Auth\Models\Admin;
use Modules\Auth\Models\Otp;

class AdminForgetPasswordController extends Controller
{
    use HttpResponse;

    public function SendOtp(AdminForgetPasswordRequest $request)
    {
        $validated = $request->validated();
        
        $user = Admin::where('email', $validated['email'])->firstOrFail();

        $otp = Otp::generateOtp($user->email);

        Mail::to($user->email)->send(new ForgetPasswordOtp(
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

        $data = DB::table('password_reset_tokens')->updateOrInsert([
            'email' => $validated['email'],
        ], [
            'email' => $validated['email'],
            'token' => Hash::make($validated['otp']),
            'created_at' => now(),
        ]);

        $fetchedData = DB::table('password_reset_tokens')
        ->where('email', $validated['email'])
        ->first();

        $otpRecord->delete();

        return $this->successResponse(data: $fetchedData, message: 'Verified Successfully please reset password');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        try {
            $updatePassword = DB::table('password_reset_tokens')
                ->where([
                    'email' => $validated['email'],
                    'token' => $validated['token'],
                ])
                ->first();

            if (! $updatePassword) {
                return $this->errorResponse(message: 'Invalid token!');
            }
            Admin::query()->where('email', $validated['email'])
                ->update(['password' => Hash::make($validated['new_password'])]);

            DB::table('password_reset_tokens')->where(['email' => $validated['email']])->delete();

            return $this->successResponse(message: 'Your password has been changed!');
        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage());
        }
    }
}
