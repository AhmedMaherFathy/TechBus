<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use Modules\Ticket\Models\Ticket;
use Modules\Balance\Models\Balance;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'custom_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class,'user_ticket',
        'ticket_id',   // Foreign key in pivot table pointing to the tickets table
        'user_id',     // Foreign key in pivot table pointing to the users table
        'custom_id',   // Local key on the tickets table
        'custom_id'    // Local key on the users table
        );
    }

    public function balance()
    {
        return $this->hasOne(Balance::class,'user_id','custom_id');
    }
}
