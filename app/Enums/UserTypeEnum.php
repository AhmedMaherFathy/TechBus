<?php

namespace App\Enums;

enum UserTypeEnum
{
    const Admin = 0;

    const Driver = 0;
    
    const Passenger = 0;

    public static function userTypes()
    {
        return[
            self::Admin,
            self::Driver,
            self::Passenger
        ];
    }
}
