<?php

namespace Modules\Driver\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Driver\Database\Factories\DriverFactory;

class Driver extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable =[
        'custom_id',
        'full_name',
        'email',
        'phone',
        'password',
        'status',
        'national_id',
        'Driver_license',

    ];

    protected $hidden = ['password'];
    // protected static function newFactory(): DriverFactory
    // {
    //     // return DriverFactory::new();
    // }
}
