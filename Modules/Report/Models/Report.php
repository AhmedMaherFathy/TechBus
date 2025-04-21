<?php

namespace Modules\Report\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Report\Database\Factories\ReportFactory;

class Report extends Model
{
    
    protected $fillable = [
        'user_id',
        'description',
        'note'
    ];

    // protected static function newFactory(): ReportFactory
    // {
    //     // return ReportFactory::new();
    // }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','custom_id');
    }
    
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }
}
