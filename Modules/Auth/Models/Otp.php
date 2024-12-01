<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Auth\Database\Factories\OtpFactory;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'code',
        'expires_at',
    ];

    // protected static function newFactory(): OTPFactory
    // {
    //     // return OTPFactory::new();
    // }

    public static function generateOtp($email)
    {
        $otp = rand(1000, 9999);

        $expiresAt = now()->addMinutes(5)->format('Y-m-d H:i:s');

        $otpRecord = self::updateOrInsert(
            ['identifier' => $email], // Matching condition
            [
                'code' => $otp,
                'expires_at' => $expiresAt,
                'updated_at' => now(),
            ]
        );
    
        // Fetch and return the updated or created record
        return self::where('identifier', $email)->first();
    }
}
