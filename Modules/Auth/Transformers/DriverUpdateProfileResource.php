<?php

namespace Modules\Auth\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriverUpdateProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'custom_id' => $this->custom_id,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'image' => $this->fetchFirstMedia()['file_url'] ?? null ?: 'https://res.cloudinary.com/dnrhne5fh/image/upload/v1733608099/mspvvthjcuokw7eiyxo6.png',
        ];
    }
}
