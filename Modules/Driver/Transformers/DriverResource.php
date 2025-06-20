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
            'status' => $this->status,
            'bus' => $this->bus ? [
                'id' => $this->bus->id,
                'custom_id' => $this->bus->custom_id,
            ] : null,
            'photo' => $imageUrl ?: 'https://res.cloudinary.com/dnrhne5fh/image/upload/v1733608099/mspvvthjcuokw7eiyxo6.png',
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'days' => json_decode($this->days, true) ?: [],
        ];
    }
}
