<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Auth\Database\Factories\OtpFactory;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'code',
        'expires_at'
    ];

    // protected static function newFactory(): OTPFactory
    // {
    //     // return OTPFactory::new();
    // }

    public static function generateOtp($email)
    {
        $otp = rand(1000, 9999);

        $expiresAt = now()->addMinutes(5);

        self::create([
            'identifier' => $email,
            'code' => $otp,
            'expires_at' => $expiresAt
        ]);

        return $otp;
    }
}
