<?php

namespace Modules\Ticket\Models;

use App\Models\User;
use Modules\Bus\Models\Bus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Driver\Models\Driver;

// use Modules\Ticket\Database\Factories\TicketFactory;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'custom_id',
        'qr_code',
        'name',
        'points',
        'status',
    ];

    // protected static function newFactory(): TicketFactory
    // {
    //     // return TicketFactory::new();
    // }

    public function bus()
    {
        return $this->hasOne(Bus::class, 'ticket_id', 'custom_id'); // forign Key , value
    }

    public function users()
    {
        return $this->belongsToMany(User::class,
                                                'user_ticket',
                                                'ticket_id',   // Foreign key in pivot table pointing to the tickets table
                                                'user_id',     // Foreign key in pivot table pointing to the users table
                                                'custom_id',   // Local key on the tickets table
                                                'custom_id'    // Local key on the users table
                                    );
    }

    public function drivers()
    {
        return $this->belongsToMany(Driver::class,
                                                'driver_ticket',
                                                'ticket_id',   // Foreign key in pivot table pointing to the tickets table
                                                'driver_id',     // Foreign key in pivot table pointing to the users table
                                                'custom_id',   // Local key on the tickets table
                                                'custom_id'    // Local key on the users table
                                    );
    }
}
