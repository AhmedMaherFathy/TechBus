<?php

namespace Modules\Place\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Place\Database\Factories\RouteFactory;

class Route extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'number',
        'custom_id'
    ];

    // protected static function newFactory(): RouteFactory
    // {
    //     // return RouteFactory::new();
    // }

    public function stations()
    {
        return $this->belongsToMany(Station::class)->withPivot('order')->withTimestamps();
    }
}
