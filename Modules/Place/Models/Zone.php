<?php

namespace Modules\Place\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Place\Database\Factories\ZoneFactory;

class Zone extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name'
    ];

    // protected static function newFactory(): ZoneFactory
    // {
    //     // return ZoneFactory::new();
    // }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }
}
