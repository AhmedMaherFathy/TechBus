<?php

namespace Modules\Place\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'estimated_time' => $this->estimated_time."-".($this->estimated_time+5)." min",
            'stations' => $this->stations
        ];
    }
}
