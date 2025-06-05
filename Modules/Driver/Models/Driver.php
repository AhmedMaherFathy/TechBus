<?php

namespace Modules\Driver\Models;

use App\Models\FcmToken;
use App\Traits\Searchable;
use Carbon\Carbon;
use Modules\Bus\Models\Bus;
use Laravel\Sanctum\HasApiTokens;
use Modules\Ticket\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

// use Modules\Driver\Database\Factories\DriverFactory;

class Driver extends Model 
{
    use HasApiTokens, HasFactory, MediaAlly, Notifiable, Searchable;

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
        'days',
        'start_time',
        'end_time',
        'fcm_token'
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

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class,'driver_ticket',
        'ticket_id',   // Foreign key in pivot table pointing to the tickets table
        'driver_id',     // Foreign key in pivot table pointing to the users table
        'custom_id',   // Local key on the tickets table
        'custom_id'    // Local key on the users table
        );
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
