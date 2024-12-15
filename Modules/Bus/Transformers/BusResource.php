<?php

namespace Modules\Bus\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'custom_id' => $this->custom_id,
            'plate_number' => $this->plate_number,
            'status' => $this->status,
            'license' => $this->license,
            'route' => $this->route,
            'driver'=>$this->driver,
            'ticket'=>$this->ticket,
        ];
    }
}
