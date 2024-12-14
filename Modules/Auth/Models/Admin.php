<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Auth\Database\Factories\AdminFactory;

// use Modules\Auth\Database\Factories\AdminFactory;

class Admin extends Authenticatable 
{
    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'custom_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $guard = 'admin';

    protected static function newFactory(): AdminFactory
    {
        return AdminFactory::new();
    }
}
