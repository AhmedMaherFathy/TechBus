<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\SendOtp;
use Modules\Auth\Events\UserRegistered;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\UserRegisterRequest;
use Modules\Auth\Http\Requests\VerifyRequest;
use Modules\Auth\Models\Admin;
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
        $validated = $request->validated();
        $guardName = $guard == 'web' ? 'user' : $guard;
        // Attempt to authenticate the user or admin based on the provided guard
        $admin = $model::where('email',$validated['email'])->first();
        // Auth::guard($guard)->attempt($validated)
        if ($admin && Hash::check($validated['password'], $admin->password)) {
            // $authUser = Auth::guard($guard)->user();
            // info($authUser);die;
            // Check if it's a user or admin and verify email verification status for users
            if ($guard === 'web' && is_null($admin->email_verified_at)) {
                return $this->errorResponse(message: 'Please verify your email address before logging in.');
            }

            $token = $admin->createToken($guardName.'-token')->plainTextToken;

            return $this->successResponse(
                data: [
                    'user' => $admin,
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
}
