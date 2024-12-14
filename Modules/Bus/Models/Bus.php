<?php

namespace Modules\Bus\Models;

use Modules\Place\Models\Route;
use Modules\Driver\Models\Driver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Ticket\Models\Ticket;

// use Modules\Bus\Database\Factories\BusFactory;

class Bus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'plate_number',
        'status',
        'custom_id',
        'driver_id',
        'route_id',
        'ticket_id'
    ];

    // protected static function newFactory(): BusFactory
    // {
    //     // return BusFactory::new();
    // }
    
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
    
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
