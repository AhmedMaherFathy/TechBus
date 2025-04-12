<?php

namespace Modules\Driver\Models;

use Carbon\Carbon;
use Modules\Bus\Models\Bus;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Driver\Database\Factories\DriverFactory;

class Driver extends Model 
{
    use HasApiTokens, HasFactory, MediaAlly;

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
        'driver_license',
        'lat',
        'long',
    ];

    protected $hidden = ['password'];

    protected $guard = ['driver'];

    // protected static function newFactory(): DriverFactory
    // {
    //     // return DriverFactory::new();
    // }

    public function bus()
    {
        return $this->hasOne(Bus::class,'driver_id','custom_id');
    }
    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? Carbon::parse($this->start_time)->format('h:i A') : null;
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? Carbon::parse($this->end_time)->format('h:i A') : null;
    }
}
