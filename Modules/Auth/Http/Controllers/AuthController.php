<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\SendOtp;
use Modules\Auth\Http\Requests\UserRegisterRequest;
use Modules\Auth\Http\Requests\VerifyRequest;
use Modules\Auth\Models\Otp;

class AuthController extends Controller
{
    use HttpResponse;
    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();
        try {
            // $user = null ;
            // $code = null;
            // DB::transaction(function () use ($validated, &$user , &$code) {

            $lastUser = User::latest('id')->first();
            $nextId = $lastUser ? ( (int)(str_replace('U-', '', $lastUser->id)) + 1) : 1;
            $customId = 'U-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            
                $user = User::create([
                    'custom_id' => $customId,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                $otp = Otp::generateOtp($user['email']);
                // info($otp->code); die;
            // });
            Mail::to($user->email)->send(new SendOtp(
                $otp->code,
                $user['first_name']
            ));


            return $this->successResponse(data:[
                'id' => $user->custom_id,
                'email'=> $user->email,
                'first_name' => $user['first_name'],
                'otp_expires_at' => $otp->expires_at
            ],
            message: 'Registered successfully');

        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage());
        }
    }

    public function verifyOtp(VerifyRequest $request)
    {
        $validated = $request->validated();

        $otpRecord = Otp::where('identifier',$validated['email'])->first();
        
        if(!$otpRecord || $otpRecord->code != $validated['otp'])
        {
            return $this->errorResponse(message: 'Invalid otp');
        }
        
        if($otpRecord->expires_at < now())
        {
            $otpRecord->delete();
            return $this->errorResponse(message: 'Otp expired');
        }

        User::where('email',$validated['email'])->first()->MarkEmailAsVerified();

        $otpRecord->delete();

        return $this->successResponse(message:"Verified Successfully please login");
    }

    public function login()
    {

    }
}
