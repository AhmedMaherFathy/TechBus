<?php

namespace Modules\Balance\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Balance\Database\Factories\BalanceFactory;

class Balance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'points',
        'user_id'
    ];

    // protected static function newFactory(): BalanceFactory
    // {
    //     // return BalanceFactory::new();
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
