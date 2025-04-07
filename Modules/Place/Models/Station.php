<?php

namespace Modules\Place\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Place\Database\Factories\StationFactory;

class Station extends Model
{
    use HasFactory, Searchable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'custom_id',
        'lat',
        'long',
        'zone_id'
    ];

    // protected static function newFactory(): StationFactory
    // {
    //     // return StationFactory::new();
    // }

    public function routes()
    {
        return $this->belongsToMany(Route::class)->withPivot('order')->withTimestamps();
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
