<?php

namespace Modules\Driver\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $imageUrl = $this->fetchFirstMedia()['file_url'] ?? null;

        return [
            'id' => $this->id,
            'custom_id' => $this->custom_id,
            'fullname' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'national_id' => $this->national_id,
            'Driver_license' => $this->driver_license,
            'photo' => $imageUrl ?: env('APP_URL').'/public/media/default/person.png',
        ];
    }
}
