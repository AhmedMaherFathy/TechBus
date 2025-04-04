<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Modules\Auth\Models\Otp;
use Modules\Auth\Models\Admin;
use Modules\Auth\Emails\SendOtp;
use Modules\Driver\Models\Driver;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Events\UserRegistered;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\VerifyRequest;
use Modules\Auth\Http\Requests\UserRegisterRequest;

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

            $lastUser = User::latest('id')->value('id');
            $nextId = $lastUser ? ($lastUser + 1) : 1;
            $customId = 'P-'.str_pad($nextId, 3, '0', STR_PAD_LEFT);
            // info($customId); die;
            $user = User::create([
                'custom_id' => $customId,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            $otp = Otp::generateOtp($user['email']);
            // });
            
            // dispatch(function () use ($user, $otp) {
            defer(function() use($user , $otp){
                Mail::to($user->email)->send(new SendOtp(
                    $otp->code,
                    $user['first_name']
                ));
            });
            // event(new UserRegistered($user->email , $otp->code ,$user['first_name']));
            // UserRegistered::dispatch($user->email , $otp->code ,$user['first_name']);

            return $this->successResponse(data: [
                'id' => $user->custom_id,
                'email' => $user->email,
                'first_name' => $user['first_name'],
                'otp_expires_at' => $otp->expires_at,
            ],
                message: 'Registered successfully');

        } catch (\Exception $e) {
            return $this->errorResponse(message: $e->getMessage());
        }
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

        return $this->successResponse(message: 'Verified Successfully please login');
    }

    public function login(LoginRequest $request ,$model , $guard = 'web'  )
    {
        // DB::listen(fn($e)=> info($e->toRawSql()));
        $validated = $request->validated();
        $guardName = $guard == 'web' ? 'user' : $guard;

        if($guardName == 'user'){
            $user = $model::select('id','custom_id' ,'first_name', 'email','password','email_verified_at') 
                            ->with([
                                'balance' => function ($query) {
                                    $query->select('id', 'points', 'user_id'); 
                                }
                            ])
                            ->where('email', $validated['email'])
                            ->first();
            if(!$user){
                return $this->errorResponse(message: 'Incorrect Email or Password');
            }
            $data['balance'] = $user->balance;
        }else{
            $user = $model::where('email',$validated['email'])->first();
        }

        if ($user && Hash::check($validated['password'], $user->password)) {
            if ($guard === 'web' && is_null($user->email_verified_at)) {
                return $this->errorResponse(message: 'Please verify your email address before logging in.');
            }
            // info($guardName);
            $token = $user->createToken($guardName.'-token')->plainTextToken;

            return $this->successResponse(
                data: [
                    'user' => $user,
                    'token' => $token,
                ],
                message: 'Logged in successfully.',
            );
        }

        return $this->errorResponse(message: 'Incorrect Email or Password');
    }

    public function userLogin(LoginRequest $request)
    {
        return $this->login($request , User::class , 'web' );
    }

    public function adminLogin(LoginRequest $request)
    {
        return $this->login($request , Admin::class, 'admin');
    }

    public function driverLogin(LoginRequest $request)
    {
        return $this->login($request , Driver::class, 'driver');
    }

    public function logout(Request $request, $guard)
    {
        $user = $request->user($guard);
        // $user = Auth::guard($guard)->user();
        $user->currentAccessToken()->delete();
        //$user->tokens()->delete();  //lo 3aiz a3ml logout mn kl el aghza 

        return $this->successResponse(message: 'Logged out successfully');
    }

    public function userLogout(Request $request)
    {
        return $this->logout($request, 'user');
    }

    public function adminLogout(Request $request)
    {
        return $this->logout($request, 'admin');
    }

    public function driverLogout(Request $request)
    {
        return $this->logout($request, 'driver');
    }
}
