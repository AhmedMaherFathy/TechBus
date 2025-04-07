<?php

namespace Modules\Place\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteStationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customId' => $this->custom_id,
            'name' => $this->name,
            'number' => $this->number,
            'stations' => $this->stations->map(function ($station) {
                return [
                    "name" => $station->name,
                    "order" => $station->pivot->order,
                ];
            }),
        ];
    }
}
