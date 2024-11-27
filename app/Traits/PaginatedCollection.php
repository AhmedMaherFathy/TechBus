<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait PaginatedCollection
{ 
    public function scopePaginatedCollection(Builder $builder)
    {
        $perPage = request()->input('per_page') ?: 5 ;
        
        return $builder->fastPaginate($perPage );
    }
}
