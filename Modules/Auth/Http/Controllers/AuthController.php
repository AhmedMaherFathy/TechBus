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
                $user = User::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ]);

                $code = Otp::generateOtp($user['email']);
            // });
            Mail::to($user->email)->send(new SendOtp(
                $code
            ));


            return $this->successResponse(data:['email'=>$user->email],message: 'Registered successfully');
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
