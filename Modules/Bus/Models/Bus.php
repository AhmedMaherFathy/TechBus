<?php

namespace Modules\Bus\Models;

use App\Traits\Searchable;
use Modules\Place\Models\Route;
use Modules\Driver\Models\Driver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Ticket\Models\Ticket;

// use Modules\Bus\Database\Factories\BusFactory;

class Bus extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'plate_number',
        'status',
        'custom_id',
        'license',
        'driver_id',
        'route_id',
        'ticket_id'
    ];

    // protected static function newFactory(): BusFactory
    // {
    //     // return BusFactory::new();
    // }

    public function casts()
    {
        return [
            'created_at' => 'datetime:Y-m-d,h:i:A'
        ];
    }
    
    public function route()
    {
        return $this->belongsTo(Route::class,'route_id','custom_id');
    }
    
    public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id','custom_id');
    }
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class,'ticket_id','custom_id');
    }
}
