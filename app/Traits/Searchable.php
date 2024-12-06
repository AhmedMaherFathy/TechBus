<?php

namespace App\Traits;

trait Searchable
{
    public function scopeSearchable($query , $value ,array $keys = ['name'] )
    {
        return $query->whereAny($keys, "like" , "%$value%");
    }
}
