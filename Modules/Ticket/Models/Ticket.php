<?php

namespace Modules\Ticket\Models;

use App\Models\User;
use Modules\Bus\Models\Bus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Ticket\Database\Factories\TicketFactory;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
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
        return $this->hasOne(Bus::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class,'user_ticket');
    }
}
